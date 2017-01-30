<?php
/**
 * @package plugins.drm
 * @subpackage api.filters.base
 * @abstract
 */
abstract class BorhanDrmDeviceBaseFilter extends BorhanFilter
{
	static private $map_between_objects = array
	(
		"partnerIdEqual" => "_eq_partner_id",
		"partnerIdIn" => "_in_partner_id",
		"deviceIdLike" => "_like_device_id",
		"providerEqual" => "_eq_provider",
		"providerIn" => "_in_provider",
	);

	static private $order_by_map = array
	(
		"+deviceId" => "+device_id",
		"-deviceId" => "-device_id",
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
	public $deviceIdLike;

	/**
	 * @var BorhanDrmProviderType
	 */
	public $providerEqual;

	/**
	 * @dynamicType BorhanDrmProviderType
	 * @var string
	 */
	public $providerIn;
}
