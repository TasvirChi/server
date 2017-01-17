<?php
/**
 * Client notification object to hold the notification url and the data when sending client side notifications
 * 
 * @package api
 * @subpackage objects
 */
class BorhanClientNotification extends BorhanObject 
{
    /**
     * The URL where the notification should be sent to 
     *
     * @var string
     */
    public $url;
    
    
    /**
     * The serialized notification data to send
     *
     * @var string
     */
    public $data;
}