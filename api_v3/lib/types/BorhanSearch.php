<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated
 */
class BorhanSearch extends BorhanObject
{
	/**
	 * @var string
	 */
	public $keyWords;
	
	/**
	 * @var BorhanSearchProviderType
	 */
	public $searchSource;
	
	/**
	 * @var BorhanMediaType
	 */
	public $mediaType;
	
	/**
	 * Use this field to pass dynamic data for searching
	 * For example - if you set this field to "mymovies_$partner_id"
	 * The $partner_id will be automatically replcaed with your real partner Id
	 * 
	 * @var string
	 */
	public $extraData;
	
	/**
	 * @var string
	 */
	public $authData;
	
	public function fromSearch( )
	{
		
	}
	
	public function toSearch( )
	{
		
	}
}
