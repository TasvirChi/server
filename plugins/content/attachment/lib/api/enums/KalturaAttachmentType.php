<?php
/**
 * @package plugins.attachment
 * @subpackage api.enum
 */
class BorhanAttachmentType extends BorhanDynamicEnum implements AttachmentType
{
	public static function getEnumClass()
	{
		return 'AttachmentType';
	}
}