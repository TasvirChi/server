<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanConversionProfileAssetParams extends BorhanObject implements IRelatedFilterable 
{
	/**
	 * The id of the conversion profile
	 * 
	 * @var int
	 * @readonly
	 * @filter eq,in
	 */
	public $conversionProfileId;
	
	/**
	 * The id of the asset params
	 * 
	 * @var int
	 * @readonly
	 * @filter eq,in
	 */
	public $assetParamsId;

	/**
	 * The ingestion origin of the asset params
	 *  
	 * @var BorhanFlavorReadyBehaviorType
	 * @filter eq,in
	 */
	public $readyBehavior;

	/**
	 * The ingestion origin of the asset params
	 *  
	 * @var BorhanAssetParamsOrigin
	 * @filter eq,in
	 */
	public $origin;

	/**
	 * Asset params system name
	 *  
	 * @var string
	 * @filter eq,in
	 */
	public $systemName;
	
	/**
	 * Starts conversion even if the decision layer reduced the configuration to comply with the source
	 * @var BorhanNullableBoolean
	 */
	public $forceNoneComplied;
	
	/**
	 * 
	 * Specifies how to treat the flavor after conversion is finished
	 * @var BorhanAssetParamsDeletePolicy
	 */
	public $deletePolicy;
	
	/**
	 * @var BorhanNullableBoolean
	 */
	public $isEncrypted;

	/**
	 * @var float
	 */
	public $contentAwareness;
	
	/**
	 * @var BorhanNullableBoolean
	 */
	public $twoPass;

	private static $map_between_objects = array
	(
		'conversionProfileId',
		'assetParamsId' => 'flavorParamsId',
		'readyBehavior',
		'origin',
		'systemName',
		'forceNoneComplied',
		'deletePolicy',
		'isEncrypted',
		'contentAwareness',
		'twoPass',
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
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
	 * @see BorhanObject::validateForUpdate($sourceObject, $propertiesToSkip)
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		/* @var $sourceObject flavorParamsConversionProfile */
		$assetParams = $sourceObject->getassetParams();
		if(!$assetParams)
			throw new BorhanAPIException(BorhanErrors::ASSET_ID_NOT_FOUND, $sourceObject->getFlavorParamsId());
			
		if($assetParams instanceof liveParams && $this->origin == BorhanAssetParamsOrigin::CONVERT_WHEN_MISSING)
			throw new BorhanAPIException(BorhanErrors::LIVE_PARAMS_ORIGIN_NOT_SUPPORTED, $sourceObject->getFlavorParamsId(), $assetParams->getType(), $this->origin);
	}
}
