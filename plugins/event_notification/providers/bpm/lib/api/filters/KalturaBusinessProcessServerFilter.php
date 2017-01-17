<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage api.filters
 */
class BorhanBusinessProcessServerFilter extends BorhanBusinessProcessServerBaseFilter
{
	/**
	 * @var BorhanNullableBoolean
	 */
	public $currentDcOrExternal;

	/**
	 * @var BorhanNullableBoolean
	 */
	public $currentDc;

	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new BusinessProcessServerFilter();
	}

	/* (non-PHPdoc)
	 * @see BorhanFilter::toObject()
	 */
	public function toObject ( $object_to_fill = null, $props_to_skip = array() )
	{
		if(!$this->isNull('currentDc') && BorhanNullableBoolean::toBoolean($this->currentDc))
			$this->dcEqual = kDataCenterMgr::getCurrentDcId();

		elseif(!$this->isNull('currentDcOrExternal') && BorhanNullableBoolean::toBoolean($this->currentDcOrExternal))
		{
			$this->dcEqOrNull = kDataCenterMgr::getCurrentDcId();
		}

		return parent::toObject($object_to_fill, $props_to_skip);
	}
}
