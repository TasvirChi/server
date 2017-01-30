<?php
/**
 * @package plugins.drm
 * @subpackage api.objects
 */
class BorhanDrmDevice extends BorhanObject implements IFilterable
{	
	/**
	 * @var int
	 * @readonly
	 */
	public $id;
	
	/**
	 * @var int
	 * @insertonly
	 * @filter eq,in
	 */
	public $partnerId;
		
	/**
	 * @var string
	 * @filter like
	 */
	public $deviceId;
	
	/**
	 * @var BorhanDrmProviderType
	 * @filter eq,in
	 */
	public $provider;
		
	/**
	 * @var int
	 * @readonly
	 * @filter order
	 */
	public $createdAt;

	/**
	 * @var int
	 * @readonly
	 */
	public $updatedAt;
	
	/*
	 * mapping between the field on this object (on the left) and the setter/getter on the entry object (on the right)  
	 */
	private static $map_between_objects = array(
		'id',
		'partnerId',
		'provider',
		'deviceId',
		'createdAt',
		'updatedAt',
	 );
		 
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	public function toObject($dbObject = null, $skip = array())
	{
		if (is_null($dbObject))
			$dbObject = new DrmDevice();
		parent::toObject($dbObject, $skip);		
		return $dbObject;
	}
		
	/**
	 * @param int $type
	 * @return BorhanDrmDevice
	 */
	static function getInstanceByType ($provider)
	{
		$obj = BorhanPluginManager::loadObject('BorhanDrmDevice', $provider);		
		if(!$obj)
			$obj = new BorhanDrmDevice();
		return $obj;
	}

	public function getExtraFilters()
	{
		return array();
	} 
	
	public function getFilterDocs()
	{
		return null;
	}
	
}