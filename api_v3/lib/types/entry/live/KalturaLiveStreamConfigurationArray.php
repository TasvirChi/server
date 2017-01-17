<?php
/**
 * @package api
 * @subpackage objects
 *
 */
class BorhanLiveStreamConfigurationArray extends BorhanTypedArray
{
	/**
	 * Returns API array object from regular array of database objects.
	 * @param array $dbArray
	 * @return BorhanLiveStreamConfiguration
	 */
	public static function fromDbArray(array $dbArray = null, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$array = new BorhanLiveStreamConfigurationArray();
		if($dbArray && is_array($dbArray))
		{
			foreach($dbArray as $object)
			{
				/* @var $object kLiveStreamConfiguration */
				$configObject = new BorhanLiveStreamConfiguration();
				$configObject->fromObject($object, $responseProfile);;
				$array[] = $configObject;
			}
		}
		return $array;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanLiveStreamConfiguration");
	}
	
	/* (non-PHPdoc)
	 * @see BorhanTypedArray::toObjectsArray()
	 */
	public function toObjectsArray()
	{
		$objects = $this->toArray();
		for ($i = 0; $i < count($objects); $i++)
		{
			for ($j = $i+1; $j <count($objects); $j++ )
			{
				if ($objects[$i]->protocol == $objects[$j]->protocol)
				{
					unset($objects[$i]);
				}
			}
		}
		
		$ret = array();
		foreach ($objects as $object)
		{
			$ret[] = $object->toObject();
		}
		
		return $ret;
	}
	
}