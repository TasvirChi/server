<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanMixEntry extends BorhanPlayableEntry
{
	/**
	 * Indicates whether the user has submited a real thumbnail to the mix (Not the one that was generated automaticaly)
	 * 
	 * @var bool
	 * @readonly
	 */
	public $hasRealThumbnail;
	
	/**
	 * The editor type used to edit the metadata
	 * 
	 * @var BorhanEditorType
	 */
	public $editorType;

	/**
	 * The xml data of the mix
	 *
	 * @var string
	 */
	public $dataContent;
	
	public function __construct()
	{
		$this->type = BorhanEntryType::MIX;
	}
	
	private static $map_between_objects = array
	(
		"hasRealThumbnail" => "hasRealThumb",
		"editorType",
		"dataContent"
	);
	
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
    public function doFromObject($entry, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($entry, $responseProfile);

		if($this->shouldGet('editorType', $responseProfile))
		{
			if ($entry->getEditorType() == "borhanAdvancedEditor" || $entry->getEditorType() == "Keditor")
			    $this->editorType = BorhanEditorType::ADVANCED;
			else
			    $this->editorType = BorhanEditorType::SIMPLE;
		}
	}
	
	public function toObject($entry = null, $skip = array())
	{
		$entry = parent::toObject($entry, $skip);
		
		if ($this->editorType === BorhanEditorType::ADVANCED)
			$entry->setEditorType("borhanAdvancedEditor");
		else
			$entry->setEditorType("borhanSimpleEditor");
			
		return $entry;
	}
}
?>