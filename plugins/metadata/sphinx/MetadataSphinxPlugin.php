<?php

/**
 * Enable indexing and searching of metadata objects in sphinx
 * @package plugins.metadataSphinx
 */
class MetadataSphinxPlugin extends BorhanPlugin implements IBorhanCriteriaFactory
{
	const PLUGIN_NAME = 'metadataSphinx';
	
	/* (non-PHPdoc)
	 * @see IBorhanPlugin::getPluginName()
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanCriteriaFactory::getBorhanCriteria()
	 */
	public static function getBorhanCriteria($objectType)
	{
		if ($objectType == "Metadata")
			return new SphinxMetadataCriteria();
			
		return null;
	}
}
