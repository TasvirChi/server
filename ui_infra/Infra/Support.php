<?php
/**
 * @package UI-infra
 * @subpackage Support
 */
class Infra_Support 
{
	public static function isEnabled()
	{
		$mantisConfig = Zend_Registry::get('config')->mantis;
		if(!$mantisConfig)
			return false;
			
		return $mantisConfig->enabled;
	}
	
	public static function isAdminEnabled()
	{
		$mantisConfig = Zend_Registry::get('config')->mantis;
		if(!$mantisConfig)
			return false;
			
		return $mantisConfig->adminEnabled;
	}
	
	public static function addIssue($summary, $description, $file_content = null, $customFields = array())
	{
		BorhanLog::debug("addIssue(summary = [$summary], description = [$description], file_content = [$file_content])");
		
		$version = null;
		$productConfig = Zend_Registry::get('config')->product;
		if($productConfig)
			$version = $productConfig->version;
		
		$mantisConfig = Zend_Registry::get('config')->mantis;
		if(!$mantisConfig)
			return false;
			
		$wsdlUrl = $mantisConfig->url;
		$username = $mantisConfig->username;
		$password = $mantisConfig->password;
		$category = $mantisConfig->category;
		$projectId = $mantisConfig->project;
		$email = $mantisConfig->email;

		$client = new MantisClient($wsdlUrl, $username, $password);

		$result = $client->getProjectCustomFields($projectId);
		if ($client->getError())
			return false;
		
		$issue = array();
		$issue["project"]["id"] = $projectId;
		$issue["category"] = $category;
		$issue["reproducibility"]["id"] = "100";
		$issue["severity"]["id"] = "10";
		$issue["priority"]["id"] = "10";
		$issue["summary"] = $summary;
		$issue["description"] = $description;
//		$issue["version"] = $version;

		$issue["status"] = array();
		$issue["resolution"] = array();
		$issue["projection"] = array();
		$issue["eta"] = array();
		$issue["view_state"] = array();
		
		$issue["custom_fields"] = array();
		foreach($customFields as $customFieldId => $customFieldValue) {
			$issue["custom_fields"][] = array(
				"field" => array("id" => $customFieldId),
				"value" => $customFieldValue
			);
		}
		
		$result = $client->addIssue($issue);
		if ($client->getError())
			return false;
		
		$issueId = $result;
		
		if ($file_content) 
		{
			$result = $client->addAttachmentToIssue($issueId, 'entry.ked', 'text/plain', base64_encode($file_content));
			$attchId = $result;
//			if ($client->getError())
//				return false;
		}
		
		if ($email) 
		{
			// send the mail
			$subject = "Borhan Support: Ticket #$issueId";
			$body = "Hello,<br />
				<br />
				Thank you for contacting Borhan.  Ticket #$issueId has been issued for your support inquiry.<br />  
				<br />
				Someone from our support team will review your message and get back to you as soon as possible. If you reply to this message, please include the string \"Ticket #$issueId\" in the subject line of all future correspondence about this issue.<br />
				<br />
				You can find additional information about Borhan's solutions in our Forums:  <a href=\"http://borhan.org/community/index.php\">http://borhan.org/community/index.php</a><br />
				<br />
				<br />
				Regards,<br />
				Borhan Support Team<br />
				<a href=\"mailto:support@borhan.com\">support@borhan.com</a><br />
			";
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Borhan Support <support@borhan.com>';
			
			try{
				$result = mail($email, $subject, $body, $headers);
			}
			catch (Exception $e){
				$result = null;
				BorhanLog::err("Failed to send mail to [".$email."]: " . $e->getMessage());
			}
			
			if ($result)
				BorhanLog::info("Mail was send successfully to [".$email."]");
			else
				BorhanLog::err("Failed to send mail to [".$email."]");
		}
		else
		{
			BorhanLog::err("Email was not specified!");
		}

		
		return $issueId;
	}
}