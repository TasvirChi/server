<?php
/**
 * @package infra
 * @subpackage Plugins
 */
class BorhanDependency
{
	/**
	 * @var string
	 */
	protected $pluginName;
	
	/**
	 * @var BorhanVersion
	 */
	protected $minVersion;
	
	/**
	 * @param string $pluginName
	 * @param BorhanVersion $minVersion
	 */
	public function __construct($pluginName, BorhanVersion $minVersion = null)
	{
		$this->pluginName = $pluginName;
		$this->minVersion = $minVersion;
	}
	
	/**
	 * @return string plugin name
	 */
	public function getPluginName()
	{
		return $this->pluginName;
	}

	/**
	 * @return BorhanVersion minimum version
	 */
	public function getMinimumVersion()
	{
		return $this->minVersion;
	}
}