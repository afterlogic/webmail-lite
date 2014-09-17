<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
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

		$sSql = 'UPDATE %sawm_social SET %s WHERE type = %d AND id_social = %s';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult), $oSocial->Type, $oSocial->IdSocial);
	}

	/**
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return bool
	 */
	public function DeleteSocial($iIdAccount, $iType)
	{
		$sSql = 'DELETE FROM %sawm_social WHERE id_acct = %d AND type = %d ';

		return sprintf($sSql, $this->Prefix(), $iIdAccount, $iType);
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
	 * @param int $iType
	 * @param string $sIdSocial
	 * @return string
	 */
	public function SocialExists($iType, $sIdSocial)
	{
		$sSql = 'SELECT COUNT(id) as social_count FROM %sawm_social WHERE %s = %d AND %s = %s';

		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('type'), $iType,
			$this->escapeColumn('id_social'), $this->escapeString(strtolower($sIdSocial))
		);
	}

	/**
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return string
	 */
	public function GetSocial($iIdAccount, $iType)
	{
		return $this->getSocialByWhere(sprintf('%s = %d AND %s = %d', 
				$this->escapeColumn('id_acct'), $iIdAccount,
				$this->escapeColumn('type'), $iType
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
