<?php
/**
 * Interface which allows plugin to add its own content to the playManifest action output.
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanPlayManifestContributor extends IBorhanBase
{
	/**
	 * Function receives the manifest renderer and edits its output as it requires.
	 * @param kManifestEditorConfig $config
	 * @returns array<BaseManifestEditor>
	 */
	public static function getManifestEditors ($config);
}