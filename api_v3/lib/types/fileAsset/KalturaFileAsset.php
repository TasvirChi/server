<?php
/**
 * @package api
 * @subpackage api.objects
 */
class BorhanFileAsset extends BorhanObject implements IRelatedFilterable 
{
	/**
	 * @var int
	 * @filter eq,in
	 * @readonly
	 */
	public $id;

	
	/**
	 * @var int
	 * @filter eq
	 * @readonly
	 */
	public $partnerId;

	
	/**
	 * 
	 * @var BorhanFileAssetObjectType
	 * @filter eq
	 * @insertonly
	 */
	public $fileAssetObjectType;

	
	/**
	 * 
	 * @var string
	 * @filter eq,in
	 * @insertonly
	 */
	public $objectId;

	
	/**
	 * 
	 * @var string
	 */
	public $name;

	
	/**
	 * 
	 * @var string
	 */
	public $systemName;

	
	/**
	 * 
	 * @var string
	 */
	public $fileExt;

	
	/**
	 * 
	 * @var int
	 * @readonly
	 */
	public $version;

	
	/**
	 * 
	 * @var int
	 * @filter gte,lte,order
	 * @readonly
	 */
	public $createdAt;


	/**
	 * 
	 * @var int
	 * @filter gte,lte,order
	 * @readonly
	 */
	public $updatedAt;

	
	/**
	 * 
	 * @var BorhanFileAssetStatus
	 * @filter eq,in
	 * @readonly
	 */
	public $status;
	
	private static $map_between_objects = array
	(
		"id",
		"partnerId",
		"fileAssetObjectType" => "objectType",
		"objectId",
		"name",
		"systemName",
		"fileExt",
		"version",
		"createdAt",
		"updatedAt",
		"status",
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see IFilterable::getExtraFilters()
	 */
	public function getExtraFilters()
	{
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IFilterable::getFilterDocs()
	 */
	public function getFilterDocs()
	{
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($dbFileAsset = null, $propsToSkip = array())
	{
		if(is_null($dbFileAsset))
			$dbFileAsset = new FileAsset();
			
		return parent::toObject($dbFileAsset, $propsToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert($propertiesToSkip)
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		$this->validatePropertyNotNull('fileAssetObjectType');
		$this->validatePropertyNotNull('objectId');
		
		switch($this->fileAssetObjectType)
		{
			case BorhanFileAssetObjectType::UI_CONF:
				$uiConf = uiConfPeer::retrieveByPK($this->objectId);
				if(!$uiConf)
					throw new BorhanAPIException(APIErrors::INVALID_UI_CONF_ID, $this->objectId);
					 
				break;
		}
	}
}