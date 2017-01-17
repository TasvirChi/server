<?php

class DeliveryProfileLivePackagerHds extends DeliveryProfileLiveHds {
	
	protected function getHttpUrl($serverNode)
	{
		$httpUrl = $this->getLivePackagerUrl($serverNode);
		$httpUrl .= "manifest.f4m";
		
		BorhanLog::debug("Live Stream url [$httpUrl]");
		return $httpUrl;
	}
}

