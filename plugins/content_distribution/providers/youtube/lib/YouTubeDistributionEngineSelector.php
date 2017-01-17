<?php
/**
 * @package plugins.youTubeDistribution
 * @subpackage lib
 */
class YouTubeDistributionEngineSelector extends DistributionEngine implements
	IDistributionEngineUpdate,
	IDistributionEngineSubmit,
	IDistributionEngineReport,
	IDistributionEngineDelete,
	IDistributionEngineCloseUpdate,
	IDistributionEngineCloseSubmit,
	IDistributionEngineCloseDelete
{
	/* (non-PHPdoc)
	 * @see IDistributionEngineSubmit::submit()
	 */
	public function submit(BorhanDistributionSubmitJobData $data)
	{
		$engine = $this->getEngineByProfile($data);
		return $engine->submit($data);
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseSubmit::closeSubmit()
	 */
	public function closeSubmit(BorhanDistributionSubmitJobData $data)
	{
		$engine = $this->getEngineByProfile($data);
		return $engine->closeSubmit($data);
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineDelete::delete()
	 */
	public function delete(BorhanDistributionDeleteJobData $data)
	{
		$engine = $this->getEngineByProfile($data);
		return $engine->delete($data);
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseDelete::closeDelete()
	 */
	public function closeDelete(BorhanDistributionDeleteJobData $data)
	{
		$engine = $this->getEngineByProfile($data);
		return $engine->closeDelete($data);
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineUpdate::update()
	 */
	public function update(BorhanDistributionUpdateJobData $data)
	{
		$engine = $this->getEngineByProfile($data);
		return $engine->update($data);
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseUpdate::closeUpdate()
	 */
	public function closeUpdate(BorhanDistributionUpdateJobData $data)
	{
		$engine = $this->getEngineByProfile($data);
		return $engine->closeUpdate($data);
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineReport::fetchReport()
	 */
	public function fetchReport(BorhanDistributionFetchReportJobData $data)
	{
		$engine = $this->getEngineByProfile($data);
		return $engine->fetchReport($data);
	}

	protected function getEngineByProfile(BorhanDistributionJobData $data)
	{
		if (!$data->distributionProfile instanceof BorhanYouTubeDistributionProfile)
			throw new Exception('Distribution profile is not of type BorhanYouTubeDistributionProfile for entry distribution #'.$data->entryDistributionId);

		if ($data->distributionProfile->feedSpecVersion == BorhanYouTubeDistributionFeedSpecVersion::VERSION_2)
			$engine = new YouTubeDistributionRightsFeedEngine();
		else
			$engine = new YouTubeDistributionLegacyEngine();

		if (KBatchBase::$taskConfig)
			$engine->configure();
		$engine->setClient();

		return $engine;
	}
}