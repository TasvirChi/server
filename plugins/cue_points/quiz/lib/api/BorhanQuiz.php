<?php
/**
 * @package plugins.quiz
 * @subpackage api.objects
 */
class BorhanQuiz extends BorhanObject
{
	/**
	 *
	 * @var int
	 * @readonly
	 */
	public $version;

	/**
	 * Array of key value ui related objects
	 * @var BorhanKeyValueArray
	 */
	public $uiAttributes;

	/**
	 * @var BorhanNullableBoolean
	 */
	public $showResultOnAnswer;

	/**
	 * @var BorhanNullableBoolean
	 */
	public $showCorrectKeyOnAnswer;

	/**
	 * @var BorhanNullableBoolean
	 */
	public $allowAnswerUpdate;

	/**
	 * @var BorhanNullableBoolean
	 */
	public $showCorrectAfterSubmission;


	/**
	 * @var BorhanNullableBoolean
	 */
	public $allowDownload;

	/**
	 * @var BorhanNullableBoolean
	 */
	public $showGradeAfterSubmission;


	private static $mapBetweenObjects = array
	(
		"version",
		"uiAttributes",
		"showResultOnAnswer",
		"showCorrectKeyOnAnswer",
		"allowAnswerUpdate",
		"showCorrectAfterSubmission",
		"allowDownload",
		"showGradeAfterSubmission",
	);

	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($dbObject = null, $propsToSkip = array())
	{
		if (!$dbObject)
		{
			$dbObject = new kQuiz();
		}

		return parent::toObject($dbObject, $propsToSkip);
	}
}
