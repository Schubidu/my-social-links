<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan
 * Date: 01.04.12
 * Time: 12:22
 * To change this template use File | Settings | File Templates.
 */
class SocialLinkCollection extends ArrayObject
{
	public function __call($func, $argv)
	{
		if (!is_callable($func) || substr($func, 0, 6) !== 'array_')
		{
			throw new BadMethodCallException(__CLASS__.'->'.$func);
		}
		return call_user_func_array($func, array_merge(array($this->getArrayCopy()), $argv));
	}

	public function toIcons(){
		$icons = array();
		foreach($this->getArrayCopy() as $social){
			array_push($icons, substr($social->getInternalUrl(), 1));
		}
		return $icons;
	}

	static function initFromHtaccess($fileSrc = '.htaccess'){
		$htaccess = file_get_contents($fileSrc);
		$redirectsExp = explode("\n###", $htaccess);
		$redirectsExp = str_replace("##\nRedirect 301 ", "|", $redirectsExp[0]);
		$redirectsExp = str_replace("##", "", $redirectsExp);
		$redirects = explode("\n", $redirectsExp);

		$collection = new self();

		foreach ($redirects as $redirect) {

			$social = new SocialLink();

			$socialExp = explode("|", $redirect);
			$social->setName($socialExp[0]);
			$social->setUrl($socialExp[1]);

			$socialExp = explode(" ", $socialExp[2]);
			$social->setInternalUrl($socialExp[0]);


			$collection->append($social);
		}

		return $collection;
	}
}
