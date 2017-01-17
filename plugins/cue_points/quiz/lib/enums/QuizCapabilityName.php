<?php

/**
 * @package plugins.quiz
 * @subpackage lib.enum
 */
class QuizEntryCapability implements IBorhanPluginEnum, EntryCapability
{

	const QUIZ = 'quiz';

	/**
	 * @return array
	 */
	public static function getAdditionalValues()
	{
		return array(
			'BORHAN_QUIZ_CAPABILITY_NAME' => self::QUIZ
		);
	}

	/**
	 * @return array
	 */
	public static function getAdditionalDescriptions()
	{
		return array();
	}

}