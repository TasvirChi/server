<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanSessionInfo extends BorhanObject 
{
	/**
	 * @var string
	 * @readonly
	 */
	public $ks;

	/**
	 * @var BorhanSessionType
	 * @readonly
	 */
	public $sessionType;

	/**
	 * @var int
	 * @readonly
	 */
	public $partnerId;

	/**
	 * @var string
	 * @readonly
	 */
	public $userId;

	/**
	 * @var int expiry time in seconds (unix timestamp)
	 * @readonly
	 */
	public $expiry;

	/**
	 * @var string
	 * @readonly
	 */
	public $privileges;
}
