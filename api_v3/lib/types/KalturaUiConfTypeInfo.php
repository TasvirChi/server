<?php
/**
 * Info about uiconf type
 * 
 * @see BorhanStringArray
 * @package api
 * @subpackage objects
 */
class BorhanUiConfTypeInfo extends BorhanObject
{
	/**
	 * UiConf Type
	 * 
	 * @var BorhanUiConfObjType
	 */
    public $type;
    
    /**
     * Available versions
     *  
     * @var BorhanStringArray
     */
    public $versions;
    
    /**
     * The direcotry this type is saved at
     * 
     * @var string
     */
    public $directory;
    
    /**
     * Filename for this UiConf type
     * 
     * @var string
     */
    public $filename;
    
	private static $mapBetweenObjects = array
	(
		"type",
		"versions",
		"directory",
		"filename",
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
}