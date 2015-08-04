<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @internal
 * 
 * @package Domains
 * @subpackage Storages
 */
class CApiDomainsCommandCreator extends api_CommandCreator
{
	/**
	 * @param CDomain $oDomain
	 *
	 *
	 * @return string
	 */
	public function updateDomain(CDomain $oDomain)
	{
		$aResult = api_AContainer::DbUpdateArray($oDomain, $this->oHelper);

		$sSql = 'UPDATE %sawm_domains SET %s WHERE id_domain = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult), $oDomain->IdDomain);
	}

	/**
	 * @param int $iVisibility
	 * @param int $iTenantId
	 *
	 * @return string
	 */
	public function setGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId)
	{
		$sSql = 'UPDATE %sawm_domains SET global_addr_book = %d WHERE id_tenant = %d';
		return sprintf($sSql, $this->prefix(), $iVisibility, $iTenantId);
	}

	/**
	 * @param CDomain $oDomain
	 *
	 * @return string
	 */
	public function createDomain(CDomain $oDomain)
	{
		$aResults = api_AContainer::DbInsertArrays($oDomain, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_domains ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param array $aDomainsIds
	 *
	 * @return string
	 */
	public function areDomainsEmpty($aDomainsIds)
	{
		$sSql = 'SELECT COUNT(id_acct) as users_count FROM %sawm_accounts WHERE def_acct = 1 AND id_domain IN (%d)';
		return sprintf($sSql, $this->prefix(), implode(', ', $aDomainsIds));
	}

	/**
	 * @param array $aDomainsIds
	 * @param bool $bEnable
	 *
	 * @return string
	 */
	public function enableOrDisableDomains($aDomainsIds, $bEnable)
	{
		$sSql = 'UPDATE %sawm_domains SET disabled = %d WHERE id_domain in (%s)';

		return sprintf($sSql, $this->prefix(), !$bEnable, implode(', ', $aDomainsIds));
	}

	/**
	 * @param int $iTenantId
	 * @param bool $bEnable
	 *
	 * @return string
	 */
	public function enableOrDisableDomainsByTenantId($iTenantId, $bEnable)
	{
		$sSql = 'UPDATE %sawm_domains SET disabled = %d WHERE id_tenant = %d';

		return sprintf($sSql, $this->prefix(), !$bEnable, $iTenantId);
	}

	/**
	 * @param array $aDomainsIds
	 *
	 * @return string
	 */
	public function deleteDomains($aDomainsIds)
	{
		$sSql = 'DELETE FROM %sawm_domains WHERE id_domain in (%s)';

		return sprintf($sSql, $this->prefix(), implode(', ', $aDomainsIds));
	}

	/**
	 * @param int $iDomainId
	 *
	 * @return string
	 */
	public function deleteDomain($iDomainId)
	{
		$sSql = 'DELETE FROM %sawm_domains WHERE id_domain = %d';

		return sprintf($sSql, $this->prefix(), $iDomainId);
	}

	/**
	 * @param string $sSearchDesc Default value is empty string.
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return string
	 */
	public function getDomainCount($sSearchDesc = '', $iTenantId = 0)
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

		return sprintf($sSql, $this->prefix(), $sWhere);
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return string
	 */
	public function getDomainIdsByTenantId($iTenantId)
	{
		$sSql = 'SELECT id_domain FROM %sawm_domains WHERE id_tenant = %d';

		return sprintf($sSql, $this->prefix(), $iTenantId);
	}

	/**
	 * @param string $sDomainName
	 *
	 * @return string
	 */
	public function domainExists($sDomainName)
	{
		$sSql = 'SELECT COUNT(id_domain) as domains_count FROM %sawm_domains WHERE %s = %s';

		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('name'), $this->escapeString(strtolower($sDomainName)));
	}

	/**
	 * @param int $iDomainId
	 *
	 * @return string
	 */
	public function getDomainById($iDomainId)
	{
		return $this->getDomainByWhere(sprintf('%s = %d', $this->escapeColumn('id_domain'), $iDomainId));
	}

	/**
	 * @param string $sDomainName
	 *
	 * @return string
	 */
	public function getDomainByName($sDomainName)
	{
		return $this->getDomainByWhere(sprintf('%s = %s',
			$this->escapeColumn('name'), $this->escapeString(strtolower((string) $sDomainName))));
	}

	/**
	 * @param string $sDomainUrl
	 *
	 * @return string
	 */
	public function getDomainByUrl($sDomainUrl)
	{
		return $this->getDomainByWhere(sprintf('%s = %s',
			$this->escapeColumn('url'), $this->escapeString(strtolower((string) $sDomainUrl))));
	}
	
	/**
	 * @param int $iTenantId
	 *
	 * @return string
	 */
	public function getDefaultDomainByTenantId($iTenantId)
	{
		return $this->getDomainByWhere(sprintf('%s = %d AND %s = %d', $this->escapeColumn('id_tenant'), $iTenantId, $this->escapeColumn('is_default_for_tenant'), 1));
	}

	/**
	 * @param string $sWhere
	 *
	 * @return string
	 */
	protected function getDomainByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CDomain::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_domains WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(), $sWhere);
	}
}

/**
 * @internal
 * 
 * @package Domains
 * @subpackage Storages
 */
class CApiDomainsCommandCreatorMySQL extends CApiDomainsCommandCreator
{
	/**
	 * @param int $iPage
	 * @param int $iDomainsPerPage
	 * @param string $sOrderBy Default value is **'name'**
	 * @param bool $bOrderType Default value is **true**.
	 * @param string $sSearchDesc Default value is empty string.
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return string
	 */
	public function getDomainsList($iPage, $iDomainsPerPage, $sOrderBy = 'name', $bOrderType = true, $sSearchDesc = '', $iTenantId = 0)
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

		$sSql = sprintf($sSql, $this->prefix(), $sWhere, $sOrderBy,
			((bool) $bOrderType) ? 'ASC' : 'DESC',
			$iDomainsPerPage,
			($iPage > 0) ? ($iPage - 1) * $iDomainsPerPage : 0
		);

		return $sSql;
	}
}

/**
 * @todo make it
 * 
 * @internal
 * 
 * @package Domains
 * @subpackage Storages
 */
class CApiDomainsCommandCreatorPostgreSQL  extends CApiDomainsCommandCreatorMySQL
{
	// TODO
}
