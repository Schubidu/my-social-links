<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan
 * Date: 03.12.11
 * Time: 16:14
 * To change this template use File | Settings | File Templates.
 */
class SocialLink {
	/**
	 * @var string
	 */
	private $name = "";
	/**
	 * @var string
	 */
	private $url = "";
	/**
	 * @var string
	 */
	private $internalUrl = "";

	/**
	 * @param $internalUrl string
	 */
	public function setInternalUrl($internalUrl)
	{
		$this->internalUrl = $internalUrl;
	}

	/**
	 * @return string
	 */
	public function getInternalUrl()
	{
		return $this->internalUrl;
	}

	/**
	 * @param $name string
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param $url string
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @static
	 * @param $socialStr string
	 * @return SocialLink
	 */
	public static function initFromHtAccess($socialStr) {

		$social = new SocialLink();

		$socialExp = explode("|", $socialStr);
		$social->setName($socialExp[0]);
		$social->setUrl($socialExp[1]);

		$socialExp = explode(" ", $socialExp[2]);
		$social->setInternalUrl($socialExp[0]);

		return $social;
	}
}

