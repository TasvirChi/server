<?php
/**
 * @package api
 * @subpackage v3
 */
class BorhanReportHelper
{
	public static function getValidateExecutionParameters(Report $report, BorhanKeyValueArray $params = null)
	{
		if (is_null($params))
			$params = new BorhanKeyValueArray();
			
		$execParams = array();
		$currentParams = $report->getParameters();
		foreach($currentParams as $currentParam)
		{
			$found = false;
			foreach($params as $param)
			{
				/* @var $param BorhanKeyValue */
				if ((strtolower($param->key) == strtolower($currentParam)))
				{
					$execParams[':'.$currentParam] = $param->value;
					$found = true;
				}
			}
			
			if (!$found)
				throw new BorhanAPIException(BorhanErrors::REPORT_PARAMETER_MISSING, $currentParam);
		}
		return $execParams;
	}
}
