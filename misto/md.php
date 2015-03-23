<?php
/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/

use \Michelf\MarkdownExtra;

/**
 * Markdown parser for Misto. It uses the Michelf's Markdown classes to parse the page
 * @dependency Michelf/Markdown
 * @dependency Michelf/MarkdownExtra
 */
class md extends MarkdownExtra
{
	private static $_valid_file = false;
	private static $_file;
	private static $_text;
	private static $_meta = array();
	private static $_content;
	public static $html;
    public static $doc_root = '_articles';

	/**
	 * constructor function for Markdown parser in Misto.
	 * @param $file path of the markdown file
	 */
	public function __construct($file)
	{
        parent::__construct();

		self::$_file = $file;

		// check if developer added the md file ext. while calling the constructor
		if (tools::extension($file) == '') {
			self::$_file = $file . '.md';
		}

		$this->is_exists();

		if (self::$_valid_file === true) {
			$this->getMetadata();
			$this->setMetadata();
		    $this->transformMD();
		}
	}

	/**
	 * Check if file exists, if not loads the 404 page
	 */
	private function is_exists()
	{
		$file_path = ABS_PATH . POSTS_PATH . self::$doc_root . '/' . self::$_file;

		// check if file exists
		if (!file_exists($file_path)) {
			// article names starts with their dates
			if (strstr($file_path, self::$doc_root)) {
				$file_path = str_replace(self::$doc_root . '/', self::$doc_root . '/*', $file_path);
				$article   = glob($file_path);

				if (!$article) {
					router::setRoute('404');

					header("HTTP/1.0 404 Not Found");
					tools::inc(router::getRoute(), '', 'require_once');
					exit;
				} else {
					$file_path = $article[0];
				}
			} else {
				router::setRoute('404');

				header("HTTP/1.0 404 Not Found");
				tools::inc(router::getRoute(), '', 'require_once');
				exit;
			}
		}

		self::$_valid_file = true;
		self::$_text       = file_get_contents($file_path, FILE_USE_INCLUDE_PATH);
	}

	/**
	 * Gets the meta data at the beginning of the Markdown file
	 */
	private function getMetadata()
	{
		$tmp = explode('---', self::$_text);

		if ($tmp[0] !== '') {
			self::$_content = self::$_text;

			return;
		}

        if(!isset($tmp[1])){
            return;
        }

		$tmp_meta = explode("\n", $tmp[1]);

		// remove the meta part from the content and set the content
		unset($tmp[0]);
		unset($tmp[1]);

		self::$_content = implode('---', $tmp);

		// set the metas

		$key     = '';
		$value   = '';
		$tmp_val = array();

		foreach ($tmp_meta as $meta) {
			$meta = trim($meta);

			if (empty($meta)) continue;

			// for list of values, they start with dash
			if (in_array(substr(trim($meta), 0, 1), array('-', '*'))) {
				$tmp_val[] = trim(substr($meta, 1));

				self::$_meta[$key] = $tmp_val;
			} // key : value pair
			else {
				$mt      = explode(':', $meta, 2);
				$new_key = trim($mt[0]);

				if (!empty($mt[1])) {
					$value = trim($mt[1]);
				}

				self::$_meta[$new_key] = $value;
			}

			// sets the new key and empty the value array
			if ($key != $new_key) {
				$key     = $new_key;
				$tmp_val = array();
			}
		}
	}

	/**
	 * Sets the meta data of the page if any
	 * @todo Seperate setMetadata and getMetadata functions and use them from article object.
	 */
	private function setMetadata()
	{
		if (count(self::$_meta) > 0) {
			foreach (self::$_meta as $key => $value) {
				// add all meta to html's meta property
				html::$meta[$key] = $value;

				// if also named property exist, set the value
				if (property_exists('html', $key)) {
					html::$$key = $value;
				}
			}
		}
	}

    /**
     * Inline code overwrites for class/id attribute support
     */
    protected function makeCodeSpan($code){
        $code = preg_replace_callback('{
                ([\*-_:a-zA-Z0-9 ]+)
                ('.$this->id_class_attr_catch_re.' )?
                }mx',
            array(&$this, '_makeCodeSpan_callback'), $code);

