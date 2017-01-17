<?php
/**
 * Consumer to disable caching after an object is saved.
 *
 * @package api
 * @subpackage cache
 */
class BorhanCacheDisabler implements kObjectSavedEventConsumer
{
	public function objectSaved(BaseObject $object)
	{
		BorhanResponseCacher::disableCache();
	}
	
	public function shouldConsumeSavedEvent(BaseObject $object)
	{
		return BorhanResponseCacher::isCacheEnabled();
	}
}