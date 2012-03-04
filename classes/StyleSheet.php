<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan
 * Date: 05.02.12
 * Time: 18:58
 * To change this template use File | Settings | File Templates.
 */
class StyleSheet
{

	private $query = '';
	private $src = '';
	private $width = 0;
	private $height = 0;
	private $useAsDefault = false;

	public function __construct($query, $src, $width = 0, $height = 0, $useAsDefault = false)
	{
		$this->setQuery($query);
		$this->setWidth($width);
		$this->setHeight($height);
		$this->setSrc($src);
		$this->setUseAsDefault($useAsDefault);
	}

	protected function setQuery($query)
	{
		$this->query = $query;
	}

	public function getQuery()
	{
		return $this->query;
	}

	protected function setSrc($src)
	{
		$this->src = $src;
	}

	public function getSrc()
	{
		return $this->src;
	}

	public static function dimensions($width = 0, $height = 0){
		if($width !== 0 && $height !== 0){
			return "width: ".$width."px; height:".$height."px;";
		} elseif ($width !== 0 &&  $height === 0){
			return "width: ".$width."px; height:".$width."px;";
		}
	}

	public static function backgroundImage($directory, $icon, $fileExtension = 'png')
	{
		return "background-image: url(" . $directory . $icon . '.' . $fileExtension . ")\n";
	}

	public static function backgroundImage_base64($directory, $icon, $fileExtension = 'png')
	{
		$datauri = "data:image/png;base64," . base64_encode(file_get_contents(substr($directory . $icon . '.' . $fileExtension, 3)));
		return "background-image: url(" . $datauri . ")\n";
	}

	public function generateCode(array $icons){
		$query = $this->getQuery();
		$ret = '';
		if($this->getUseAsDefault()){
			$ret.= $this->generateDefaultCode($icons);
		}
		$ret.= "@media $query {\n";
		$ret.="\tli a {". self::dimensions($this->getWidth(), $this->getHeight()) ."}\n";
		$ret.="}\n";

		return $ret;
	}

	public function generateDefaultCode(array $icons)
	{
		$ret = "li a {";
		$ret .= self::dimensions($this->getWidth(), $this->getHeight());
		$ret .= "}\n";
		foreach ($icons as $icon) {
			$ret .= "li a.icon-$icon {" . StyleSheet::backgroundImage($this->getSrc(), $icon) . "}\n";
		}

		return $ret;
	}

	protected function setWidth($width)
	{
		$this->width = $width;
	}

	public function getWidth()
	{
		return $this->width;
	}

	protected function setHeight($height)
	{
		$this->height = $height;
	}

	public function getHeight()
	{
		return $this->height;
	}

	protected function setUseAsDefault($useAsDefault)
	{
		$this->useAsDefault = $useAsDefault;
	}

	public function getUseAsDefault()
	{
		return $this->useAsDefault;
	}

}
