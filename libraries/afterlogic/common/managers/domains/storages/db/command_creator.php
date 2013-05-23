<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Domains
 */
class CApiDomainsCommandCreator extends api_CommandCreator
{
	/**
	 * @param CDomain $oDomain
	 * @return string
	 */
	public function UpdateDomain(CDomain $oDomain)
	{
		$aResult = api_AContainer::DbUpdateArray($oDomain, $this->oHelper);

		$sSql = 'UPDATE %sawm_domains SET %s WHERE id_domain = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult), $oDomain->IdDomain);
	}

	/**
	 * @param int $iVisibility
	 * @param int $iRealmId
	 * @return string
	 */
	public function SetGlobalAddressBookVisibilityByRealmId($iVisibility, $iRealmId)
	{
		$sSql = 'UPDATE %sawm_domains SET global_addr_book = %d WHERE id_realm = %d';
		return sprintf($sSql, $this->Prefix(), $iVisibility, $iRealmId);
	}

	/**
	 * @param CDomain $oDomain
	 * @return string
	 */
	public function CreateDomain(CDomain $oDomain)
	{
		$aResults = api_AContainer::DbInsertArrays($oDomain, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_domains ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param array $aDomainsIds
	 * @return string
	 */
	public function AreDomainsEmpty($aDomainsIds)
	{
		$sSql = 'SELECT COUNT(id_acct) as users_count FROM %sawm_accounts WHERE def_acct = 1 AND id_domain IN (%d)';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aDomainsIds));
	}

	/**
	 * @param array $aDomainsIds
	 * @param bool $bEnable
	 * @return string
	 */
	public function EnableOrDisableDomains($aDomainsIds, $bEnable)
	{
		$sSql = 'UPDATE %sawm_domains SET disabled = %d WHERE id_domain in (%s)';

		return sprintf($sSql, $this->Prefix(), !$bEnable, implode(', ', $aDomainsIds));
	}

	/**
	 * @param int $iRealmId
	 * @param bool $bEnable
	 * @return string
	 */
	public function EnableOrDisableDomainsByRealmId($iRealmId, $bEnable)
	{
		$sSql = 'UPDATE %sawm_domains SET disabled = %d WHERE id_realm = %d';

		return sprintf($sSql, $this->Prefix(), !$bEnable, $iRealmId);
	}

	/**
	 * @param array $aDomainsIds
	 * @return string
	 */
	public function DeleteDomains($aDomainsIds)
	{
		$sSql = 'DELETE FROM %sawm_domains WHERE id_domain in (%s)';

		return sprintf($sSql, $this->Prefix(), implode(', ', $aDomainsIds));
	}

	/**
	 * @param int $iDomainId
	 * @return string
	 */
	public function DeleteDomain($iDomainId)
	{
		$sSql = 'DELETE FROM %sawm_domains WHERE id_domain = %d';

		return sprintf($sSql, $this->Prefix(), $iDomainId);
	}

	/**
	 * @param string $sSearchDesc = ''
	 * @param int $iRealmId = 0
	 *
	 * @return string
	 */
	public function GetDomainCount($sSearchDesc = '', $iRealmId = 0)
	{
		$sWhere = '';

		if (0 < $iRealmId)
		{
			$sWhere = empty($sWhere) ? ' WHERE ': $sWhere.' AND ';
			$sWhere .= 'id_realm = '.((int) $iRealmId);
		}

		if (!empty($sSearchDesc))
		{
			$sWhere = empty($sWhere) ? ' WHERE ': $sWhere.' AND ';
			$sWhere .= 'name LIKE '.$this->escapeString('%'.strtolower($sSearchDesc).'%');
		}

		$sSql = 'SELECT COUNT(id_domain) as domains_count FROM %sawm_domains%s';

		return sprintf($sSql, $this->Prefix(), $sWhere);
	}

	/**
	 * @param int $iRealmId
	 *
	 * @return string
	 */
	public function GetDomainIdsByRealmId($iRealmId)
	{
		$sSql = 'SELECT id_domain FROM %sawm_domains WHERE id_realm = %d';

		return sprintf($sSql, $this->Prefix(), $iRealmId);
	}

	/**
	 * @param string $sDomainName
	 * @return string
	 */
	public function DomainExists($sDomainName)
	{
		$sSql = 'SELECT COUNT(id_domain) as domains_count FROM %sawm_domains WHERE %s = %s';

		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('name'), $this->escapeString(strtolower($sDomainName)));
	}

	/**
	 * @param int $iDomainId
	 * @return string
	 */
	public function GetDomainById($iDomainId)
	{
		return $this->getDomainByWhere(sprintf('%s = %d', $this->escapeColumn('id_domain'), $iDomainId));
	}

	/**
	 * @param string $sDomainName
	 * @return string
	 */
	public function GetDomainByName($sDomainName)
	{
		return $this->getDomainByWhere(sprintf('%s = %s',
			$this->escapeColumn('name'), $this->escapeString(strtolower((string) $sDomainName))));
	}

	/**
	 * @param string $sDomainUrl
	 * @return string
	 */
	public function GetDomainByUrl($sDomainUrl)
	{
		return $this->getDomainByWhere(sprintf('%s = %s',
			$this->escapeColumn('url'), $this->escapeString(strtolower((string) $sDomainUrl))));
	}

	/**
	 * @param string $sName
	 * @param mixed $mValue
	 * @return string
	 */
	protected function getDomainByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CDomain::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_domains WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $sWhere);
	}
}

/**
 * @package Domains
 */
class CApiDomainsCommandCreatorMySQL extends CApiDomainsCommandCreator
{
	/**
	 * @param int $iPage
	 * @param int $iDomainsPerPage
	 * @param string $sOrderBy = 'name'
	 * @param bool $bOrderType = true
	 * @param string $sSearchDesc = ''
	 * @param int $iRealmId = 0
	 *
	 * @return string
	 */
	public function GetDomainsList($iPage, $iDomainsPerPage, $sOrderBy = 'name', $bOrderType = true, $sSearchDesc = '', $iRealmId = 0)
	{
		$sWhere = '';

		if (0 < $iRealmId)
		{
			$sWhere = empty($sWhere) ? ' WHERE ': $sWhere.' AND ';
			$sWhere .= 'id_realm = '.((int) $iRealmId);
		}

		if (!empty($sSearchDesc))
		{
			$sWhere = empty($sWhere) ? ' WHERE ': $sWhere.' AND ';
			$sWhere .= 'name LIKE '.$this->escapeString('%'.$sSearchDesc.'%');
		}

		$sOrderBy = empty($sOrderBy) ? 'name' : $sOrderBy;

		$sSql = 'SELECT id_domain, is_internal, name FROM %sawm_domains %s ORDER BY %s %s LIMIT %d, %d';

		$sSql = sprintf($sSql, $this->Prefix(), $sWhere, $sOrderBy,
			((bool) $bOrderType) ? 'ASC' : 'DESC', ($iPage > 0) ? ($iPage - 1) * $iDomainsPerPage : 0,
			$iDomainsPerPage);

		return $sSql;
	}
}
