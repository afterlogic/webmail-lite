<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Social
 */
class CApiSocialCommandCreator extends api_CommandCreator
{
	/**
	 * @param CSocial $oSocial
	 * @return string
	 */
	public function CreateSocial(CSocial $oSocial)
	{
		$aResults = api_AContainer::DbInsertArrays($oSocial, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_social ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param CSocial $oSocial
	 * @return string
	 */
	public function UpdateSocial(CSocial $oSocial)
	{
		$aResult = api_AContainer::DbUpdateArray($oSocial, $this->oHelper);

		$sSql = 'UPDATE %sawm_social SET %s WHERE type_str = %s AND id_social = %s';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult), $this->escapeString(strtolower($oSocial->TypeStr)), $oSocial->IdSocial);
	}

	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 * @return bool
	 */
	public function DeleteSocial($iIdAccount, $sType)
	{
		$sSql = 'DELETE FROM %sawm_social WHERE id_acct = %d AND type_str = %s ';

		return sprintf($sSql, $this->Prefix(), $iIdAccount, $this->escapeString(strtolower($sType)));
	}

	/**
	 * @param int $iIdAccount
	 * @return bool
	 */
	public function DeleteSocialByAccountId($iIdAccount)
	{
		$sSql = 'DELETE FROM %sawm_social WHERE id_acct = %d';

		return sprintf($sSql, $this->Prefix(), $iIdAccount);
	}
	/**
	 * @param string $sType
	 * @param string $sIdSocial
	 * @return string
	 */
	public function SocialExists($sType, $sIdSocial)
	{
		$sSql = 'SELECT COUNT(id) as social_count FROM %sawm_social WHERE %s = %s AND %s = %s';

		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('type_str'), $this->escapeString(strtolower($sType)),
			$this->escapeColumn('id_social'), $this->escapeString(strtolower($sIdSocial))
		);
	}

	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 * @return string
	 */
	public function GetSocial($iIdAccount, $sType)
	{
		return $this->getSocialByWhere(sprintf('%s = %d AND %s = %s', 
				$this->escapeColumn('id_acct'), $iIdAccount,
				$this->escapeColumn('type_str'), $this->escapeString(strtolower($sType))
		));
	}
	
	/**
	 * @param string $sIdSocial
	 * @param string $sType
	 * @return string
	 */
	public function GetSocialById($sIdSocial, $sType)
	{
		return $this->getSocialByWhere(sprintf('%s = %s AND %s = %s', 
				$this->escapeColumn('id_social'), $sIdSocial,
				$this->escapeColumn('type_str'), $this->escapeString(strtolower($sType))
		));
	}	
	
	/**
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return string
	 */
	public function GetSocials($iIdAccount)
	{
		return $this->getSocialByWhere(sprintf('%s = %d', 
				$this->escapeColumn('id_acct'), $iIdAccount
		));
	}

	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function getSocialByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CSocial::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_social WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $sWhere);
	}	
}

/**
 * @package Social
 */
class CApiSocialCommandCreatorMySQL extends CApiSocialCommandCreator
{
	// TODO
}

class CApiSocialCommandCreatorPostgreSQL  extends CApiSocialCommandCreatorMySQL
{
	// TODO
}
