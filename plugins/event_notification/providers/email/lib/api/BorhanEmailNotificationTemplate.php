<?php
/**
 * @package plugins.emailNotification
 * @subpackage api.objects
 */
class BorhanEmailNotificationTemplate extends BorhanEventNotificationTemplate
{	
	/**
	 * Define the email body format
	 * @var BorhanEmailNotificationFormat
	 * @requiresPermission update
	 */
	public $format;
	
	/**
	 * Define the email subject 
	 * @var string
	 */
	public $subject;
	
	/**
	 * Define the email body content
	 * @var string
	 */
	public $body;
	
	/**
	 * Define the email sender email
	 * @var string
	 */
	public $fromEmail;
	
	/**
	 * Define the email sender name
	 * @var string
	 */
	public $fromName;
	
	/**
	 * Email recipient emails and names
	 * @var BorhanEmailNotificationRecipientProvider
	 */
	public $to;
	
	/**
	 * Email recipient emails and names
	 * @var BorhanEmailNotificationRecipientProvider
	 */
	public $cc;
	
	/**
	 * Email recipient emails and names
	 * @var BorhanEmailNotificationRecipientProvider
	 */
	public $bcc;
	
	/**
	 * Default email addresses to whom the reply should be sent. 
	 * 
	 * @var BorhanEmailNotificationRecipientProvider
	 */
	public $replyTo;
	
	/**
	 * Define the email priority
	 * @var BorhanEmailNotificationTemplatePriority
	 * @requiresPermission update
	 */
	public $priority;
	
	/**
	 * Email address that a reading confirmation will be sent
	 * 
	 * @var string
	 */
	public $confirmReadingTo;
	
	/**
	 * Hostname to use in Message-Id and Received headers and as default HELLO string. 
	 * If empty, the value returned by SERVER_NAME is used or 'localhost.localdomain'.
	 * 
	 * @var string
	 * @requiresPermission update
	 */
	public $hostname;
	
	/**
	 * Sets the message ID to be used in the Message-Id header.
	 * If empty, a unique id will be generated.
	 * 
	 * @var string
	 * @requiresPermission update
	 */
	public $messageID;
	
	/**
	 * Adds a e-mail custom header
	 * 
	 * @var BorhanKeyValueArray
	 * @requiresPermission update
	 */
	public $customHeaders;
	
	/**
	 * mapping between the field on this object (on the left) and the setter/getter on the entry object (on the right)  
	 */
	private static $map_between_objects = array(
		'format',
		'subject',
		'body',
		'fromEmail',
		'fromName',
		'to',
		'cc',
		'bcc',
		'replyTo',
		'priority',
		'confirmReadingTo',
		'hostname',
		'messageID',
		'customHeaders',
	);
		 
	public function __construct()
	{
		$this->type = EmailNotificationPlugin::getApiValue(EmailNotificationTemplateType::EMAIL);
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
		$this->validatePropertyNotNull('format');
		return parent::validateForInsert($propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUpdate()
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		$propertiesToSkip[] = 'type';
		return parent::validateForUpdate($sourceObject, $propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if(is_null($dbObject))
			$dbObject = new EmailNotificationTemplate();
			
		return parent::toObject($dbObject, $propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($source_object)
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject EmailNotificationTemplate */
		parent::doFromObject($dbObject, $responseProfile);
		
		if($this->shouldGet('to', $responseProfile) && $dbObject->getTo())
			$this->to = BorhanEmailNotificationRecipientProvider::getProviderInstance($dbObject->getTo());
		if($this->shouldGet('cc', $responseProfile) && $dbObject->getCc())
			$this->cc = BorhanEmailNotificationRecipientProvider::getProviderInstance($dbObject->getCc());
		if($this->shouldGet('bcc', $responseProfile) && $dbObject->getBcc())
			$this->bcc = BorhanEmailNotificationRecipientProvider::getProviderInstance($dbObject->getBcc());
		if($this->shouldGet('replyTo', $responseProfile) && $dbObject->getReplyTo())
			$this->replyTo = BorhanEmailNotificationRecipientProvider::getProviderInstance($dbObject->getReplyTo());
	}
}