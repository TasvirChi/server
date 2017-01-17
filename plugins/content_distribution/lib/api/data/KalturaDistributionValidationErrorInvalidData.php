<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.objects
 */
class BorhanDistributionValidationErrorInvalidData extends BorhanDistributionValidationError
{
	/**
	 * @var string
	 */
	public $fieldName;
	
	/**
	 * @var BorhanDistributionValidationErrorType
	 */
	public $validationErrorType;
	
	/**
	 * Parameter of the validation error
	 * For example, minimum value for BorhanDistributionValidationErrorType::STRING_TOO_SHORT validation error
	 * @var string
	 */
	public $validationErrorParam;

	/*
	 * mapping between the field on this object (on the left) and the setter/getter on the object (on the right)  
	 */
	private static $map_between_objects = array 
	(
		'fieldName' => 'data',
		'validationErrorType',
		'validationErrorParam',
	);
		 
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
}