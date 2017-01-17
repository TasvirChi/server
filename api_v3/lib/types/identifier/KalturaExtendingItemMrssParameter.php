<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanExtendingItemMrssParameter extends BorhanObject
{
	/**
	 * XPath for the extending item
	 * @var string
	 */
	public $xpath;
	
	/**
	 * Object identifier
	 * @var BorhanObjectIdentifier
	 */
	public $identifier;
	
	/**
	 * Mode of extension - append to MRSS or replace the xpath content.
	 * @var BorhanMrssExtensionMode
	 */
	public $extensionMode;
	
	
	private static $map_between_objects = array(
			"xpath",
			"identifier",
			"extensionMode"
		);
		
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($dbObject = null, $propsToSkip = array())
	{
		$this->validate();
		if (!$dbObject)
			$dbObject = new kExtendingItemMrssParameter();

		return parent::toObject($dbObject, $propsToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($source_object)
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($dbObject, $responseProfile);
		
		/* @var $dbObject kExtendingItemMrssParameter */
		if($this->shouldGet('identifier', $responseProfile))
		{
			$identifierType = get_class($dbObject->getIdentifier());
			BorhanLog::info("Creating identifier for DB identifier type $identifierType");
			switch ($identifierType)
			{
				case 'kEntryIdentifier':
					$this->identifier = new BorhanEntryIdentifier();
					break;
				case 'kCategoryIdentifier':
					$this->identifier = new BorhanCategoryIdentifier();
			}
			
			if ($this->identifier)
				$this->identifier->fromObject($dbObject->getIdentifier());
		}
	}
	
	protected function validate ()
	{
		//Should not allow any extending object but entries to be added in APPEND mode
		if ($this->extensionMode == BorhanMrssExtensionMode::APPEND && get_class($this->identifier) !== 'BorhanEntryIdentifier')
		{
			throw new BorhanAPIException(BorhanErrors::EXTENDING_ITEM_INCOMPATIBLE_COMBINATION);
		}
		
		if (!$this->xpath)
		{
			throw new BorhanAPIException(BorhanErrors::EXTENDING_ITEM_MISSING_XPATH);
		}
	}
}