<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage api.objects
 */
class BorhanBusinessProcessNotificationDispatchJobData extends BorhanEventNotificationDispatchJobData
{
	/**
	 * @var BorhanBusinessProcessServer
	 */
	public $server;
	
	/**
	 * @var string
	 */
	public $caseId;
	
	private static $map_between_objects = array
	(
		'caseId',
	);

	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	protected function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kBusinessProcessNotificationDispatchJobData */
		parent::doFromObject($dbObject, $responseProfile);
		
		$server = $dbObject->getServer();
		$this->server = BorhanBusinessProcessServer::getInstanceByType($server->getType());
		$this->server->fromObject($server);
	}
}
