<?php
/**
 * @package plugins.podcastDistribution
 * @subpackage lib
 */
class PodcastDistributionEngine extends DistributionEngine implements 
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
		return true;
	}


	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseSubmit::closeSubmit()
	 */
	public function closeSubmit(BorhanDistributionSubmitJobData $data)
	{
		return true;
	}


	/* (non-PHPdoc)
	 * @see IDistributionEngineDelete::delete()
	 */
	public function delete(BorhanDistributionDeleteJobData $data)
	{
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseDelete::closeDelete()
	 */
	public function closeDelete(BorhanDistributionDeleteJobData $data)
	{
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineCloseUpdate::closeUpdate()
	 */
	public function closeUpdate(BorhanDistributionUpdateJobData $data)
	{
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineReport::fetchReport()
	 */
	public function fetchReport(BorhanDistributionFetchReportJobData $data)
	{
		return true;
	}

	/* (non-PHPdoc)
	 * @see IDistributionEngineUpdate::update()
	 */
	public function update(BorhanDistributionUpdateJobData $data)
	{
		return true;
	}

}