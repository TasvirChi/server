<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanBulkUploadResultCategoryUser extends BorhanBulkUploadResult
{
   /**
    * @var int
    */
   public $categoryId;
   
   /**
    * @var string
    */
   public $categoryReferenceId;
   
   /**
    * @var string
    */
   public $userId;
   
   /**
    * @var int
    */
   public $permissionLevel;
   
   /**
    * @var int
    */
   public $updateMethod;
   
   /**
    * @var int
    */
   public $requiredObjectStatus;
    
    private static $mapBetweenObjects = array
	(
	    "categoryId",
	    "categoryReferenceId",
		"userId",
	    "permissionLevel",
	    "updateMethod",
		"requiredObjectStatus" => "requiredStatus",
	);
	
    public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
	
    /* (non-PHPdoc)
     * @see BorhanBulkUploadResult::toInsertableObject()
     */
    public function toInsertableObject ( $object_to_fill = null , $props_to_skip = array() )
	{
		return parent::toInsertableObject(new BulkUploadResultCategoryKuser(), $props_to_skip);
	}
	
    /* (non-PHPdoc)
     * @see BorhanObject::toObject()
     */
    public function toObject($object_to_fill = null, $props_to_skip = array())
	{
	    //No need to add objectId to result with status ERROR
	    if ($this->status != BorhanBulkUploadResultStatus::ERROR)
	    {
		    $kuser = kuserPeer::getKuserByPartnerAndUid($this->partnerId, $this->userId);
		    if (!$kuser)
		    {
		        throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID);
		    }
		    $categoryKuser = categoryKuserPeer::retrieveByCategoryIdAndKuserId($this->categoryId, $kuser->getId());
		    if ($categoryKuser)
		        $this->objectId = $categoryKuser->getId();
	    }
	        
		return parent::toObject($object_to_fill, $props_to_skip);
	}
}