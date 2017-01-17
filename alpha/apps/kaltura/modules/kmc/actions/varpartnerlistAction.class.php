<?php
/**
 * Test page for adding JW player to BMC
 * 
 * @package    Core
 * @subpackage BMC
 */
class varpartnerlistAction extends borhanAction
{
	public function execute ( ) 
	{
		$email = @$_GET['email'];
		$screenName = @$_GET['screen_name'];
		$partner_id = $this->getP('partner_id', null);
		if($partner_id === null)
		{
			header("Location: /index.php/bmc/varlogin");
			die;
		}
		
		sfView::SUCCESS;
		
		$this->me = PartnerPeer::retrieveByPK($this->getP('partner_id', null));
		if(!$this->me || $this->me->getPartnerGroupType() != PartnerGroupType::VAR_GROUP)
		{
			die('You are not an wuthorized VAR. If you are a VAR, Please contact us at support@borhan.com');
		}
		
		$ks = kSessionUtils::crackKs($this->getP('ks'));
		$user = $ks->user;
		$res = kSessionUtils::validateKSession2(kSessionUtils::REQUIED_TICKET_ADMIN, $partner_id, $user, $this->getP('ks'), $ks);
		if($res != ks::OK)
		{
			header("Location: /index.php/bmc/varlogin");
			die;
		}
		
		$c = new Criteria;
		$c->addAnd(PartnerPeer::PARTNER_PARENT_ID, $this->me->getId());
		// add extra filtering if required
		//$c->addAnd(PartnerPeer::STATUS, 1);
		$partners = PartnerPeer::doSelect($c);
		$this->partners = array();
		$partner_id_param_name = 'pid';
		$subpid_param_name = 'subpid';
		if($this->me->getBmcVersion() == 1)
		{
			$partner_id_param_name = 'partner_id';
			$subpid_param_name = 'subp_id';
		}
		$bmc2Query = '?'.$partner_id_param_name.'='.$this->me->getId().'&'.$subpid_param_name.'='.($this->me->getId()*100).'&ks='.$_GET['ks'].'&email='.$email.'&screen_name='.$screenName;
		$this->varBmcUrl = 'http://'.kConf::get('www_host').'/index.php/bmc/bmc'.$this->me->getBmcVersion().$bmc2Query;
		foreach($partners as $partner)
		{
			$ks = null;
			kSessionUtils::createKSessionNoValidations ( $partner->getId() ,  $partner->getAdminUserId() , $ks , 30 * 86400 , 2 , "" , "*" );
			$adminUser_email = $partner->getAdminEmail();
			$partner_id_param_name = 'pid';
			$subpid_param_name = 'subpid';
			if($partner->getBmcVersion() == 1)
			{
				$partner_id_param_name = 'partner_id';
				$subpid_param_name = 'subp_id';
			}
			$bmc2Query = '?'.$partner_id_param_name.'='.$partner->getId().'&'.$subpid_param_name.'='.($partner->getId()*100).'&ks='.$ks.'&email='.$adminUser_email.'&screen_name=varAdmin';
			//$bmcLink = url_for('index.php/bmc/bmc2'.$bmc2Query);
//			$bmcLink = 'http://'.kConf::get('www_host').'/index.php/bmc/bmc'.$partner->getBmcVersion().$bmc2Query;
			$bmcLink = 'http://'.kConf::get('www_host')."/index.php/bmc/extlogin?ks=$ks&partner_id=" . $partner->getId();
			$this->partners[$partner->getId()] = array(
				'name' => $partner->getPartnerName(),
				'bmcLink' => $bmcLink,
			);
		}
	}
}
