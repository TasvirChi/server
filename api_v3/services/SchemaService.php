<?php
/**
 * Expose the schema definitions for syndication MRSS, bulk upload XML and other schema types. 
 * 
 * @service schema
 * @package api
 * @subpackage services
 */
class SchemaService extends BorhanBaseService 
{
	const CORE_SCHEMA_NAME = 'core';
	const ENUM_SCHEMA_NAME = 'enum';
	
	/* (non-PHPdoc)
	 * @see BorhanBaseService::partnerRequired()
	 */
	protected function partnerRequired($actionName)
	{
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseService::isPermitted()
	 */
	protected function isPermitted(&$allowPrivatePartnerData)
	{
		return true;
	}
	
	/**
	 * Serves the requested XSD according to the type and name. 
	 * 
	 * @action serve
	 * @param BorhanSchemaType $type  
	 * @return file 
	 */
	function serveAction($type)
	{
		$cachedXsdFilePath = self::getCachedXsdFilePath($type);
		if(file_exists($cachedXsdFilePath))
			return $this->dumpFile(realpath($cachedXsdFilePath), 'application/xml');
		
		$resultXsd = self::buildSchemaByType($type);
		kFile::safeFilePutContents($cachedXsdFilePath, $resultXsd, 0644);
		return new kRendererString($resultXsd, 'application/xml');
	}
	
	public static function getSchemaPath($type)
	{
		$cachedXsdFilePath = self::getCachedXsdFilePath($type);
		if(file_exists($cachedXsdFilePath))
			return realpath($cachedXsdFilePath);
		
		$resultXsd = self::buildSchemaByType($type);
		kFile::safeFilePutContents($cachedXsdFilePath, $resultXsd, 0644);
		return realpath($cachedXsdFilePath);
	}
	
	private static function getCachedXsdFilePath($type)
	{
		$cachedXsdFilePath = kConf::get("cache_root_path") . "/$type.xsd";
		return $cachedXsdFilePath;
	}
	
	private static function buildSchemaByType($type)
	{
		$elementsXSD = '';
		
		$baseXsdElement = new SimpleXMLElement('<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"/>');
		if($type == SchemaType::SYNDICATION)
		{
			$baseXsdElement = new SimpleXMLElement(file_get_contents(kConf::get("syndication_core_xsd_path")));
		}
		else
		{
			$plugin = kPluginableEnumsManager::getPlugin($type);
			if($plugin instanceof IBorhanSchemaDefiner)
			{
				$baseXsdElement = $plugin->getPluginSchema($type);
			}
		}
		
		if(!($baseXsdElement instanceof SimpleXMLElement))
			$baseXsdElement = new SimpleXMLElement('<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"/>');
		
		$version = '1.0';
		if($baseXsdElement['version'])
			$version = $baseXsdElement['version'];
		
		$resultXsd = "<xs:schema xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" version=\"$version\">";
		
		foreach($baseXsdElement->children('http://www.w3.org/2001/XMLSchema') as $element)
		{
			/* @var $element SimpleXMLElement */
			$xsd = $element->asXML();
			$elementsXSD .= $xsd;
			
			$resultXsd .= '
	' . $xsd;
		}
		
		$schemaContributors = BorhanPluginManager::getPluginInstances('IBorhanSchemaContributor');
		foreach($schemaContributors as $key => $schemaContributor)
		{
			/* @var $schemaContributor IBorhanSchemaContributor */
			$elements = $schemaContributor->contributeToSchema($type);
			if($elements)
			{
				$elementsXSD .= $elements;
				$resultXsd .= $elements;
			}
		}
		
		$resultXsd .= '
	<!-- Borhan enum types -->
	';
		
		$enumClasses = array();
		$matches = null;
		if(preg_match_all('/type="(Borhan[^"]+)"/', $elementsXSD, $matches))
			$enumClasses = $matches[1];
		
		$enumTypes = array();
		foreach($enumClasses as $class)
		{
			$classTypeReflector = BorhanTypeReflectorCacher::get($class);
			if($classTypeReflector)
				self::loadClassRecursively($classTypeReflector, $enumTypes);
		}
		
		foreach($enumTypes as $class => $classTypeReflector)
		{
			if(!is_subclass_of($class, 'BorhanEnum') && !is_subclass_of($class, 'BorhanStringEnum')) // class must be enum
				continue;
			
			$xsdType = 'int';
			if($classTypeReflector->isStringEnum())
				$xsdType = 'string';
			
			$xsd = '
	<xs:simpleType name="' . $class . '">
		<xs:annotation><xs:documentation>http://' . kConf::get('www_host') . '/api_v3/testmeDoc/index.php?object=' . $class . '</xs:documentation></xs:annotation>
		<xs:restriction base="xs:' . $xsdType . '">';
			
			$contants = $classTypeReflector->getConstants();
			foreach($contants as $contant)
			{
				$xsd .= '
			<xs:enumeration value="' . $contant->getDefaultValue() . '"><xs:annotation><xs:documentation>' . $contant->getName() . '</xs:documentation></xs:annotation></xs:enumeration>';
			}
			
			
			$xsd .= '
		</xs:restriction>
	</xs:simpleType>
			';
			
			$resultXsd .= $xsd;
		}
		
		$resultXsd .= '
</xs:schema>';
		
		return $resultXsd;
	}
	
	private static function loadClassRecursively(BorhanTypeReflector $classTypeReflector, &$enumClasses)
	{
		$class = $classTypeReflector->getType();
		if(
			$class == 'BorhanEnum'
			||
			$class == 'BorhanStringEnum'
			||
			$class == 'BorhanObject'
		)
			return;
			
		$enumClasses[$class] = $classTypeReflector;
		$parentClassTypeReflector = $classTypeReflector->getParentTypeReflector();
		if($parentClassTypeReflector)
			self::loadClassRecursively($parentClassTypeReflector, $enumClasses);
	}
}
