<?php
/**
 * @package Core
 * @subpackage model.filters.advanced
 */ 
class AdvancedSearchFilterItem
{
	/**
	 * @var string
	 */
	protected $borhanClass;

	final public function apply(baseObjectFilter $filter, IBorhanDbQuery $query)
	{
		$this->applyCondition($query);
	}
	
	public function getFreeTextConditions($partnerScope, $freeTexts)
	{
		return array();	
	}
	
	/**
	 * Adds conditions, matches and where clauses to the query
	 * @param IBorhanIndexQuery $query
	 */
	public function applyCondition(IBorhanDbQuery $query)
	{
	}
	
	public function addToXml(SimpleXMLElement &$xmlElement)
	{
		$xmlElement->addAttribute('borhanClass', $this->borhanClass);
	}
	
	public function fillObjectFromXml(SimpleXMLElement $xmlElement)
	{
		$attr = $xmlElement->attributes();
		if(isset($attr['borhanClass']))
			$this->borhanClass = (string) $attr['borhanClass'];
	}
	
	/**
	 * @return the $borhanClass
	 */
	public function getBorhanClass() {
		return $this->borhanClass;
	}

	/**
	 * @param $borhanClass the $borhanClass to set
	 */
	public function setBorhanClass($borhanClass) {
		$this->borhanClass = $borhanClass;
	}
}
