<?php
/**
 * @package api
 * @subpackage objects
 */
abstract class BorhanBaseResponseProfile extends BorhanObject implements IApiObjectFactory
{
	public static function getInstance($sourceObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$object = null;
		
		if($sourceObject instanceof ResponseProfile)
		{
			$object = new BorhanResponseProfile();
		}
		elseif($sourceObject instanceof kResponseProfile)
		{
			$object = new BorhanDetachedResponseProfile();
		}
		
		if($object)
		{
			$object->fromObject($sourceObject, $responseProfile);
		}
		
		return $object;
	}
}