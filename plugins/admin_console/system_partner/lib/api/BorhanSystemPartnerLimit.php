<?php
/**
 * @package plugins.systemPartner
 * @subpackage api.objects
 */
class BorhanSystemPartnerLimit extends BorhanObject
{
	/**
	 * @var BorhanSystemPartnerLimitType
	 */
	public $type;
	
	/**
	 * @var float
	 */
	public $max;
	
	/**
	 * @param BorhanSystemPartnerLimitType $type
	 * @param Partner $partner
	 * @return BorhanSystemPartnerLimit
	 */
	public static function fromPartner($type, Partner $partner)
	{
		$limit = new BorhanSystemPartnerLimit();
		$limit->type = $type;
		
		switch($type)
		{
			case BorhanSystemPartnerLimitType::ACCESS_CONTROLS:
				$limit->max = $partner->getAccessControls();
				break;
				
			case BorhanSystemPartnerLimitType::LIVE_STREAM_INPUTS:
				$limit->max = $partner->getMaxLiveStreamInputs();
				break;
				
			case BorhanSystemPartnerLimitType::LIVE_STREAM_OUTPUTS:
				$limit->max = $partner->getMaxLiveStreamOutputs();
				break;

			case BorhanSystemPartnerLimitType::USER_LOGIN_ATTEMPTS:
				$limit->max = $partner->getMaxLoginAttempts();
				break;
		}
		
		return $limit;
	} 

	public function validate()
	{
		switch($this->type)
		{
			case BorhanSystemPartnerLimitType::ACCESS_CONTROLS:
				$this->validatePropertyMinValue('max', 1, true);
				break;
				
			case BorhanSystemPartnerLimitType::LIVE_STREAM_INPUTS:
				$this->validatePropertyMinValue('max', 1, true);
				break;
				
			case BorhanSystemPartnerLimitType::LIVE_STREAM_OUTPUTS:
				$this->validatePropertyMinValue('max', 1, true);
				break;
				
			case BorhanSystemPartnerLimitType::USER_LOGIN_ATTEMPTS:
				$this->validatePropertyMinValue('max', 0, true);
				break;
		}
	}
	
	/**
	 * @param Partner $partner
	 */
	public function apply(Partner $partner)
	{
		if($this->isNull('max'))
			$this->max = null;
			
		switch($this->type)
		{
			case BorhanSystemPartnerLimitType::ACCESS_CONTROLS:
				$partner->setAccessControls($this->max);
				break;
				
			case BorhanSystemPartnerLimitType::LIVE_STREAM_INPUTS:
				$partner->setMaxLiveStreamInputs($this->max);
				break;
				
			case BorhanSystemPartnerLimitType::LIVE_STREAM_OUTPUTS:
				$partner->setMaxLiveStreamOutputs($this->max);
				break;
				
			case BorhanSystemPartnerLimitType::USER_LOGIN_ATTEMPTS:
				$partner->setMaxLoginAttempts($this->max);
				break;
		}
	} 
}