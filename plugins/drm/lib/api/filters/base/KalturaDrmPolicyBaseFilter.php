<?php
/**
 * @package plugins.drm
 * @subpackage api.filters.base
 * @abstract
 */
abstract class BorhanDrmPolicyBaseFilter extends BorhanFilter
{
	static private $map_between_objects = array
	(
		"partnerIdEqual" => "_eq_partner_id",
		"partnerIdIn" => "_in_partner_id",
		"nameLike" => "_like_name",
		"systemNameLike" => "_like_system_name",
		"providerEqual" => "_eq_provider",
		"providerIn" => "_in_provider",
		"statusEqual" => "_eq_status",
		"statusIn" => "_in_status",
		"scenarioEqual" => "_eq_scenario",
		"scenarioIn" => "_in_scenario",
	);

	static private $order_by_map = array
	(
	);

	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	public function getOrderByMap()
	{
		return array_merge(parent::getOrderByMap(), self::$order_by_map);
	}

	/**
	 * @var int
	 */
	public $partnerIdEqual;

	/**
	 * @var string
	 */
	public $partnerIdIn;

	/**
	 * @var string
	 */
	public $nameLike;

	/**
	 * @var string
	 */
	public $systemNameLike;

	/**
	 * @var BorhanDrmProviderType
	 */
	public $providerEqual;

	/**
	 * @dynamicType BorhanDrmProviderType
	 * @var string
	 */
	public $providerIn;

	/**
	 * @var BorhanDrmPolicyStatus
	 */
	public $statusEqual;

	/**
	 * @var string
	 */
	public $statusIn;

	/**
	 * @var BorhanDrmLicenseScenario
	 */
	public $scenarioEqual;

	/**
	 * @dynamicType BorhanDrmLicenseScenario
	 * @var string
	 */
	public $scenarioIn;
}
