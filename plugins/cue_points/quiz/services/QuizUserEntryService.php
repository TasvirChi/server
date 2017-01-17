<?php

/**
 *
 * @service quizUserEntry
 * @package plugins.quiz
 * @subpackage api.services
 */
class QuizUserEntryService extends BorhanBaseService{

	/**
	 * Submits the quiz so that it's status will be submitted and calculates the score for the quiz
	 *
	 * @action submitQuiz
	 * @actionAlias userEntry.submitQuiz
	 * @param int $id
	 * @return BorhanQuizUserEntry
	 * @throws BorhanAPIException
	 */
	public function submitQuizAction($id)
	{
		$dbUserEntry = UserEntryPeer::retrieveByPK($id);
		if (!$dbUserEntry)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $id);
		
		if ($dbUserEntry->getType() != QuizPlugin::getCoreValue('UserEntryType',QuizUserEntryType::QUIZ))
			throw new BorhanAPIException(BorhanQuizErrors::PROVIDED_ENTRY_IS_NOT_A_QUIZ, $id);
		
		$dbUserEntry->setStatus(QuizPlugin::getCoreValue('UserEntryStatus', QuizUserEntryStatus::QUIZ_SUBMITTED));
		$userEntry = new BorhanQuizUserEntry();
		$userEntry->fromObject($dbUserEntry, $this->getResponseProfile());
		$entryId = $dbUserEntry->getEntryId();
		$entry = entryPeer::retrieveByPK($entryId);
		if(!$entry)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $entryId);
		
		$kQuiz = QuizPlugin::getQuizData($entry);
		if (!$kQuiz)
			throw new BorhanAPIException(BorhanQuizErrors::PROVIDED_ENTRY_IS_NOT_A_QUIZ, $entryId);
		
		list($score, $numOfCorrectAnswers) = $dbUserEntry->calculateScoreAndCorrectAnswers();
		$dbUserEntry->setScore($score);
		$dbUserEntry->setNumOfCorrectAnswers($numOfCorrectAnswers);	
		if ($kQuiz->getShowGradeAfterSubmission()== BorhanNullableBoolean::TRUE_VALUE || $this->getKs()->isAdmin() == true)
		{
			$userEntry->score = $score;
		}
		else
		{
			$userEntry->score = null;
		}

		$c = new Criteria();
		$c->add(CuePointPeer::ENTRY_ID, $dbUserEntry->getEntryId(), Criteria::EQUAL);
		$c->add(CuePointPeer::TYPE, QuizPlugin::getCoreValue('CuePointType', QuizCuePointType::QUIZ_QUESTION));
		$dbUserEntry->setNumOfQuestions(CuePointPeer::doCount($c));
		$dbUserEntry->setStatus(QuizPlugin::getCoreValue('UserEntryStatus', QuizUserEntryStatus::QUIZ_SUBMITTED));
		$dbUserEntry->save();

		return $userEntry;
	}
}
