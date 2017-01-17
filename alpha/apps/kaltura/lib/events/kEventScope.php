<?php
/**
 * @package Core
 * @subpackage events
 */
class kEventScope extends kScope
{
	/**
	 * @var BorhanEvent
	 */
	protected $event;
	
	/**
	 * @var int
	 */
	protected $partnerId;
	
	/**
	 * @var BatchJob
	 */
	protected $parentRaisedJob;
	
	/**
	 * @param BorhanEvent $v
	 */
	public function __construct(BorhanEvent $v = null)
	{
		parent::__construct();
		$this->event = $v;
	}
	
	/**
	 * @return BorhanEvent
	 */
	public function getEvent()
	{
		return $this->event;
	}

	/**
	 * @return BaseObject|null
	 */
	public function getObject()
	{
		if ($this->event instanceof IBorhanObjectRelatedEvent)
			return $this->event->getObject();
		else
			return null;
	}
	
	/**
	 * @return int $partnerId
	 */
	public function getPartnerId()
	{
	    if (! $this->partnerId)
	    {
	        return kCurrentContext::$partner_id ? kCurrentContext::$partner_id : kCurrentContext::$ks_partner_id;
	    }
		
	    return $this->partnerId;
	}

	/**
	 * @return BatchJob $parentRaisedJob
	 */
	public function getParentRaisedJob()
	{
		return $this->parentRaisedJob;
	}

	/**
	 * @param int $partnerId
	 */
	public function setPartnerId($partnerId)
	{
		$this->partnerId = $partnerId;
	}

	/**
	 * @param BatchJob $parentRaisedJob
	 */
	public function setParentRaisedJob(BatchJob $parentRaisedJob)
	{
		$this->parentRaisedJob = $parentRaisedJob;
	}

	
}