        return $code;
    }

    protected function _makeCodeSpan_callback($matches){
        $code = trim($matches[1]);
        $attrs =& $matches[3];

        $attr_str = $this->doExtraAttributes($this->code_attr_on_pre ? "pre" : "code", $attrs);

        return '<code' . $attr_str . '>' . $code . '</code>';
    }

    protected function doTables($text) {
        #
        # Form HTML tables.
        #
        $less_than_tab = $this->tab_width - 1;
        #
        # Find tables with leading pipe.
        #
        #	| Header 1 | Header 2
        #	| -------- | --------
        #	| Cell 1   | Cell 2
        #	| Cell 3   | Cell 4
        #
        $text = preg_replace_callback('
			{
				^							# Start of a line
				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				[|]							# Optional leading pipe (present)
				(.+) \n						# $1: Header row (at least one pipe)

				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				[|] ([ ]*[-:]+[-| :]*) \n	# $2: Header underline

				(							# $3: Cells
					(?>
						[ ]*				# Allowed whitespace.
						[|] .* \n			# Row content.
					)*
				)
				(?=\n|\Z)					# Stop at final double newline.
			}xm',
            array(&$this, '_doTable_leadingPipe_callback'), $text);

        #
        # Find tables without header.
        #   !---     | ---
        #	Cell 1   | Cell 2
        #	Cell 3   | Cell 4
        #
        $text = preg_replace_callback('
			{
				^							# Start of a line
				[!] ([ ]*[-:]+[-| :]*) \n	# $2: Header underline
				(							# $3: Cells
					(?>
						.* [|] .* \n		# Row content
					)*
				)
				(?=\n|\Z)					# Stop at final double newline.
			}xm',
            array(&$this, '_DoTable_withoutHeader_callback'), $text);


        #
        # Find tables without leading pipe.
        #
        #	Header 1 | Header 2
        #	-------- | --------
        #	Cell 1   | Cell 2
        #	Cell 3   | Cell 4
        #
        $text = preg_replace_callback('
			{
				^							# Start of a line
				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				(\S.*[|].*) \n				# $1: Header row (at least one pipe)

				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				([-:]+[ ]*[|][-| :]*) \n	# $2: Header underline

				(							# $3: Cells
					(?>
						.* [|] .* \n		# Row content
					)*
				)
				(?=\n|\Z)					# Stop at final double newline.
			}xm',
            array(&$this, '_DoTable_callback'), $text);

        return $text;
    }

    protected function _doTable_withoutHeader_callback($matches){
        $head = array();
        $underline = $matches[1];
        $content = $matches[2];

        $content	= preg_replace('/^ *[|]/m', '', $content);

        return $this->_doTable_callback(array($matches[0], $head, $underline, $content));
    }

    protected function _doTable_callback($matches) {
        $head		= $matches[1];
        $underline	= $matches[2];
        $content	= $matches[3];


        # Remove any tailing pipes for each line.
        $head		= preg_replace('/[|] *$/m', '', $head);
        $underline	= preg_replace('/[|] *$/m', '', $underline);
        $content	= preg_replace('/[|] *$/m', '', $content);

        # Reading alignement from header underline.
        $separators	= preg_split('/ *[|] */', $underline);
        $col_count = count($separators);
        foreach ($separators as $n => $s) {
            if (preg_match('/^ *-+: *$/', $s))
                $attr[$n] = $this->_doTable_makeAlignAttr('right');
            else if (preg_match('/^ *:-+: *$/', $s))
                $attr[$n] = $this->_doTable_makeAlignAttr('center');
            else if (preg_match('/^ *:-+ *$/', $s))
                $attr[$n] = $this->_doTable_makeAlignAttr('left');
            else
                $attr[$n] = '';
        }

        # Parsing span elements, including code spans, character escapes,
        # and inline HTML tags, so that pipes inside those gets ignored.
        if(!empty($head)){
            $head		= $this->parseSpan($head);
            $headers	= preg_split('/ *[|] */', $head);
            $attr       = array_pad($attr, $col_count, '');
        }

        # Write column headers.
        $text = "<table>\n";
        if(!empty($head)){
            $text .= "<thead>\n";
            $text .= "<tr>\n";
            foreach ($headers as $n => $header)
                $text .= $this->_doTable_cell(trim($header), $attr[$n], 'th') . "\n";
            $text .= "</tr>\n";
            $text .= "</thead>\n";
        }

        # Split content by row.
        $rows = explode("\n", trim($content, "\n"));

        $text .= "<tbody>\n";
        foreach ($rows as $row) {
            # Parsing span elements, including code spans, character escapes,
            # and inline HTML tags, so that pipes inside those gets ignored.
            $row = $this->parseSpan($row);

            # Split row by cell.
            $row_cells = preg_split('/ *[|] */', $row, $col_count);
            $row_cells = array_pad($row_cells, $col_count, '');

            $text .= "<tr>\n";
            foreach ($row_cells as $n => $cell)
                $text .= $this->_doTable_cell(trim($cell), $attr[$n]) . "\n";
            $text .= "</tr>\n";
        }
        $text .= "</tbody>\n";
        $text .= "</table>";

        return $this->hashBlock($text) . "\n";
    }

    protected function _doTable_cell($content, $attr, $cell = 'td'){
        $attr_str = '';

        preg_match(
                '{
                    ('.$this->id_class_attr_catch_re.')
                }xm', $content, $matches);

        if(!empty($matches)){
            $content = str_replace($matches[0], '', $content);
            $attr_str = $this->doExtraAttributes($this->code_attr_on_pre ? "pre" : "code", $matches[2]);
        }

        return '<' . $cell . $attr . $attr_str . '>' . $this->runSpanGamut(trim($content)) . '</' . $cell . '>';
    }

    /**
     * Builds the content
     */
    private function transformMD()
    {
        $this->code_attr_on_pre = true;
        self::$html = $this->transform(self::$_content);
    }

    /**
     * Corrects internal urls
     */
    public static function setLinks(){
        $url_prefix = url::homepage() . '/';

        // corrects anchors
        self::$html = str_replace('href="/', 'href="' . $url_prefix, self::$html);

        // corrects src paths
        self::$html = str_replace('src="/', 'src="' . $url_prefix, self::$html);

        return self::$html;
    }

	/**
	 * Gets a single meta
	 * @param $meta
	 */
	public static function getMeta($meta)
	{
		if (array_key_exists($meta, self::$_meta)) {
			return self::$_meta[$meta];
		}
	}

	public static function getContent()
	{
		return self::$html;
	}

	/**
	 * Prints the transformed html
	 */
	public static function printContent()
	{
		echo self::getContent();
	}
} 