<?php
/**
 * @package plugins.quiz
 * @subpackage api.filters
 */
class BorhanQuestionCuePointFilter extends BorhanQuestionCuePointBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::validateForResponseProfile()
	 */
	public function validateForResponseProfile()
	{
		// override BorhanCuePointFilter::validateForResponseProfile because all question cue-points are public
	}

	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, $type = null)
	{
		return parent::getTypeListResponse($pager, $responseProfile, QuizPlugin::getCoreValue('CuePointType',QuizCuePointType::QUIZ_QUESTION));
	}
}
