<?php
/**
 * Subclass for representing a row from the 'comment' table.
 *
 * 
 *
 * @package Core
 * @subpackage model
 */ 
class comment extends Basecomment
{
	// We're using the same table to store comments of different types, use this integer constant to differentiate
	const COMMENT_TYPE_KSHOW = 1;
	const COMMENT_TYPE_DISCUSSION = 2;
	const COMMENT_TYPE_USER = 3;
	const COMMENT_TYPE_SHOUTOUT = 4;

	public function save(PropelPDO $con = null)
	{
		if ( $this->isNew() )
		{
			myStatisticsMgr::addComment( $this );
		}
		
		parent::save( $con );
	}	
		
	public function getFormattedCreatedAt( $format = dateUtils::BORHAN_FORMAT )
	{
		return dateUtils::formatBorhanDate( $this , 'getCreatedAt' , $format );
	}

	public function getFormattedUpdatedAt( $format = dateUtils::BORHAN_FORMAT )
	{
		return dateUtils::formatBorhanDate( $this , 'getUpdatedAt' , $format );
	}

		
}
