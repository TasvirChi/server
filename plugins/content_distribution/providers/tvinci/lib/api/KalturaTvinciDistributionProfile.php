<?php
/**
 * @package plugins.tvinciDistribution
 * @subpackage api.objects
 */
class BorhanTvinciDistributionProfile extends BorhanConfigurableDistributionProfile
{
	/**
	 * @var string
	 */
	public $ingestUrl;
	
	/**
	 * @var string
	 */
	public $username;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * Tags array for Tvinci distribution
	 * @var BorhanTvinciDistributionTagArray
	 */
	public $tags;

	/**
	 * @var string
	 */
	public $xsltFile;


	/*
	 * mapping between the field on this object (on the left) and the setter/getter on the object (on the right)
	 */
	private static $map_between_objects = array 
	(
		'ingestUrl',
		'username',
		'password',
		'tags',
		'xsltFile',
	 );
		 
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	/**
	 * @param TvinciDistributionProfile $srcObj
	 * @param BorhanDetachedResponseProfile $responseProfile
	 */
	protected function doFromObject($srcObj, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($srcObj, $responseProfile);
		$this->tags = BorhanTvinciDistributionTagArray::fromDbArray($srcObj->getTags());
	}
}