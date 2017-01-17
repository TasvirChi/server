<?php
/**
 * @package api
 * @subpackage objects
 * @abstract
 */
abstract class BorhanDataCenterContentResource extends BorhanContentResource 
{
	public function getDc()
	{
		return kDataCenterMgr::getCurrentDcId();
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUsage($sourceObject, $propertiesToSkip)
	 */
	public function validateForUsage($sourceObject, $propertiesToSkip = array())
	{
		parent::validateForUsage($sourceObject, $propertiesToSkip);
		
		$dc = $this->getDc();
		if($dc == kDataCenterMgr::getCurrentDcId())
			return;
			
		$remoteDCHost = kDataCenterMgr::getRemoteDcExternalUrlByDcId($dc);
		if($remoteDCHost)
			kFileUtils::dumpApiRequest($remoteDCHost);
			
		throw new BorhanAPIException(BorhanErrors::REMOTE_DC_NOT_FOUND, $dc);
	}
}