<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanBatchGetExclusiveNotificationJobsResponse extends BorhanObject
{
	/**
	 * @var BorhanBatchJobArray
	 * @readonly
	 */
	public $notifications;

	/**
	 * @var BorhanPartnerArray
	 * @readonly
	 */
	public $partners;
}