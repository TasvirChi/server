<?php
/**
 * @package plugins.varConsole
 * @subpackage api.types
 */
class BorhanPartnerUsageListResponse extends BorhanListResponse
{
    /**
     * @var BorhanVarPartnerUsageItem
     */
    public $total;
    /**
     * @var BorhanVarPartnerUsageArray
     */
    public $objects;
}