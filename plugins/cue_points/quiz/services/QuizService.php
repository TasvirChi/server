<?php
/**
 * Allows user to handle quizzes
 *
 * @service quiz
 * @package plugins.quiz
 * @subpackage api.services
 */

class QuizService extends BorhanBaseService
{

	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		if(!QuizPlugin::isAllowedPartner($this->getPartnerId()))
		{
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, QuizPlugin::PLUGIN_NAME);
		}
	}

	/**
	 * Allows to add a quiz to an entry
	 *
	 * @action add
	 * @param string $entryId
	 * @param BorhanQuiz $quiz
	 * @return BorhanQuiz
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::INVALID_USER_ID
	 * @throws BorhanQuizErrors::PROVIDED_ENTRY_IS_ALREADY_A_QUIZ
	 */
	public function addAction( $entryId, BorhanQuiz $quiz )
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);

		if ( !is_null( QuizPlugin::getQuizData($dbEntry) ) )
			throw new BorhanAPIException(BorhanQuizErrors::PROVIDED_ENTRY_IS_ALREADY_A_QUIZ, $entryId);

		return $this->validateAndUpdateQuizData( $dbEntry, $quiz );
	}

	/**
	 * Allows to update a quiz
	 *
	 * @action update
	 * @param string $entryId
	 * @param BorhanQuiz $quiz
	 * @return BorhanQuiz
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::INVALID_USER_ID
	 * @throws BorhanQuizErrors::PROVIDED_ENTRY_IS_NOT_A_QUIZ
	 */
	public function updateAction( $entryId, BorhanQuiz $quiz )
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);
		$kQuiz = QuizPlugin::validateAndGetQuiz( $dbEntry );
		return $this->validateAndUpdateQuizData( $dbEntry, $quiz, $kQuiz->getVersion(), $kQuiz );
	}

	/**
	 * if user is entitled for this action will update quizData on entry
	 * @param entry $dbEntry
	 * @param BorhanQuiz $quiz
	 * @param int $currentVersion
	 * @param kQuiz|null $newQuiz
	 * @return BorhanQuiz
	 * @throws BorhanAPIException
	 */
	private function validateAndUpdateQuizData( entry $dbEntry, BorhanQuiz $quiz, $currentVersion = 0, kQuiz $newQuiz = null )
	{
		if ( !kEntitlementUtils::isEntitledForEditEntry($dbEntry) ) {
			BorhanLog::debug('Update quiz allowed only with admin KS or entry owner or co-editor');
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID);
		}
		$quizData = $quiz->toObject($newQuiz);
		$quizData->setVersion( $currentVersion+1 );
		QuizPlugin::setQuizData( $dbEntry, $quizData );
		$dbEntry->setIsTrimDisabled( true );
		$dbEntry->save();
		$quiz->fromObject( $quizData );
		return $quiz;
	}

	/**
	 * Allows to get a quiz
	 *
	 * @action get
	 * @param string $entryId
	 * @return BorhanQuiz
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 *
	 */
	public function getAction( $entryId )
	{
		$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);

		$kQuiz = QuizPlugin::getQuizData($dbEntry);
		if ( is_null( $kQuiz ) )
			throw new BorhanAPIException(BorhanQuizErrors::PROVIDED_ENTRY_IS_NOT_A_QUIZ, $entryId);

		$quiz = new BorhanQuiz();
		$quiz->fromObject( $kQuiz );
		return $quiz;
	}

	/**
	 * List quiz objects by filter and pager
	 *
	 * @action list
	 * @param BorhanQuizFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanQuizListResponse
	 */
	function listAction(BorhanQuizFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanQuizFilter;

		if (! $pager)
			$pager = new BorhanFilterPager ();

		return $filter->getListResponse($pager, $this->getResponseProfile());
	}

	/**
	 * creates a pdf from quiz object
	 * The Output type defines the file format in which the quiz will be generated
	 * Currently only PDF files are supported
	 * @action serve
	 * @param string $entryId
	 * @param BorhanQuizOutputType $quizOutputType
	 * @return file
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanQuizErrors::PROVIDED_ENTRY_IS_NOT_A_QUIZ
	 */
	public function serveAction($entryId, $quizOutputType)
	{
		BorhanLog::debug("Create a PDF Document for entry id [ " .$entryId. " ]");
		$dbEntry = entryPeer::retrieveByPK($entryId);

		//validity check
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);

		//validity check
		$kQuiz = QuizPlugin::getQuizData($dbEntry);
		if ( is_null( $kQuiz ) )
			throw new BorhanAPIException(BorhanQuizErrors::PROVIDED_ENTRY_IS_NOT_A_QUIZ, $entryId);

		//validity check
		if (!$kQuiz->getAllowDownload())
		{
			throw new BorhanAPIException(BorhanQuizErrors::QUIZ_CANNOT_BE_DOWNLOAD);
		}
		//create a pdf
		$kp = new kQuizPdf($entryId);
		$kp->createQuestionPdf();
		return $kp->submitDocument();
	}


	/**
	 * sends a with an api request for pdf from quiz object
	 *
	 * @action getUrl
	 * @param string $entryId
	 * @param BorhanQuizOutputType $quizOutputType
	 * @return string
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanQuizErrors::PROVIDED_ENTRY_IS_NOT_A_QUIZ
	 * @throws BorhanQuizErrors::QUIZ_CANNOT_BE_DOWNLOAD
	 */
	public function getUrlAction($entryId, $quizOutputType)
	{
		BorhanLog::debug("Create a URL PDF Document download for entry id [ " .$entryId. " ]");

		$dbEntry = entryPeer::retrieveByPK($entryId);
		if (!$dbEntry)
			throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $entryId);

		$kQuiz = QuizPlugin::getQuizData($dbEntry);
		if ( is_null( $kQuiz ) )
			throw new BorhanAPIException(BorhanQuizErrors::PROVIDED_ENTRY_IS_NOT_A_QUIZ, $entryId);

		//validity check
		if (!$kQuiz->getAllowDownload())
		{
			throw new BorhanAPIException(BorhanQuizErrors::QUIZ_CANNOT_BE_DOWNLOAD);
		}

		$finalPath ='/api_v3/service/quiz_quiz/action/serve/quizOutputType/';

		$finalPath .="$quizOutputType";
		$finalPath .= '/entryId/';
		$finalPath .="$entryId";
		$ksObj = $this->getKs();
		$ksStr = ($ksObj) ? $ksObj->getOriginalString() : null;
		$finalPath .= "/ks/".$ksStr;

		$partnerId = $this->getPartnerId();
		$downloadUrl = myPartnerUtils::getCdnHost($partnerId) . $finalPath;

		return $downloadUrl;
	}
}
