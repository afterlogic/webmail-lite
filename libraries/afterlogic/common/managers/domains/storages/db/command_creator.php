<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
	 * @param int $iTenantId
	 * @return string
	 */
	public function SetGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId)
	{
		$sSql = 'UPDATE %sawm_domains SET global_addr_book = %d WHERE id_tenant = %d';
		return sprintf($sSql, $this->Prefix(), $iVisibility, $iTenantId);
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
	 * @param int $iTenantId
	 * @param bool $bEnable
	 * @return string
	 */
	public function EnableOrDisableDomainsByTenantId($iTenantId, $bEnable)
	{
		$sSql = 'UPDATE %sawm_domains SET disabled = %d WHERE id_tenant = %d';

		return sprintf($sSql, $this->Prefix(), !$bEnable, $iTenantId);
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
	 * @param int $iTenantId = 0
	 *
	 * @return string
	 */
	public function GetDomainCount($sSearchDesc = '', $iTenantId = 0)
	{
		$sWhere = '';

		if (0 < $iTenantId)
		{
			$sWhere = empty($sWhere) ? ' WHERE ': $sWhere.' AND ';
			$sWhere .= 'id_tenant = '.((int) $iTenantId);
		}

		if (!empty($sSearchDesc))
		{
			$sSearchDescEsc = '\'%'.$this->escapeString(strtolower($sSearchDesc), true, true).'%\'';

			$sWhere = empty($sWhere) ? ' WHERE ': $sWhere.' AND ';
			$sWhere .= 'name LIKE '.$sSearchDescEsc;
		}

		$sSql = 'SELECT COUNT(id_domain) as domains_count FROM %sawm_domains%s';

		return sprintf($sSql, $this->Prefix(), $sWhere);
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return string
	 */
	public function GetDomainIdsByTenantId($iTenantId)
	{
		$sSql = 'SELECT id_domain FROM %sawm_domains WHERE id_tenant = %d';

		return sprintf($sSql, $this->Prefix(), $iTenantId);
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
	 * @param int $iTenantId
	 * @return string
	 */
	public function GetDefaultDomainByTenantId($iTenantId)
	{
		return $this->getDomainByWhere(sprintf('%s = %d AND %s = %d', $this->escapeColumn('id_tenant'), $iTenantId, $this->escapeColumn('is_default_for_tenant'), 1));
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
	 * @param int $iTenantId = 0
	 *
	 * @return string
	 */
	public function GetDomainsList($iPage, $iDomainsPerPage, $sOrderBy = 'name', $bOrderType = true, $sSearchDesc = '', $iTenantId = 0)
	{
		$sWhere = '';

		if (0 < $iTenantId)
		{
			$sWhere = empty($sWhere) ? ' WHERE ': $sWhere.' AND ';
			$sWhere .= 'id_tenant = '.((int) $iTenantId);
		}

		if (!empty($sSearchDesc))
		{
			$sSearchDescEsc = '\'%'.$this->escapeString(strtolower($sSearchDesc), true, true).'%\'';

			$sWhere = empty($sWhere) ? ' WHERE ': $sWhere.' AND ';
			$sWhere .= 'name LIKE '.$sSearchDescEsc;
		}

		$sOrderBy = empty($sOrderBy) ? 'name' : $sOrderBy;

		$sSql = 'SELECT id_domain, is_internal, name FROM %sawm_domains %s ORDER BY %s %s LIMIT %d OFFSET %d';

		$sSql = sprintf($sSql, $this->Prefix(), $sWhere, $sOrderBy,
			((bool) $bOrderType) ? 'ASC' : 'DESC',
			$iDomainsPerPage,
			($iPage > 0) ? ($iPage - 1) * $iDomainsPerPage : 0
		);

		return $sSql;
	}
}

class CApiDomainsCommandCreatorPostgreSQL  extends CApiDomainsCommandCreatorMySQL
{
	// TODO
}
