<?php
/**
 * @package plugins.httpNotification
 * @subpackage api.objects
 */
class BorhanHttpNotificationTemplate extends BorhanEventNotificationTemplate
{
	/**
	 * Remote server URL
	 * @var string
	 */
	public $url;
	
	/**
	 * Request method.
	 * @var BorhanHttpNotificationMethod
	 */
	public $method;
	
	/**
	 * Data to send.
	 * 
	 * @var BorhanHttpNotificationData
	 */
	public $data;
	
	/**
	 * The maximum number of seconds to allow cURL functions to execute.
	 * 
	 * @var int
	 */
	public $timeout;
	
	/**
	 * The number of seconds to wait while trying to connect.
	 * Must be larger than zero.
	 * 
	 * @var int
	 */
	public $connectTimeout;
	
	/**
	 * A username to use for the connection.
	 * 
	 * @var string
	 */
	public $username;
	
	/**
	 * A password to use for the connection.
	 * 
	 * @var string
	 */
	public $password;
	
	/**
	 * The HTTP authentication method to use.
	 * 
	 * @var BorhanHttpNotificationAuthenticationMethod
	 */
	public $authenticationMethod;
	
	/**
	 * The SSL version (2 or 3) to use.
	 * By default PHP will try to determine this itself, although in some cases this must be set manually.
	 * 
	 * @var BorhanHttpNotificationSslVersion
	 */
	public $sslVersion;
	
	/**
	 * SSL certificate to verify the peer with.
	 * 
	 * @var string
	 */
	public $sslCertificate;
	
	/**
	 * The format of the certificate.
	 * 
	 * @var BorhanHttpNotificationCertificateType
	 */
	public $sslCertificateType;
	
	/**
	 * The password required to use the certificate.
	 * 
	 * @var string
	 */
	public $sslCertificatePassword;
	
	/**
	 * The identifier for the crypto engine of the private SSL key specified in ssl key.
	 * 
	 * @var string
	 */
	public $sslEngine;
	
	/**
	 * The identifier for the crypto engine used for asymmetric crypto operations.
	 * 
	 * @var string
	 */
	public $sslEngineDefault;
	
	/**
	 * The key type of the private SSL key specified in ssl key - PEM / DER / ENG.
	 * 
	 * @var BorhanHttpNotificationSslKeyType
	 */
	public $sslKeyType;
	
	/**
	 * Private SSL key.
	 * 
	 * @var string
	 */
	public $sslKey;
	
	/**
	 * The secret password needed to use the private SSL key specified in ssl key.
	 * 
	 * @var string
	 */
	public $sslKeyPassword;
	
	/**
	 * Adds a e-mail custom header
	 * 
	 * @var BorhanKeyValueArray
	 */
	public $customHeaders;
	
	private static $map_between_objects = array
	(
		'url',
		'method',
		'data',
		'timeout',
		'connectTimeout',
		'username',
		'password',
		'authenticationMethod',
		'sslVersion',
		'sslCertificate',
		'sslCertificateType',
		'sslCertificatePassword',
		'sslEngine',
		'sslEngineDefault',
		'sslKeyType',
		'sslKey',
		'sslKeyPassword',
		'customHeaders',
	);
	
	public function __construct()
	{
		$this->type = HttpNotificationPlugin::getApiValue(HttpNotificationTemplateType::HTTP);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		$this->validatePropertyMinValue('connectTimeout', 1, true);
		return parent::validateForInsert($propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUpdate()
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		$propertiesToSkip[] = 'type';
		$this->validatePropertyMinValue('connectTimeout', 1, true);
		return parent::validateForUpdate($sourceObject, $propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if(is_null($dbObject))
			$dbObject = new HttpNotificationTemplate();
			
		return parent::toObject($dbObject, $propertiesToSkip);
	}
	 
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject HttpNotificationTemplate */
		parent::doFromObject($dbObject, $responseProfile);
		
		if($this->shouldGet('data', $responseProfile) && $dbObject->getData())
			$this->data = BorhanHttpNotificationData::getInstance($dbObject->getData());
	}
}
