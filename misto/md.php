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
class md
{
	private static $_valid_file = false;
	private static $_file;
	private static $_text;
	private static $_meta = array();
	private static $_content;
	public static $html;

	/**
	 * constructor function for Markdown parser in Misto.
	 * @param $file path of the markdown file
	 */
	public function __construct($file)
	{
		self::$_file = $file;

		// check if developer added the md file ext. while calling the constructor
		if (tools::extension($file) == '') {
			self::$_file = $file . '.md';
		}

		self::is_exists();

		if (self::$_valid_file === true) {
			self::getMetadata();
			self::setMetadata();
			self::transformMD();
		}
	}

	/**
	 * Check if file exists, if not loads the 404 page
	 */
	private function is_exists()
	{
		$file_path = ABS_PATH . APP_PATH . self::$_file;

		// check if file exists
		if (!file_exists($file_path)) {
			// article names starts with their dates
			if(strstr($file_path, '_articles')){
				$file_path = str_replace('_articles/', '_articles/*', $file_path);
				$article = glob($file_path);

				if(!$article){
					router::setRoute('404');

					header("HTTP/1.0 404 Not Found");
					tools::inc(router::getRoute(), '', 'require_once');
					exit;
				}

				else  {
					$file_path = $article[0];
				}
			}

			else {
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

		$tmp_meta = explode("\n", $tmp[1]);

		// remove the meta part from the content and set the content
		unset($tmp[0]);
		unset($tmp[1]);

		self::$_content = implode('---', $tmp);

		// set the metas
		foreach ($tmp_meta as $meta) {
			if ($meta != '') {
				$mt                        = explode(':', $meta);
				self::$_meta[trim($mt[0])] = trim($mt[1]);
			}
		}
	}

	/**
	 * Sets the meta data of the page if any
	 */
	private function setMetadata()
	{
		if (count(self::$_meta) > 0) {
			foreach (self::$_meta as $key => $value) {
				html::$$key = $value;
			}
		}
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

	/**
	 * Builds the content
	 */
	private static function transformMD()
	{
		self::$html = MarkdownExtra::defaultTransform(self::$_content);
	}

	/**
	 * Prints the transformed html
	 */
	public static function printContent()
	{
		echo self::$html;
	}
} 