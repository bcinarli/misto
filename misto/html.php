<?php
/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/

    class html
    {
        public static $title;
        public static $keywords;
        public static $description;
        public static $class;
        public static $id;
	    public static $meta = array();
	    public static $date;
	    public static $slug;
	    public static $category;
	    public static $tags;
	    public static $externalCSS;
	    public static $externalJS;
	    public static $lang;
	    public static $author;
	    public static $comments = false;

        public static function load_meta()
        {
            if(device::isMobile() || role::is_homepage() || role::is_404()){
                $meta = '<title>' . SITE_TITLE . '</title>' . "\n";
            }
            else{
                $meta = '<title>' . self::$title . ' | ' . SITE_TITLE . '</title>' . "\n";    
            }
            
            if (!empty(self::$keywords))
            {
                $meta .= "\t" . '<meta name="keywords" content="' . self::$keywords . '" />' . "\n";
            }

            if (!empty(self::$description))
            {
                $meta .= "\t" . '<meta name="description" content="' . self::$description . '" />' . "\n";
            }
            
            if(router::$is_404 === true){
                $meta .= "\n\t" . '<meta name="robots" content="nofollow, noindex" />' . "\n";
                $meta .= "\t" . '<meta name="robots" content="noarchive" />' . "\n";
                $meta .= "\t" . '<meta name="googlebot" content="noarchive" />' . "\n";
            }

            echo $meta;
        }

        public static function cssclass()
        {
            if(!empty(self::$class)){
                echo ' class="' . self::$class . '"';
            }
        }
    }