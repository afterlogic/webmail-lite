<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\Principal\Backend;

use afterlogic\DAV\Constants;

class PDOExt extends \Sabre\DAVACL\PrincipalBackend\PDO 
{
	/* @var $oApiUsersManager \CApiUsersManager */
	protected $oApiUsersManager;	
	
	
	/**
     * Sets up the backend.
     */
    public function __construct()
	{
		$oPdo = \CApi::GetPDO();
		$dbPrefix = \CApi::GetSettings()->GetConf('Common/DBPrefix');

		$this->oApiUsersManager = \CApi::Manager('users');
		parent::__construct($oPdo, $dbPrefix.Constants::T_PRINCIPALS, $dbPrefix.Constants::T_GROUPMEMBERS);
    } 

    /**
     * Returns a list of principals based on a prefix.
     *
     * @param string $prefixPath
     * @return array
     */
    public function getPrincipalsByPrefix($prefixPath) 
	{

        $principals = array();
		$aUsers = $this->oApiUsersManager->GetUserFullList();
		
        foreach($aUsers as $aUser) 
		{
            $principals[] = array(
				'id'  => $aUser->id_acct,
				'uri' => \afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $aUser->email,
				'{http://sabredav.org/ns}email-address' => $aUser->email,
				'{DAV:}displayname' => $aUser->friendly_nm
			);
        }

        return $principals;

    }

    /**
     * Returns a specific principal, specified by it's path.
     * The returned structure should be the exact same as from
     * getPrincipalsByPrefix.
     *
     * @param string $path
     * @return array
     */
    public function getPrincipalByPath($path) 
	{

		$oAccount = \afterlogic\DAV\Utils::GetAccountByLogin(basename($path));
        if ($oAccount)
		{
			return array(
				'id'  => $oAccount->IdAccount,
				'uri' => \afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $oAccount->Email,
				'{http://sabredav.org/ns}email-address' => $oAccount->Email,
				'{DAV:}displayname' => $oAccount->FriendlyName,
			);
		}
		else 
		{
			return;
		}
    }

    /**
     * Updates one ore more webdav properties on a principal.
     *
     * @param string $path
     * @param array $mutations
     * @return array|bool
     */
    public function updatePrincipal($path, $mutations) 
	{

        return true;

    }

    /**
     * This method is used to search for principals matching a set of
     * properties.
     *
     * @param string $prefixPath
     * @param array $searchProperties
     * @return array
     */
    public function searchPrincipals($prefixPath, array $searchProperties) 
	{

        $oAccount = null;
		foreach($searchProperties as $property => $value) {

            switch($property) {

                case '{http://sabredav.org/ns}email-address' :
					$oAccount = \afterlogic\DAV\Utils::GetAccountByLogin($value);
					break;
                default :
                    // Unsupported property
                    return array();

            }

        }
		
        $principals = array();
		if ($oAccount)
		{
			$principals[] = \afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $oAccount->Email;
		}

        return $principals;

    }

    /**
     * Returns the list of members for a group-principal
     *
     * @param string $principal
     * @return array
     */
    public function getGroupMemberSet($principal) 
	{

        return array();

    }

    /**
     * Returns the list of groups a principal is a member of
     *
     * @param string $principal
     * @return array
     */
    public function getGroupMembership($principal) 
	{
		
		return array();

    }

    /**
     * Updates the list of group members for a group principal.
     *
     * The principals should be passed as a list of uri's.
     *
     * @param string $principal
     * @param array $members
     * @return void
     */
    public function setGroupMemberSet($principal, array $members) 
	{

    }
	
    /**
     * @param string $uri 
     * @return bool
     */
	public function existsPrincipal($uri) 
	{
		
        $bResult = false;
		
		$oAccount = \afterlogic\DAV\Utils::GetAccountByLogin(basename($uri));
        if ($oAccount)
		{
			$bResult = true;
		}
	
		return $bResult;
	}
	
    /**
     * @param string $uri 
     * @param string $email 
     * @param string $displayname 
     * @return void
     */
	public function createPrincipal($uri, $email = '', $displayname = '') 
	{
    }
	
    /**
     * @param string $uri 
     * @return void
     */
	public function deletePrincipal($uri) 
	{
    } 	
	
    /**
     * @param string $sEmail 
     * @return string
     */
	public function getPrincipalByEmail($sEmail)
	{
	
		return $sEmail;
		
	}	

}
