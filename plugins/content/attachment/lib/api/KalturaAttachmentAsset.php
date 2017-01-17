<?php
/**
 * @package plugins.attachment
 * @subpackage api.objects
 */
class BorhanAttachmentAsset extends BorhanAsset  
{
	/**
	 * The filename of the attachment asset content
	 * @var string
	 */
	public $filename;
	
	/**
	 * Attachment asset title
	 * @var string
	 */
	public $title;
	
	/**
	 * The attachment format
	 * @var BorhanAttachmentType
	 * @filter eq,in
	 */
	public $format;
	
	/**
	 * The status of the asset
	 * 
	 * @var BorhanAttachmentAssetStatus
	 * @readonly 
	 * @filter eq,in,notin
	 */
	public $status;
	
	private static $map_between_objects = array
	(
		"filename",
		"title",
		"format" => "containerFormat",
		"status",
	);
	
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	public function toObject($object_to_fill = null, $props_to_skip = array())
	{
		if (!$object_to_fill)
			$object_to_fill = new AttachmentAsset();
	
		return parent::toObject($object_to_fill, $props_to_skip);
	}
}
	