<?php
namespace afterlogic\DAV\Auth;

class Plugin extends \Sabre\DAV\Auth\Plugin {

    protected $oAccount = null;
	
	public function getCurrentAccount() {

		return $this->oAccount;
		
    }	

	public function setCurrentAccount($oAccount) {

        $this->oAccount = $oAccount;
		$this->authBackend->setCurrentUser($oAccount->Email);

    }	
}