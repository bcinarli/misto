<?php
/**
  * Sample custom class for misto app
  **/

class sample {
	public static function helloWorld(){
		echo '<h2>Hello, this is a Misto App</h2>';
		
		echo '<h3 style="margin-bottom: 5px;">Some variables</h3>';
		echo '<p style="margin-top: 0;">Document Root: <code>' . url::getPath() . '</code><br />
				 Host: <code>' . url::getHost() . '</code><br />
				 Plain Host: <code>' . url::getPlainhost() . '</code><br />
				 Url: <code>' . url::getUrl() . '</code>
			  </p>';
	}
}