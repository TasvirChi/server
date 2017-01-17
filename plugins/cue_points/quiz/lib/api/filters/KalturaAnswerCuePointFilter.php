<?php
/**
 * @package plugins.quiz
 * @subpackage api.filters
 */
class BorhanAnswerCuePointFilter extends BorhanAnswerCuePointBaseFilter
{
    /* (non-PHPdoc)
     * @see BorhanCuePointFilter::getCriteria()
     */
    protected function getCriteria()
    {
        return BorhanCriteria::create('AnswerCuePoint');
    }
    
	/* (non-PHPdoc)
	 * @see BorhanCuePointFilter::getTypeListResponse()
	 */
	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, $type = null)
	{
		if ($this->quizUserEntryIdIn || $this->quizUserEntryIdEqual)
		{
			BorhanCriterion::disableTag(BorhanCriterion::TAG_WIDGET_SESSION);
		}
		return parent::getTypeListResponse($pager, $responseProfile, QuizPlugin::getCoreValue('CuePointType',QuizCuePointType::QUIZ_ANSWER));
	}
	
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		kApiCache::disableCache();
		return new AnswerCuePointFilter();
	}	
}
