<?php
/**
 * @package plugins.systemPartner
 * @subpackage api.objects
 */
class BorhanSystemPartnerLimitArray extends BorhanTypedArray
{
	/**
	 * @param Partner $partner
	 * @return BorhanSystemPartnerLimitArray
	 */
	public static function fromPartner(Partner $partner)
	{
		$arr = new BorhanSystemPartnerLimitArray();
		$reflector = BorhanTypeReflectorCacher::get('BorhanSystemPartnerLimitType');
		$types = $reflector->getConstants();
		foreach($types as $typeInfo) {
		    $typeValue = $typeInfo->getDefaultValue();
		    $arr[] = BorhanSystemPartnerOveragedLimit::fromPartner($typeValue, $partner);
		}
			
			
		return $arr;
	} 
	
	public function __construct()
	{
		return parent::__construct("BorhanSystemPartnerLimit");
	}
}