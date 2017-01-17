<?php
/**
 * @package Core
 * @subpackage model.filters.advanced
 */ 
class AdvancedSearchFilterMatchAttributeCondition extends AdvancedSearchFilterMatchCondition
{
	/**
	 * Adds conditions, matches and where clauses to the query
	 * @param IBorhanDbQuery $query
	 */
	public function applyCondition(IBorhanDbQuery $query)
	{
		if (!$query instanceof IBorhanIndexQuery)
			return;

		$matchText = '"'.BorhanCriteria::escapeString($this->value).'"';
		if ($this->not)
			$matchText = '!'.$matchText;
		$query->addMatch("@$this->field (".$matchText.")");
	}
}
