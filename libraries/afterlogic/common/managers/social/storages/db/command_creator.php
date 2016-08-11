<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Social
 * @subpackage Storages
 */
class CApiSocialCommandCreator extends api_CommandCreator
{
	/**
	 * @param CSocial $oSocial
	 *
	 * @return string
	 */
	public function createSocial(CSocial $oSocial)
	{
		$aResults = api_AContainer::DbInsertArrays($oSocial, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_social ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param CSocial $oSocial
	 *
	 * @return string
	 */
	public function updateSocial(CSocial $oSocial)
	{
		$aResult = api_AContainer::DbUpdateArray($oSocial, $this->oHelper);

		$sSql = 'UPDATE %sawm_social SET %s WHERE type_str = %s AND id_acct = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult), $this->escapeString(strtolower($oSocial->TypeStr)), $oSocial->IdAccount);
	}

	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 *
	 * @return bool
	 */
	public function deleteSocial($iIdAccount, $sType)
	{
		$sSql = 'DELETE FROM %sawm_social WHERE id_acct = %d AND type_str = %s ';

		return sprintf($sSql, $this->prefix(), $iIdAccount, $this->escapeString(strtolower($sType)));
	}

	/**
	 * @param int $iIdAccount
	 *
	 * @return bool
	 */
	public function deleteSocialByAccountId($iIdAccount)
	{
		$sSql = 'DELETE FROM %sawm_social WHERE id_acct = %d';

		return sprintf($sSql, $this->prefix(), $iIdAccount);
	}
	
	/**
	 * @param string $sEmail
	 *
	 * @return bool
	 */
	public function deleteSocialsByEmail($sEmail)
	{
		$sSql = 'DELETE FROM %sawm_social WHERE email = %s';

		return sprintf($sSql, $this->prefix(), $this->escapeString($sEmail));
	}	

	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 *
	 * @return string
	 */
	public function isSocialExists($iIdAccount, $sType)
	{
		$sSql = 'SELECT COUNT(id) as social_count FROM %sawm_social WHERE %s = %s AND %s = %d';

		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('type_str'), $this->escapeString(strtolower($sType)),
			$this->escapeColumn('id_acct'), $iIdAccount);
	}

	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 *
	 * @return string
	 */
	public function getSocial($iIdAccount, $sType)
	{
		return $this->getSocialByWhere(sprintf('%s = %d AND %s = %s', 
				$this->escapeColumn('id_acct'), $iIdAccount,
				$this->escapeColumn('type_str'), $this->escapeString(strtolower($sType))
		));
	}
	
	/**
	 * @param string $sIdSocial
	 * @param string $sType
	 *
	 * @return string
	 */
	public function getSocialById($sIdSocial, $sType)
	{
		return $this->getSocialByWhere(sprintf('%s = %s AND %s = %s', 
				$this->escapeColumn('id_social'), $sIdSocial,
				$this->escapeColumn('type_str'), $this->escapeString(strtolower($sType))
		));
	}	
	
	/**
	 * @param int $iIdAccount
	 *
	 * @return string
	 */
	public function getSocials($iIdAccount)
	{
		return $this->getSocialByWhere(sprintf('%s = %d', 
				$this->escapeColumn('id_acct'), $iIdAccount
		));
	}

	/**
	 * @param string $sWhere
	 *
	 * @return string
	 */
	protected function getSocialByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CSocial::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_social WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(), $sWhere);
	}	
}

/**
 * @package Social
 * @subpackage Storages
 */
class CApiSocialCommandCreatorMySQL extends CApiSocialCommandCreator
{
	// TODO
}

/**
 * @package Social
 * @subpackage Storages
 */
class CApiSocialCommandCreatorPostgreSQL  extends CApiSocialCommandCreatorMySQL
{
	// TODO
}
