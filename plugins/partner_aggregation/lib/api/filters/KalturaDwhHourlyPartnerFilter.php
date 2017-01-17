<?php
/**
 * @package plugins.partnerAggregation
 * @subpackage api.filters
 */
class BorhanDwhHourlyPartnerFilter extends BorhanDwhHourlyPartnerBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new DwhHourlyPartnerFilter();
	}
	
	/**
	 * @see BorhanFilter::toObject()
	 * @return DwhHourlyPartnerFilter
	 */
	public function toObject($object_to_fill = null, $props_to_skip = array())
	{
		if(!is_null($this->aggregatedTimeLessThanOrEqual))
		{
			$object_to_fill->set('_lte_date_id', date('Ymd', $this->aggregatedTimeLessThanOrEqual));
			$object_to_fill->set('_lte_hour_id', date('G', $this->aggregatedTimeLessThanOrEqual));
		}
		if(!is_null($this->aggregatedTimeGreaterThanOrEqual))
		{
			$object_to_fill->set('_gte_date_id', date('Ymd', $this->aggregatedTimeGreaterThanOrEqual));
			$object_to_fill->set('_gte_hour_id', date('G', $this->aggregatedTimeGreaterThanOrEqual));
		}
		
		return parent::toObject($object_to_fill, $props_to_skip);
	}
}
