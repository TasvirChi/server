<?php
/**
 * @package plugins.quiz
 * @subpackage api.objects
 */
class BorhanAnswerCuePoint extends BorhanCuePoint
{
	/**
	 * @var string
	 * @filter eq,in
	 * @insertonly
	 */
	public $parentId;

	/**
	 * @var string
	 * @filter eq,in
	 * @insertonly
	 */
	public $quizUserEntryId;

	/**
	 * @var string
	 */
	public $answerKey;

	/**
	 * @var BorhanNullableBoolean
	 * @readonly
	 */
	public $isCorrect;

	/**
	 * Array of string
	 * @var BorhanStringArray
	 * @readonly
	 */
	public $correctAnswerKeys;

	/**
	 * @var string
	 * @readonly
	 */
	public $explanation;


	public function __construct()
	{
		$this->cuePointType = QuizPlugin::getApiValue(QuizCuePointType::QUIZ_ANSWER);
	}

	private static $map_between_objects = array
	(
		"quizUserEntryId",
		"answerKey",
		"parentId",
		"correctAnswerKeys",
		"isCorrect",
		"explanation"
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
			$dbObject = new AnswerCuePoint();
		}

		return parent::toObject($dbObject, $propsToSkip);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($dbObject, $responseProfile);

		$dbEntry = entryPeer::retrieveByPK($dbObject->getEntryId());
		if ( !kEntitlementUtils::isEntitledForEditEntry($dbEntry))
		{
			/**
			 * @var kQuiz $kQuiz
			 */
			$kQuiz = QuizPlugin::validateAndGetQuiz( $dbEntry );

			$dbUserEntry = UserEntryPeer::retrieveByPK($this->quizUserEntryId);
			if ($dbUserEntry && $dbUserEntry->getStatus() == QuizPlugin::getCoreValue('UserEntryStatus', QuizUserEntryStatus::QUIZ_SUBMITTED))
			{
				if (!$kQuiz->getShowCorrectAfterSubmission())
				{
					$this->isCorrect = null;
					$this->correctAnswerKeys = null;
					$this->explanation = null;
				}
			}
			else
			{
				if (!$kQuiz->getShowCorrect()) {
					$this->isCorrect = null;
				}
				if (!$kQuiz->getShowCorrectKey())
				{
					$this->correctAnswerKeys = null;
					$this->explanation = null;
				}
			}
		}
	}

	/*
	 * @param string $cuePointId
	 * @throw BorhanAPIException - when parent cue points is missing or not a question cue point or doesn't belong to the same entry
	 */
	public function validateParentId($cuePointId = null)
	{
		if ($this->isNull('parentId'))
			throw new BorhanAPIException(BorhanQuizErrors::PARENT_ID_IS_MISSING);

		$dbParentCuePoint = CuePointPeer::retrieveByPK($this->parentId);
		if (!$dbParentCuePoint)
			throw new BorhanAPIException(BorhanCuePointErrors::PARENT_CUE_POINT_NOT_FOUND, $this->parentId);

		if (!($dbParentCuePoint instanceof QuestionCuePoint))
			throw new BorhanAPIException(BorhanQuizErrors::WRONG_PARENT_TYPE, $this->parentId);

		if ($dbParentCuePoint->getEntryId() != $this->entryId)
			throw new BorhanAPIException(BorhanCuePointErrors::PARENT_CUE_POINT_DO_NOT_BELONG_TO_THE_SAME_ENTRY);

	}

	protected function validateUserEntry()
	{
		$dbUserEntry = UserEntryPeer::retrieveByPK($this->quizUserEntryId);
		if (!$dbUserEntry)
			throw new BorhanAPIException(BorhanErrors::USER_ENTRY_NOT_FOUND, $this->quizUserEntryId);
		if ($dbUserEntry->getEntryId() !== $this->entryId)
		{
			throw new BorhanAPIException(BorhanCuePointErrors::USER_ENTRY_DOES_NOT_MATCH_ENTRY_ID, $this->quizUserEntryId);
		}
		if ($dbUserEntry->getStatus() === QuizPlugin::getCoreValue('UserEntryStatus', QuizUserEntryStatus::QUIZ_SUBMITTED))
		{
			throw new BorhanAPIException(BorhanQuizErrors::USER_ENTRY_QUIZ_ALREADY_SUBMITTED);
		}
		if (!kCurrentContext::$is_admin_session && ($dbUserEntry->getKuserId() != kCurrentContext::getCurrentKsKuserId()) ) {
		    throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID);
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
		$this->validateParentId();
		$this->validateUserEntry();
	}

	/* (non-PHPdoc)
	 * @see BorhanCuePoint::validateForUpdate()
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		parent::validateForUpdate($sourceObject, $propertiesToSkip);
		$dbEntry = entryPeer::retrieveByPK($this->entryId);
		$kQuiz = QuizPlugin::validateAndGetQuiz($dbEntry);
		$this->validateUserEntry();
		if ( !$kQuiz->getAllowAnswerUpdate() ) {
			throw new BorhanAPIException(BorhanQuizErrors::ANSWER_UPDATE_IS_NOT_ALLOWED, $sourceObject->getEntryId());
		}
	}
}
