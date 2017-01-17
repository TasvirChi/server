<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanRule instead
 */
class BorhanSessionRestriction extends BorhanBaseRestriction 
{
	/* (non-PHPdoc)
	 * @see BorhanBaseRestriction::toRule()
	 */
	public function toRule(BorhanRestrictionArray $restrictions)
	{	
		$rule = null;
		
		foreach($restrictions as $restriction)
		{
			if($restriction instanceof BorhanPreviewRestriction)
			{
				$rule = $restriction->toObject(new kAccessControlPreviewRestriction());
			}
		}
	
		if(!$rule)
			$rule = $this->toObject(new kAccessControlSessionRestriction());
		
		return $rule;
	}
}