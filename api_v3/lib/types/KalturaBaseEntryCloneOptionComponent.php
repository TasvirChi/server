<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanBaseEntryCloneOptionComponent extends BorhanBaseEntryCloneOptionItem
{
    /**
     *
     * @var BorhanBaseEntryCloneOptions
     */
    public $itemType;

    /**
     * condition rule (include/exclude)
     *
     * @var BorhanCloneComponentSelectorType
     */
    public $rule;



    private static $mapBetweenObjects = array
    (
        'itemType',
        'rule',
    );

    /**
     * @return array
     */
    public function getMapBetweenObjects()
    {
        return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
    }

    public function toObject($dbObject = null, $skip = array())
    {
        if(!$dbObject)
            $dbObject = new kBaseEntryCloneOptionComponent();

        return parent::toObject($dbObject, $skip);
    }

    /* (non-PHPdoc)
 * @see BorhanObject::fromObject()
 */
    public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
    {
        /** @var $dbObject kBaseEntryCloneOptionComponent */
        parent::doFromObject($dbObject, $responseProfile);
    }
    public function __construct()
    {
    }



}