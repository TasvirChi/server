<?php
/**
 * @package api
 * @subpackage objects
 */
abstract class BorhanContext extends BorhanObject
{
    /**
     * Function to validate the context.
     */
    abstract protected function validate ();
}