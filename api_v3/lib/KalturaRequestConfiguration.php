<?php

/**
 * Define client request optional configurations
 */
class BorhanRequestConfiguration extends BorhanObject
{
	/**
	 * Impersonated partner id
	 * @var int
	 */
	public $partnerId;
	
	/**
	 * Borhan API session
	 * @alias sessionId
	 * @var string
	 */
	public $ks;
	
	/**
	 * Response profile - this attribute will be automatically unset after every API call.
	 * @var BorhanBaseResponseProfile
	 * @volatile
	 */
	public $responseProfile;
}