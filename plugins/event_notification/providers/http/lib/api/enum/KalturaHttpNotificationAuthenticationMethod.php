<?php
/**
 * @package plugins.httpNotification
 * @subpackage api.enum
 */
class BorhanHttpNotificationAuthenticationMethod extends BorhanEnum
{
	const BASIC = CURLAUTH_BASIC;
	const DIGEST = CURLAUTH_DIGEST;
	const GSSNEGOTIATE = CURLAUTH_GSSNEGOTIATE;
	const NTLM = CURLAUTH_NTLM;
	const ANY = CURLAUTH_ANY;
	const ANYSAFE = CURLAUTH_ANYSAFE;
}
