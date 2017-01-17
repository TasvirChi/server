<?php
/**
 * @package api
 * @subpackage v3
 */
class BorhanServiceActionItem
{
    /**
     * @var string
     */
    public $serviceId;
    
    /**
     * @var string
     */
    public $serviceClass;
    
    /**
     * @var BorhanDocCommentParser
     */
    public $serviceInfo;
    
    /**
     * @var array
     */
    public $actionMap;
    
    public static function cloneItem (BorhanServiceActionItem $item)
    {
        $serviceActionItem = new BorhanServiceActionItem();
        $serviceActionItem->serviceId = $item->serviceId;
        $serviceActionItem->serviceClass = $item->serviceClass;
        $serviceActionItem->serviceInfo = $item->serviceInfo;
        $serviceActionItem->actionMap = $item->actionMap;
        return $serviceActionItem;
    }

}
