<?php
/**
 * @package    Core
 * @subpackage BMC
 */

/**
 * @package    Core
 * @subpackage BMC
 */
class supportAction extends borhanAction
{
    const SUPPORT_EMAIL_TYPE_ID = 210;
    
    public function execute ( ) 
    {
        // Prevent the page fron being embeded in an iframe
        header( 'X-Frame-Options: SAMEORIGIN' );
        
        $this->sent_request = false;

        if(isset($_GET['style']) && $_GET['style'] == 'v') {    // bmc virgo
            $this->closeFunction = 'parent.bmcCloseModal()';
            $this->bodyBgColor = 'E1E1E1';
        }
        else {
            $this->closeFunction = 'parent.bmc.utils.closeModal()';
            $this->bodyBgColor = 'F8F8F8';
        }

        if(isset($_POST['partner_id']))
        {
            // do mail
            $mail_body = array();
            foreach($_POST as $key => $val)
            {
                $mail_body[] = $key.': '.$val;
            }
            $strMailBody = implode('<BR><BR>', $mail_body);
            
            $body_params = array($strMailBody);
            $subject_params = array($_POST['subject']);
            kJobsManager::addMailJob(
                    null, 
                    0, 
                    $_POST['partner_id'],
                    self::SUPPORT_EMAIL_TYPE_ID, 
                    kMailJobData::MAIL_PRIORITY_NORMAL,
                    $_POST['email'], 
                    $_POST['your_name'].' ',
                    'borhansupport@borhan.com',
                    $body_params,
                    $subject_params);

            // Send support ticket to salesforce
            $post_items = array();
            foreach ($_POST as $key => $value) {
                $post_items[] = $key . '=' . urlencode($value);
            }
            $post_string = implode ('&', $post_items);
            $ch = curl_init("https://www.salesforce.com/servlet/servlet.WebToCase?encoding=UTF-8");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            $this->curlResult = curl_exec($ch);
            curl_close($ch); 

            $this->sent_request = true;
        }
        sfView::SUCCESS;
    }
}