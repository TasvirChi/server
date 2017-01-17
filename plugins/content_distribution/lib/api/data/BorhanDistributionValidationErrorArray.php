<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.objects
 */
class BorhanDistributionValidationErrorArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanDistributionValidationErrorArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$nObj = null;
			switch($obj->getErrorType())
			{
				case DistributionErrorType::MISSING_FLAVOR:
    				$nObj = new BorhanDistributionValidationErrorMissingFlavor();
    				break;
    			
				case DistributionErrorType::MISSING_THUMBNAIL:
    				$nObj = new BorhanDistributionValidationErrorMissingThumbnail();
    				break;
    			
				case DistributionErrorType::MISSING_METADATA:
    				$nObj = new BorhanDistributionValidationErrorMissingMetadata();
    				break;

				case DistributionErrorType::MISSING_ASSET:
					$nObj = new BorhanDistributionValidationErrorMissingAsset();
					break;
    			
				case DistributionErrorType::INVALID_DATA:
					if($obj->getMetadataProfileId())
    					$nObj = new BorhanDistributionValidationErrorInvalidMetadata();
    				else
    					$nObj = new BorhanDistributionValidationErrorInvalidData();
    				break;

    				case DistributionErrorType::CONDITION_NOT_MET:
    					$nObj = new BorhanDistributionValidationErrorConditionNotMet();
    					break;

				default:
					break;
			}
			
			if(!$nObj)
				continue;
				
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
		
	public function __construct()
	{
		parent::__construct("BorhanDistributionValidationError");	
	}
}