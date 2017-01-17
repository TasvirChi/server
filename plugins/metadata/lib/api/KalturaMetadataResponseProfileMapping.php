<?php
/**
 * @package plugins.metadata
 * @subpackage api.objects
 */
class BorhanMetadataResponseProfileMapping extends BorhanResponseProfileMapping
{
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($object = null, $propertiesToSkip = array())
	{
		if(is_null($object))
		{
			$object = new kMetadataResponseProfileMapping();
		}

		return parent::toObject($object, $propertiesToSkip);
	}

	public function apply(BorhanRelatedFilter $filter, BorhanObject $parentObject)
	{
		$filterProperty = $this->filterProperty;
		$parentProperty = $this->parentProperty;

		BorhanLog::info("Mapping XPath $parentProperty to " . get_class($filter) . "::$filterProperty");
	
		if(!$parentObject instanceof BorhanMetadata)
		{
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_TYPE, get_class($parentObject));
		}

		if(!property_exists($filter, $filterProperty))
		{
			throw new BorhanAPIException(BorhanErrors::PROPERTY_IS_NOT_DEFINED, $filterProperty, get_class($filter));
		}

		$xml = $parentObject->xml;
		$doc = new KDOMDocument();
		$doc->loadXML($xml);
		$xpath = new DOMXPath($doc);
		$metadataElements = $xpath->query($parentProperty);
		if ($metadataElements->length == 1)
		{
			$filter->$filterProperty = $metadataElements->item(0)->nodeValue;
		}
		elseif ($metadataElements->length > 1)
		{
			$values = array();
			foreach($metadataElements as $element)
				$values[] = $element->nodeValue;
			$filter->$filterProperty = implode(',', $values);
		}
		elseif (!$this->allowNull)
		{
			return false;
		}
		return true;
	}
}