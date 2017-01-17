<?php

$config = null;
$client = null;
/* @var $client BorhanClient */

require_once __DIR__ . '/lib/init.php';

$partner = new BorhanPartner();
$partner->name = 'sanity-test';
$partner->website = 'sanity.example.com';
$partner->adminName = 'sanity-test';
$partner->adminEmail = uniqid('sanity.') . '@example.com';
$partner->description = 'sanity-test';
$cmsPassword = uniqid('pW@4');
$registeredPartner = $client->partner->register($partner, $cmsPassword);
/* @var $registeredPartner BorhanPartner */

if(!$registeredPartner || !$registeredPartner->id)
{
	echo "No partner created\n";
	exit(-1);
}

$config['session']['partnerId'] = $registeredPartner->id;
$config['session']['secret'] = $registeredPartner->secret;
$config['session']['adminSecret'] = $registeredPartner->adminSecret;

write_ini_file($config);

$partnerSession = $client->user->loginByLoginId($registeredPartner->adminEmail, $cmsPassword, $registeredPartner->id, 86400, 'disableentitlement');
$client->setKs($partnerSession);
$user = $client->user->getByLoginId($registeredPartner->adminEmail);
/* @var $user BorhanUser */
if(!$user || !$user->id)
{
	echo "Unable to login\n";
	exit(-1);
}

exit(0);