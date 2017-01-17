<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanAsset extends BorhanObject implements IRelatedFilterable, IApiObjectFactory
{
	/**
	 * The ID of the Flavor Asset
	 * 
	 * @var string
	 * @readonly
	 * @filter eq,in
	 */
	public $id;
	
	/**
	 * The entry ID of the Flavor Asset
	 * 
	 * @var string
	 * @readonly
	 * @filter eq,in
	 */
	public $entryId;
	
	/**
	 * @var int
	 * @readonly
	 * @filter eq,in
	 */
	public $partnerId;
	
	/**
	 * The version of the Flavor Asset
	 * 
	 * @var int
	 * @readonly
	 */
	public $version;
	
	/**
	 * The size (in KBytes) of the Flavor Asset
	 * 
	 * @var int
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $size;
	
	/**
	 * Tags used to identify the Flavor Asset in various scenarios
	 * 
	 * @var string
	 * @filter like,mlikeor,mlikeand
	 */
	public $tags;
	
	/**
	 * The file extension
	 * 
	 * @var string
	 * @insertonly
	 */
	public $fileExt;
	
	
	/**
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $createdAt;
	
	
	/**
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $updatedAt;
	
	
	/**
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $deletedAt;
	
	
	/**
	 * System description, error message, warnings and failure cause.
	 * @var string
	 * @readonly
	 */
	public $description;
	
	
	/**
	 * Partner private data
	 * @var string
	 */
	public $partnerData;
	
	/**
	 * Partner friendly description
	 * @var string
	 */
	public $partnerDescription;
	
	/**
	 * 
	 * Comma separated list of source flavor params ids
	 * @var string
	 */
	public $actualSourceAssetParamsIds;
	
	private static $map_between_objects = array
	(
		"id",
		"entryId",
		"partnerId",
		"version",
		"size",
		"tags",
		"fileExt",
		"createdAt",
		"updatedAt",
		"deletedAt",
		"description",
		"partnerData",
		"partnerDescription",
		"actualSourceAssetParamsIds",
	);
	
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	public function getExtraFilters()
	{
		return array();
	}
	
	public function getFilterDocs()
	{
		return array();
	}
	
	public static function getInstance($sourceObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
	     $type = $sourceObject->getType();
	     $object = null;
	     switch ($type)
	     {
	         case BorhanAssetType::FLAVOR:
	             $object = new BorhanFlavorAsset();
	             break;
	         case BorhanAssetType::LIVE:
	             $object = new BorhanLiveAsset();
	             break;
	         case BorhanAssetType::THUMBNAIL:
	             $object = new BorhanThumbAsset();
	             break;
	         default:
	             if($sourceObject instanceof thumbAsset)
                     {
	                 $object = BorhanPluginManager::loadObject('BorhanThumbAsset', $type);
	             }
	             elseif($sourceObject instanceof flavorAsset)
                     {
	                 $object = BorhanPluginManager::loadObject('BorhanFlavorAsset', $type);
	             }
	             else
	             {
	                 $object = BorhanPluginManager::loadObject('BorhanAsset', $type);
	             }
	     }
	     
	     // verify object was really generated
	     if (!$object)
	         return null;
	     // otherwise, create from given object
	     $object->fromObject($sourceObject, $responseProfile);
	     return $object;
	}	
}
