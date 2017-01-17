<?php


class WSLiveStatsListResponse extends WSBaseObject
{				
	function getBorhanObject() {
		return new BorhanLiveStatsListResponse();
	}
	
	protected function getAttributeType($attributeName)
	{
		switch($attributeName)
		{	
			case 'objects':
				return 'WSLiveStatsArray';
			default:
				return parent::getAttributeType($attributeName);
		}
	}
					
	/**
	 * @var array
	 **/
	public $objects;
	
	/**
	 * @var int
	 **/
	public $totalCount;
	
}


