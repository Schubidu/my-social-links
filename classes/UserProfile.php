<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan
 * Date: 18.02.12
 * Time: 09:32
 * To change this template use File | Settings | File Templates.
 */
interface UserProfile
{
	/**
	 * @param string $url
	 */
	public function __construct(string $url);

	/**
	 * @abstract
	 * @return string
	 */
	public function getName();

	/**
	 * @abstract
	 * @return string
	 */
	public function getCreatedAt();

	/**
	 * @abstract
	 * @return string
	 */
	public function getAvatarUrl();

	/**
	 * @abstract
	 * @return string
	 */
	public function getUrl();
}
