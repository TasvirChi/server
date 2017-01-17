<?php
/**
 * @package plugins.emailNotification
 * @subpackage api.objects
 */
class BorhanEmailNotificationDispatchJobData extends BorhanEventNotificationDispatchJobData
{
	
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
	 * Email recipient emails and names, key is mail address and value is the name
	 * @var BorhanEmailNotificationRecipientJobData
	 */
	public $to;
	
	/**
	 * Email cc emails and names, key is mail address and value is the name
	 * @var BorhanEmailNotificationRecipientJobData
	 */
	public $cc;
	
	/**
	 * Email bcc emails and names, key is mail address and value is the name
	 * @var BorhanEmailNotificationRecipientJobData
	 */
	public $bcc;
	
	/**
	 * Email addresses that a replies should be sent to, key is mail address and value is the name
	 * 
	 * @var BorhanEmailNotificationRecipientJobData
	 */
	public $replyTo;
	
	/**
	 * Define the email priority
	 * @var BorhanEmailNotificationTemplatePriority
	 */
	public $priority;
	
	/**
	 * Email address that a reading confirmation will be sent to
	 * 
	 * @var string
	 */
	public $confirmReadingTo;
	
	/**
	 * Hostname to use in Message-Id and Received headers and as default HELO string. 
	 * If empty, the value returned by SERVER_NAME is used or 'localhost.localdomain'.
	 * 
	 * @var string
	 */
	public $hostname;
	
	/**
	 * Sets the message ID to be used in the Message-Id header.
	 * If empty, a unique id will be generated.
	 * 
	 * @var string
	 */
	public $messageID;
	
	/**
	 * Adds a e-mail custom header
	 * 
	 * @var BorhanKeyValueArray
	 */
	public $customHeaders;
	
	private static $map_between_objects = array
	(
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

	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kEmailNotificationDispatchJobData */
		parent::doFromObject($dbObject, $responseProfile);
		
		$this->to = BorhanEmailNotificationRecipientJobData::getDataInstance($dbObject->getTo());
		$this->cc = BorhanEmailNotificationRecipientJobData::getDataInstance($dbObject->getCc());
		$this->bcc = BorhanEmailNotificationRecipientJobData::getDataInstance($dbObject->getBcc());
		$this->replyTo = BorhanEmailNotificationRecipientJobData::getDataInstance($dbObject->getReplyTo());
		$this->customHeaders = BorhanKeyValueArray::fromKeyValueArray($dbObject->getCustomHeaders());
	}
}
