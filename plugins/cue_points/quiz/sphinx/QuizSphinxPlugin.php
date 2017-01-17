<?php
/**
 * Enable indexing and searching answers cue point objects in sphinx
 * @package plugins.cuePoint
 */
class QuizSphinxPlugin extends BorhanPlugin implements IBorhanCriteriaFactory, IBorhanPending
{
	const PLUGIN_NAME = 'quizSphinx';
	
	/* (non-PHPdoc)
	 * @see IBorhanPlugin::getPluginName()
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanPending::dependsOn()
	 */
	public static function dependsOn()
	{
	    $cuePointDependency = new BorhanDependency(CuePointPlugin::getPluginName());
	    $quizDependency = new BorhanDependency(QuizPlugin::getPluginName());
	
	    return array($cuePointDependency , $quizDependency);
	}	
	
	/* (non-PHPdoc)
	 * @see IBorhanCriteriaFactory::getBorhanCriteria()
	 */
	public static function getBorhanCriteria($objectType)
	{
		if ($objectType == 'AnswerCuePoint')
			return new SphinxAnswerCuePointCriteria();
			
		return null;
	}
}
