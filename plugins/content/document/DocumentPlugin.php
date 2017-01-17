<?php
/**
 * @package plugins.document
 */
class DocumentPlugin extends BorhanPlugin implements IBorhanPlugin, IBorhanServices, IBorhanObjectLoader, IBorhanEventConsumers, IBorhanEnumerator, IBorhanTypeExtender
{
	const PLUGIN_NAME = 'document';
	const DOCUMENT_OBJECT_CREATED_HANDLER = 'DocumentCreatedHandler';
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	public function getInstance($interface)
	{
		if($this instanceof $interface)
			return $this;
			
		return null;
	}

	/* (non-PHPdoc)
	 * @see IBorhanTypeExtender::getExtendedTypes()
	 */
	public static function getExtendedTypes($baseClass, $enumValue)
	{
		$supportedBaseClasses = array(
			assetPeer::OM_CLASS,
			assetParamsPeer::OM_CLASS,
			assetParamsOutputPeer::OM_CLASS,
		);
		
		if(in_array($baseClass, $supportedBaseClasses) && $enumValue == assetType::FLAVOR)
		{
			return array(
				DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::PDF),
				DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::SWF),
				DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::DOCUMENT),
				DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::IMAGE),
			);
		}
		
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		
		// ENTRY
		if($baseClass == 'entry' && $enumValue == entryType::DOCUMENT)
		{
			return new DocumentEntry();
		}
		
		if($baseClass == 'BorhanBaseEntry' && $enumValue == entryType::DOCUMENT)
		{
			return new BorhanDocumentEntry();
		}
		
		
		// BORHAN FLAVOR PARAMS
		
		if($baseClass == 'BorhanFlavorParams')
		{
			switch($enumValue)
			{
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::PDF):
					return new BorhanPdfFlavorParams();
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::SWF):
					return new BorhanSwfFlavorParams();
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::DOCUMENT):
					return new BorhanDocumentFlavorParams();
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::IMAGE);
					return new BorhanImageFlavorParams();
				
				default:
					return null;	
			}
		}
	
		if($baseClass == 'BorhanFlavorParamsOutput')
		{
			switch($enumValue)
			{
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::PDF):
					return new BorhanPdfFlavorParamsOutput();
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::SWF):
					return new BorhanSwfFlavorParamsOutput();
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::DOCUMENT):
					return new BorhanDocumentFlavorParamsOutput();
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::IMAGE);
					return new BorhanImageFlavorParamsOutput();
				
				default:
					return null;	
			}
		}
		
		
		// OPERATION ENGINES
		
		if($baseClass == 'KOperationEngine' && $enumValue == BorhanConversionEngineType::PDF_CREATOR)
		{
			if(!isset($constructorArgs['params']) || !isset($constructorArgs['outFilePath']))
				return null;
			
			return new KOperationEnginePdfCreator($constructorArgs['params']->pdfCreatorCmd, $constructorArgs['outFilePath']);
		}

		
		if($baseClass == 'KOperationEngine' && $enumValue == BorhanConversionEngineType::PDF2SWF)
		{
			if(!isset($constructorArgs['params']) || !isset($constructorArgs['outFilePath']))
				return null;
			
			return new KOperationEnginePdf2Swf($constructorArgs['params']->pdf2SwfCmd, $constructorArgs['outFilePath']);
		}
		
		if($baseClass == 'KOperationEngine' && $enumValue == BorhanConversionEngineType::IMAGEMAGICK)
		{
			if(!isset($constructorArgs['params']) || !isset($constructorArgs['outFilePath']))
				return null;
			
			return new KOperationEngineImageMagick($constructorArgs['params']->imageMagickCmd, $constructorArgs['outFilePath']);
		}
		
		if($baseClass == 'KOperationEngine' && $enumValue == BorhanConversionEngineType::PPT2IMG)
		{
			if(!isset($constructorArgs['params']) || !isset($constructorArgs['outFilePath']))
				return null;
			return new KOperationEnginePpt2Image($constructorArgs['params']->ppt2ImgCmd, $constructorArgs['outFilePath']);
		}

		if($baseClass == 'KOperationEngine' && $enumValue == BorhanConversionEngineType::THUMB_ASSETS)
		{
			return new KOperationEngineThumbAssetsGenerator(null, null);
		}

		
		// KDL ENGINES
		
		if($baseClass == 'KDLOperatorBase' && $enumValue == conversionEngineType::PDF_CREATOR)
		{
			return new KDLTranscoderPdfCreator($enumValue);
		}
				
		if($baseClass == 'KDLOperatorBase' && $enumValue == conversionEngineType::PDF2SWF)
		{
			return new KDLTranscoderPdf2Swf($enumValue);
		}
		
		if($baseClass == 'KDLOperatorBase' && $enumValue == self::getApiValue(DocumentConversionEngineType::IMAGEMAGICK_ENGINE))
		{
			return new KDLTranscoderImageMagick($enumValue);
		}
		
		if($baseClass == 'KDLOperatorBase' && $enumValue == self::getApiValue(DocumentConversionEngineType::PPT2IMG_ENGINE))
		{
			return new KDLTranscoderPpt2Img($enumValue);
		}

		if($baseClass == 'KDLOperatorBase' && $enumValue == self::getApiValue(DocumentConversionEngineType::THUMB_ASSETS_ENGINE))
		{
			return new KDLTranscoderThumbAssetsGenerator($enumValue);
		}
		
		return null;
	}

	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		// DOCUMENT ENTRY
		if($baseClass == 'entry' && $enumValue == entryType::DOCUMENT)
		{
			return 'DocumentEntry';
		}
		
		// FLAVOR PARAMS
		if($baseClass == 'assetParams')
		{
			switch($enumValue)
			{
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::PDF):
					return 'PdfFlavorParams';
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::SWF):
					return 'SwfFlavorParams';
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::DOCUMENT):
					return 'flavorParams';
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::IMAGE);
					return 'ImageFlavorParams';
				
				default:
					return null;	
			}
		}
	
		if($baseClass == 'assetParamsOutput')
		{
			switch($enumValue)
			{
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::PDF):
					return 'PdfFlavorParamsOutput';
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::SWF):
					return 'SwfFlavorParamsOutput';
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::DOCUMENT):
					return 'flavorParamsOutput';
					
				case DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::IMAGE);
					return 'ImageFlavorParamsOutput';
				
				default:
					return null;	
			}
		}
		
		return null;
	}

	/**
	 * @return array<string,string> in the form array[serviceName] = serviceClass
	 */
	public static function getServicesMap()
	{
		$map = array(
			'documents' => 'DocumentsService',
		);
		return $map;
	}

	/**
	 * @return array
	 */
	public static function getEventConsumers()
	{
		return array(
			self::DOCUMENT_OBJECT_CREATED_HANDLER,
		);
	}
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getAssetTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('assetType', $value);
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
	
	/**
	 * @return array<string> list of enum classes names that extend the base enum name
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('DocumentAssetType','DocumentConversionEngineType');
	
		if($baseEnumName == 'assetType')
			return array('DocumentAssetType');
			
		if($baseEnumName == 'conversionEngineType')
			return array('DocumentConversionEngineType');
			
		return array();
	}
}
