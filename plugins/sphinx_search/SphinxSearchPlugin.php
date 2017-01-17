<?php
/**
 * @package plugins.sphinxSearch
 */
class SphinxSearchPlugin extends BorhanPlugin implements IBorhanEventConsumers, IBorhanCriteriaFactory
{
	const PLUGIN_NAME = 'sphinxSearch';
	const SPHINX_SEARCH_MANAGER = 'kSphinxSearchManager';
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/**
	 * @return array
	 */
	public static function getEventConsumers()
	{
		return array(
			self::SPHINX_SEARCH_MANAGER,
		);
	}
	
	/**
	 * Creates a new BorhanCriteria for the given object name
	 * 
	 * @param string $objectType object type to create Criteria for.
	 * @return BorhanCriteria derived object
	 */
	public static function getBorhanCriteria($objectType)
	{
		if ($objectType == "entry")
			return new SphinxEntryCriteria();
			
		if ($objectType == "category")
			return new SphinxCategoryCriteria();
			
		if ($objectType == "kuser")
			return new SphinxKuserCriteria();
		
		if ($objectType == "categoryKuser")
			return new SphinxCategoryKuserCriteria();
			
		return null;
	}
}
