<?php

abstract class WSBaseObject extends SoapObject {
	
	abstract function getBorhanObject();
	
	public function toBorhanObject() {
		$borhanObj = $this->getBorhanObject();
		self::cloneObject($this, $borhanObj);
		return $borhanObj;
	}
	
	public function fromBorhanObject($borhanObj) {
		self::cloneObject($borhanObj, $this);
	}
	
	protected static function cloneObject($objA, $objB) {
		$reflect = new ReflectionClass($objA);
		foreach($reflect->getProperties(ReflectionProperty::IS_PUBLIC) as $prop)
		{
			$name = $prop->getName();
			$value = $prop->getValue($objA);
			
			if ($value instanceof WSBaseObject) {
				$value = $value->toBorhanObject();
			} else if($value instanceof SoapArray) {
				/**
				 * @var SoapArray $value
				 */
				$arr = $value->toArray();
				$newObj = array();
				foreach($arr as $val) {
					if ($val instanceof WSBaseObject) {
						$newObj[] = $val->toBorhanObject();
					} else {
						$newObj[] = $val;
					}
				} 
				$value = $newObj;
			}
			
			$objB->$name = $value; 
		}
	}
}

