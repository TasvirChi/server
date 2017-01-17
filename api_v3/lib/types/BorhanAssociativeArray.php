<?php
/**
 * @package api
 * @subpackage objects
 */
abstract class BorhanAssociativeArray extends BorhanTypedArray
{
	/* (non-PHPdoc)
	 * @see BorhanTypedArray::offsetSet()
	 */
	public function offsetSet($offset, $value) 
	{
		$this->validateType($value);
		
		if ($offset === null)
		{
			$this->array[] = $value;
		}
		else
		{
			$this->array[$offset] = $value;
		}
			
		$this->count = count ( $this->array );
	}
}