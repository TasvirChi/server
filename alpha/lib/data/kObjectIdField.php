<?php
/**
 * Calculate value of an object ID based on a specific context.
 * 
 * @package Core
 * @subpackage model.data
 *
 */
class kObjectIdField extends kStringField
{
	/* (non-PHPdoc)
	 * @see kStringField::getFieldValue()
	 */
	protected function getFieldValue(kScope $scope = null)
	{
		if(!$scope)
		{
			BorhanLog::info('No scope specified');
			return null;
		}
		
		if (!($scope instanceof kEventScope))
		{
			BorhanLog::info('Scope must be of type kEventScope, [' . get_class($scope) . '] given');
			return;
		}
		
		if (!($scope->getEvent()))
		{
			BorhanLog::info('$scope->getEvent() must return a value');
			return;
		}
		
		if ($scope->getEvent() && !($scope->getEvent() instanceof  IBorhanObjectRelatedEvent))
		{
			BorhanLog::info('Scope event must realize interface IBorhanObjectRelatedEvent');
			return;
		}
		
		if ($scope->getEvent() && !($scope->getEvent()->getObject()))
		{
			BorhanLog::info('Object not found on scope event');
			return;
		}
		
		if (!method_exists($scope->getEvent()->getObject(), 'getId'))
		{
			BorhanLog::info('Getter method for object id not found');
			return;
		}
		
		return $scope->getEvent()->getObject()->getId();
	}

	
}