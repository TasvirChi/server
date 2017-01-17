<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanEntryServerNodeArray extends BorhanTypedArray
{
	public static function fromDbArray($arr)
	{
		$newArr = new BorhanEntryServerNodeArray();
		foreach($arr as $obj)
		{
			/* @var $obj BorhanEntryServerNode */
			$nObj = BorhanEntryServerNode::getInstance($obj);
			if (!$nObj)
			{
				throw new BorhanAPIException(BorhanErrors::ENTRY_SERVER_NODE_OBJECT_TYPE_ERROR, $obj->getServerType(), $obj->getId());
			}
			$nObj->fromObject($obj);
			$newArr[] = $nObj;
		}

		return $newArr;
	}

	public function __construct()
	{
		return parent::__construct("BorhanEntryServerNode");
	}

}