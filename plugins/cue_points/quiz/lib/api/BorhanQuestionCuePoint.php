<?php
/**
 * @package plugins.quiz
 * @subpackage api.objects
 */
class BorhanQuestionCuePoint extends BorhanCuePoint
{

	/**
	 * Array of key value answerKey->optionAnswer objects
	 * @var BorhanOptionalAnswersArray
	 */
	public $optionalAnswers;


	/**
	 * @var string
	 */
	public $hint;


	/**
	 * @var string
	 * @filter like,mlikeor,mlikeand
	 */
	public $question;

	/**
	 * @var string
	 */
	public $explanation;


	public function __construct()
	{
		$this->cuePointType = QuizPlugin::getApiValue(QuizCuePointType::QUIZ_QUESTION);
	}

	private static $map_between_objects = array
	(
		"optionalAnswers",
		"hint",
		"question" => "name",
		"explanation",
	);

	/* (non-PHPdoc)
	 * @see BorhanCuePoint::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	/* (non-PHPdoc)
	* @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	*/
	public function toObject($dbObject = null, $propsToSkip = array())
	{
		if (!$dbObject)
		{
			$dbObject = new QuestionCuePoint();
		}

		return parent::toObject($dbObject, $propsToSkip);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($dbObject, $responseProfile);
		$this->optionalAnswers = BorhanOptionalAnswersArray::fromDbArray($dbObject->getOptionalAnswers(), $responseProfile);
		$dbEntry = entryPeer::retrieveByPK($dbObject->getEntryId());
		if ( !kEntitlementUtils::isEntitledForEditEntry($dbEntry) ) {
			foreach ( $this->optionalAnswers as $answer ) {
				$answer->isCorrect = BorhanNullableBoolean::NULL_VALUE;
			}
			$this->explanation = null;
		}
	}

	/* (non-PHPdoc)
	 * @see BorhanCuePoint::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		parent::validateForInsert($propertiesToSkip);
		$dbEntry = entryPeer::retrieveByPK($this->entryId);
		QuizPlugin::validateAndGetQuiz($dbEntry);
		if ( !kEntitlementUtils::isEntitledForEditEntry($dbEntry) ) {
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID);
		}
	}

}
