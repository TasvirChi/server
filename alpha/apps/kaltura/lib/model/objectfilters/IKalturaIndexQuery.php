<?php

/**
 * @package Core
 * @subpackage model.filters
 */
interface IBorhanIndexQuery extends IBorhanDbQuery
{
	/**
	 * Add a new where clause condition to the query
	 * @param string $statement
	 */
	public function addWhere($statement);
	
	/**
	 * Add a new match condition clause to the query
	 * @param string $where
	 */
	public function addMatch($match);
	
	/**
	 * Add a new condition clause to the query
	 * @param string $condition
	 */
	public function addCondition($condition);
}
