<?php
/**
 * @package plugins.caption
 * @subpackage api.objects
 */
class BorhanCaptionAsset extends BorhanAsset  
{
	/**
	 * The Caption Params used to create this Caption Asset
	 * 
	 * @var int
	 * @insertonly
	 * @filter eq,in
	 */
	public $captionParamsId;
	
	/**
	 * The language of the caption asset content
	 * 
	 * @var BorhanLanguage
	 */
	public $language;
	
	/**
	 * The language of the caption asset content
	 * 
	 * @var BorhanLanguageCode
	 * @readonly
	 */
	public $languageCode;
	
	/**
	 * Is default caption asset of the entry
	 * 
	 * @var BorhanNullableBoolean
	 */
	public $isDefault;
	
	/**
	 * Friendly label
	 * 
	 * @var string
	 */
	public $label;
	
	/**
	 * The caption format
	 * 
	 * @var BorhanCaptionType
	 * @filter eq,in
	 * @insertonly
	 */
	public $format;
	
	/**
	 * The status of the asset
	 * 
	 * @var BorhanCaptionAssetStatus
	 * @readonly 
	 * @filter eq,in,notin
	 */
	public $status;

	/**
	 * The parent id of the asset
	 * @var string
	 * @insertonly
	 *
	 */
	public $parentId;

	/**
	 * The Accuracy of the caption content
	 *@var int 
	 */
	public $accuracy;

	private static $map_between_objects = array
	(
		"captionParamsId" => "flavorParamsId",
		"language",
		"isDefault" => "default",
		"label",
		"format" => "containerFormat",
		"status",
		"parentId",
		"accuracy",
	);
	
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	public function doFromObject($source_object, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$ret = parent::doFromObject($source_object, $responseProfile);
				
		if($this->shouldGet('languageCode', $responseProfile))
		{
			$languageReflector = BorhanTypeReflectorCacher::get('BorhanLanguage');
			$languageCodeReflector = BorhanTypeReflectorCacher::get('BorhanLanguageCode');
			if($languageReflector && $languageCodeReflector)
			{
				$languageCode = $languageReflector->getConstantName($this->language);
				if($languageCode)
					$this->languageCode = $languageCodeReflector->getConstantValue($languageCode);
			}
		}
			
		return $ret;
	}

	public function toInsertableObject ( $object_to_fill = null , $props_to_skip = array() )
	{
		if (!is_null($this->captionParamsId))
		{
			$dbAssetParams = assetParamsPeer::retrieveByPK($this->captionParamsId);
			if ($dbAssetParams)
			{
				$object_to_fill->setFromAssetParams($dbAssetParams);
			}
		}
		
		if ($this->format === null &&
			$object_to_fill->getContainerFormat() === null)		// not already set by setFromAssetParams
		{
			$this->format = BorhanCaptionType::SRT;
		}
		
		return parent::toInsertableObject ($object_to_fill, $props_to_skip);
	}


	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		parent::validateForInsert($propertiesToSkip);
	}
}
