<?php
/**
 * @author Bilal Cinarli
 *
 * Feed class for RSS
 */

class feed {
	public static $feed_source;
	public static $feed_limit = 10;
	public static $fulltext = false;
	public static $title = '';
	public static $link = '';
	public static $author = '';
	public static $author_email = '';

	private $_feed_wrapper = '<feed xmlns="http://www.w3.org/2005/Atom">{{header}}{{content}}</feed>';
	private $_feed_header;
	private $_feed_text;

	/**
	 * Constructor method, defines the feed source
	 * @param $source, array of the source
	 */
	public function __construct($source = null)
	{
		if($source != null){
			self::$feed_source = $source;
		}

		$this->create_header();
		$this->create_feeds();

		$this->publish_feeds();
	}

	private function create_header()
	{
		$this->_feed_header = '
			<title>' . self::$title . '</title>
			<link href="' . self::$link . '" />
			<updated>' . self::$feed_source[0]['date'] . '</updated>
			<author>
				<name>' . self::$author . '</name>
				<email>' . self::$author_email . '</email>
			</author>
		';

	}

	private function create_feeds()
	{
		if(!is_array(self::$feed_source))
		{
			return;
		}

		$start = 1;
		foreach(self::$feed_source as $feed){
			if($start > self::$feed_limit) {
				break;
			}

			$summary = $feed['excerpt'];

			if(self::$fulltext == true){
				$md = new md('_articles/' . $feed['date'] . '-' . $feed['slug'] . '.md');
				$summary = $md::getContent();

				// make abs paths
				$summary = str_replace('href="/', 'href="http://' . url::getHost() . '/', $summary);
				$summary = str_replace('src="/', 'src="http://' . url::getHost() . '/', $summary);
			}

			$this->_feed_text .= '
				<entry>
					<title>' . $feed['title'] . '</title>
					<updated>' . $feed['date'] . '</updated>
					<summary type="html">
					<![CDATA[<p>' . $summary . '</p>]]>
					</summary>
					<link href="' . url::make_abs_url('article/' . $feed['slug']) .'" />
				</entry>';

			$start++;
		}

		return $this->_feed_text;
	}

	private function publish_feeds()
	{
		header('Content-Type: application/xml; charset=utf-8');

		$this->_feed_wrapper = str_replace('{{header}}', $this->_feed_header, $this->_feed_wrapper);
		$this->_feed_wrapper = str_replace('{{content}}', $this->_feed_text, $this->_feed_wrapper);

		echo $this->_feed_wrapper;
	}
} 