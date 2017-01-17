<?php
/**
 * @package plugins.metadata
 * @subpackage model.enum
 */
interface MetadataProfileCreateMode extends BaseEnum
{
	const API = 1;
	const BMC = 2;
	const APP = 3;
}