<?php
/**
 * @package Core
 * @subpackage model.filters.advanced
 */ 
class AdvancedSearchFilterComparableCondition extends AdvancedSearchFilterCondition
{
	/**
	 * @var BorhanSearchConditionComparison
	 */
	public $comparison;

	/**
	 * Adds conditions, matches and where clauses to the query
	 * @param IBorhanIndexQuery $query
	 */
	public function applyCondition(IBorhanDbQuery $query)
	{
		switch ($this->getComparison())
		{
			case BorhanSearchConditionComparison::EQUAL:
				$comparison = ' = ';
				break;
			case BorhanSearchConditionComparison::GREATER_THAN:
				$comparison = ' > ';
				break;
			case BorhanSearchConditionComparison::GREATER_THAN_OR_EQUAL:
				$comparison = ' >= ';
				break;
			case BorhanSearchConditionComparison::LESS_THAN:
				$comparison = " < ";
				break;
			case BorhanSearchConditionComparison::LESS_THAN_OR_EQUAL:
				$comparison = " <= ";
				break;
			case BorhanSearchConditionComparison::NOT_EQUAL:
				$comparison = " <> ";
				break;
			default:
				BorhanLog::err("Missing comparison type");
				return;
		}

		$field = $this->getField();
		$value = $this->getValue();
		$fieldValue = $this->getFieldValue($field);
		if (is_null($fieldValue))
		{
			BorhanLog::err('Unknown field [' . $field . ']');
			return;
		}

		$newCondition = $fieldValue . $comparison . BorhanCriteria::escapeString($value);

		$query->addCondition($newCondition);
	}

	protected function getFieldValue($field)
	{
		$fieldValue = null;
		switch($field)
		{
			case Criteria::CURRENT_DATE:
				$d = getdate();
				$fieldValue = mktime(0, 0, 0, $d['mon'], $d['mday'], $d['year']);
				break;

			case Criteria::CURRENT_TIME:
			case Criteria::CURRENT_TIMESTAMP:
				$fieldValue = time();
				break;
		}
		return $fieldValue ;
	}

	public function addToXml(SimpleXMLElement &$xmlElement)
	{
		parent::addToXml($xmlElement);
		
		$xmlElement->addAttribute('comparison', htmlspecialchars($this->comparison));
	}
	
	public function fillObjectFromXml(SimpleXMLElement $xmlElement)
	{
		parent::fillObjectFromXml($xmlElement);
		
		$attr = $xmlElement->attributes();
		if(isset($attr['comparison']))
			$this->comparison = (string) html_entity_decode($attr['comparison']);
	}
	
	/**
	 * @return BorhanSearchConditionComparison $comparison
	 */
	public function getComparison() {
		return $this->comparison;
	}

	/**
	 * @param BorhanSearchConditionComparison $comparison the $comparison to set
	 */
	public function setComparison($comparison) {
		$this->comparison = $comparison;
	}
}
