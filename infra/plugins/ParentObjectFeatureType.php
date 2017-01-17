<?php
/**
 * @package infra
 * @subpackage Plugins
 */
class ParentObjectFeatureType implements IBorhanPluginEnum, ObjectFeatureType
{
    const PARENT = 'Parent';

    /* (non-PHPdoc)
     * @see IBorhanPluginEnum::getAdditionalValues()
     */
    public static function getAdditionalValues()
    {
        return array
        (
            'PARENT' => self::PARENT,
        );

    }

    /* (non-PHPdoc)
     * @see IBorhanPluginEnum::getAdditionalDescriptions()
     */
    public static function getAdditionalDescriptions() {
        return array();

    }
}