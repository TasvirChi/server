<?php
class BorhanRequestParameterSerializer
{
	/**
	 * Flattens BorhanObject into an array of parameters that can sent over a GET request
	 * @param BorhanObject $object
	 * @param string $prefix
	 * @return array
	 */
	public static function serialize (BorhanObject $object, $prefix)
	{
		$params = array();
		if (!($object instanceof BorhanTypedArray))
			$params[] = "$prefix:objectType=".get_class($object);
		
		foreach ($object as $prop => $val)
		{
			if (is_null($val))
				continue;
			
			if (is_numeric($prop))
			{
				$prop = "item$prop";	
			}
			
			if (is_scalar($val))
			{
				$params[] = "$prefix:$prop=$val";
			}
			elseif ($val instanceof BorhanTypedArray)
			{
				$params = array_merge($params, self::serialize($val->toArray(),"$prefix:$prop"));				
			}
			else 
			{
				$params = array_merge($params, self::serialize($val,"$prefix:$prop"));
			}
		}
		
		return $params;
	}
}