<?php
/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/

    $main_nav = array(
        array('name' => 'Homepage', 'url' => '/'),
        array('name' => 'About', 'url' => '/about'),
        array('name' => 'Documents', 'url' => '/documents', 'children' => array(
        	array('name' => 'Requirements', 'url' => '/documents/requirements'),
			array('name' => 'Installation', 'url' => '/documents/insallation')
        ))
	);

	$GLOBALS['main_nav'] = $main_nav;