<?php
/**
 *
 * Associative array of BorhanOptionalAnswer
 *
 * @package plugins.quiz
 * @subpackage api.objects
 */

class BorhanOptionalAnswersArray extends BorhanAssociativeArray {

	public function __construct()
	{
		return parent::__construct("BorhanOptionalAnswer");
	}

	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanOptionalAnswersArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$answerObj = new BorhanOptionalAnswer();
			$answerObj->fromObject($obj, $responseProfile);
			$newArr[] = $answerObj;
		}

		return $newArr;
	}
}