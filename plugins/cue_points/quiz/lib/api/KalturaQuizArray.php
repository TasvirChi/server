<?php
/**
 * @package plugins.quiz
 * @subpackage api.objects
 */
class BorhanQuizArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanQuizArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$kQuiz = QuizPlugin::getQuizData($obj);
			if ( !is_null($kQuiz) ) {
				$quiz = new BorhanQuiz();
				$quiz->fromObject( $kQuiz, $responseProfile );
				$newArr[] = $quiz;
			}
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanQuiz");
	}
}