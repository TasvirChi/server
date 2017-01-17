<?php
/**
 * @package deployment
 * @subpackage jupiter.roles_and_permissions
 */

$script = realpath(dirname(__FILE__) . '/../../../../') . '/alpha/scripts/utils/permissions/addPermissionsAndItems.php';
//$script = realpath(dirname(__FILE__) . '/../../../../') . '/alpha/scripts/utils/permissions/removePermissionsAndItems.php';

$config = realpath(dirname(__FILE__)) . '/../../../permissions/service.cuepoint.cuepoint.ini';
passthru("php $script $config");

$config = realpath(dirname(__FILE__)) . '/../../../permissions/object.BorhanCuePoint.ini';
passthru("php $script $config");

$config = realpath(dirname(__FILE__)) . '/../../../permissions/object.BorhanAdCuePoint.ini';
passthru("php $script $config");

$config = realpath(dirname(__FILE__)) . '/../../../permissions/object.BorhanAnswerCuePoint.ini';
passthru("php $script $config");

$config = realpath(dirname(__FILE__)) . '/../../../permissions/object.BorhanThumbCuePoint.ini';
passthru("php $script $config");

$config = realpath(dirname(__FILE__)) . '/../../../permissions/object.BorhanAnnotationCuePoint.ini';
passthru("php $script $config");

$config = realpath(dirname(__FILE__)) . '/../../../permissions/object.BorhanCodeCuePoint.ini';
passthru("php $script $config");

$config = realpath(dirname(__FILE__)) . '/../../../permissions/object.BorhanEventCuePoint.ini';
passthru("php $script $config");

$config = realpath(dirname(__FILE__)) . '/../../../permissions/object.BorhanQuestionCuePoint.ini';
passthru("php $script $config");