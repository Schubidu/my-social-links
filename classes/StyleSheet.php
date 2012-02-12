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

	public function __construct($query, $src, $width = 0, $height = 0)
	{
		$this->setQuery($query);
		$this->setWidth($width);
		$this->setHeight($height);
		$this->setSrc($src);
	}

	private function setQuery($query)
	{
		$this->query = $query;
	}

	public function getQuery()
	{
		return $this->query;
	}

	private function setSrc($src)
	{
		$this->src = $src;
	}

	public function getSrc()
	{
		return $this->src;
	}

	public function generateCode(array $icons){
		$query = $this->getQuery();
		$src = $this->getSrc();
		$width = $this->getWidth();
		$height = $this->getHeight();
		$ret = "@media $query {\n";
		if($width !== 0 && $height !== 0){
			$ret.="\ta {width: ".$width."px; height:".$height."px;}\n";
		} elseif ($width !== 0 &&  $height === 0){
			$ret.="\ta {width: ".$width."px; height:".$width."px;}\n";
		}
		$ret.="}\n";

		return $ret;
	}

	private function setWidth($width)
	{
		$this->width = $width;
	}

	public function getWidth()
	{
		return $this->width;
	}

	private function setHeight($height)
	{
		$this->height = $height;
	}

	public function getHeight()
	{
		return $this->height;
	}

}
