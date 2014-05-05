<?php

/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/
class navigation
{
	private $place;
	private static $_selecteds = array();
	private $nofollow;

	public function __construct()
	{

	}

	public static function setSelected($selected)
	{
		if (!empty($selected)) {
			self::$_selecteds = explode(',', $selected);
			$temp             = array();
			foreach (self::$_selecteds as $value) {
				$temp[] = trim($value);
			}
			self::$_selecteds = $temp;
		}
	}

	public static function getNav($menu, $params)
	{
		tools::inc_setting('navigations');

		$defaults = array(
			'id'     => '',
			'class'  => 'nav',
			'output' => 'ul',
			'wrap'   => 'li');
		$nav      = '';

		$options    = tools::params($defaults, $params);
		$navigation = $GLOBALS[$menu];

		if ($options['output'] == 'ul') {
			$nav .= '
                            <ul';
			if ($options['id'] != '') {
				$nav .= ' id="' . $options['id'] . '"';
			}

			$nav .= ' class="' . $options['class'] . ' group">';
		}

		foreach ($navigation as $value) {
			$nav .= self::nav_element($value, $options['wrap']);
		}

		if ($options['output'] == 'ul') {
			$nav .= '
                            </ul>';
		}

		echo $nav;
	}

	private static function nav_element($el, $wrap = 'li')
	{
		$element   = '';
		$name      = $el['name'];
		$title     = '';
		$url       = $el['url'];
		$wrapped   = $name;
		$css       = '';
		$linkcss   = '';
		$class     = '';
		$linkclass = '';
		$id        = '';
		$rel       = '';
		$target    = '';

		if (!empty($el['title'])) {
			$title = ' title="' . $el['title'] . '"';
		}
		if (!empty($el['wrap'])) {
			$wrapped = '<' . $el['wrap'] . '>' . $name . '</' . $el['wrap'] . '>';
		}
		if (!empty($el['class'])) {
			$css .= $el['class'] . ' ';
		}
		if (!empty($el['linkclass'])) {
			$linkcss .= $el['linkclass'] . ' ';
		}
		if (!empty($el['id'])) {
			$id .= 'id="' . $el['id'] . '" ';
		}
		if (!empty($el['rel'])) {
			$id .= 'rel="' . $el['rel'] . '" ';
		}
		if (!empty($el['target'])) {
			$target .= 'target="' . $el['target'] . '" ';
		}
		if (in_array($name, self::$_selecteds)) {
			$css .= 'current';
			$linkcss .= ' current';
		}
		if ($css != '') {
			$class = ' class="' . $css . '"';
		}
		if ($linkcss != '') {
			$linkclass = ' class="' . $linkcss . '"';
		}

		if ($url == '#') {
			$link = '#';
		} else {
			$link = str_replace('//', '/', url::make_url($url));
		}

		if ($wrap != '' && in_array($wrap, array('li', 'span', 'div'))) {
			$element .= '
		            <' . $wrap . $class . '>';
		}

		$element .= '
                    <a href="' . $link . '"' . $rel . $id . $linkclass . $title . $target . '>' . $wrapped . '</a>';

		if (!empty($el['children'])) {
			$element .= '
                    <ul>';
			for ($i = 0; $i < count($el['children']); $i++) {
				$element .= self::nav_element($el['children'][$i]);
			}
			$element .= '
                    </ul>';
		}

		if ($wrap != '' && in_array($wrap, array('li', 'span', 'div'))) {
			$element .= '
		            </' . $wrap . '>';
		}

		return $element;
	}

	public function getSelect($options = array(), $name)
	{
		$select = '
                <select name="' . $name . '" id="' . $name . '">';

		foreach ($options as $value) {
			$select .= '
                    <option>' . $value . '</option>';
		}

		$select .= '
                </select>';

		echo $select;
	}
}
