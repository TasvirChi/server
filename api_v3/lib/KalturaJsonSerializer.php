<?php
/**
 * @package api
 * @subpackage v3
 */
class BorhanJsonSerializer extends BorhanSerializer
{	
	public function setHttpHeaders()
	{
		header("Content-Type: application/json");
	}

	function serialize($object)
	{
		if(is_null($object))
			return 'null';
			
		$object = parent::prepareSerializedObject($object);
		$json = json_encode($this->unsetNull($object));
		return $json;
	}

	protected function unsetNull($object)
	{
		if(!is_array($object) && !is_object($object))
			return $object;
		
		$array = (array) $object;
		foreach($array as $key => $value)
		{
			if(is_null($value))
			{
				unset($array[$key]);
			}
			else
			{
				$array[$key] = $this->unsetNull($value);
			}
		}
		
		if(is_object($object) && $object instanceof BorhanObject)
		{
			$array['objectType'] = get_class($object);
		}
		
		return $array;
	}

	public function getItemFooter($lastItem = false)
	{
		if(!$lastItem)
			return ',';
		
		return '';
	}
	
	public function getMulitRequestHeader($itemsCount = null)
	{
		return '[';
	}
	
	public function getMulitRequestFooter()
	{
		return ']';
	}
}
