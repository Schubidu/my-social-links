<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan
 * Date: 12.02.12
 * Time: 15:59
 * To change this template use File | Settings | File Templates.
 */
class StyleConfig extends ArrayObject
{
	public function __call($func, $argv)
	{
		if (!is_callable($func) || substr($func, 0, 6) !== 'array_') {
			throw new BadMethodCallException(__CLASS__ . '->' . $func);
		}
		return call_user_func_array($func, array_merge(array($this->getArrayCopy()), $argv));
	}

	public function append(StyleSheet $value)
	{
		parent::append($value);
	}

	public function getSource($icons)
	{
		$styles = '';
		$queries = array();
		foreach ($this as $styleSheet) {
			$styles .= $styleSheet->generateCode($icons);
			if (!isset($queries[$styleSheet->getSrc()])) {
				$queries[$styleSheet->getSrc()] = array();
			}
			array_push($queries[$styleSheet->getSrc()], $styleSheet->getQuery());
		}
		foreach ($queries as $key => $query) {
			$styles .= '@media ' . implode(',', $query) . " {\n";
			$styles .= '/* ' . $key . " */\n";
			foreach ($icons as $icon) {
				$styles .= "\tli a.icon-$icon {" . StyleSheet::backgroundImage_base64($key, $icon) . "}\n";
			}
			$styles .= "}\n";
		}
		return $styles;
	}

	/*	public function __construct($input = null, int $flags = 0, string $iterator_class = "ArrayIterator")
	   {

		   parent::__construct($input, $flags, $iterator_class);
	   }*/


}
