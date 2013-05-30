<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Tenants
 */
class CApiTenantsCommandCreator extends api_CommandCreator
{
	/**
	 * @param int $iTenantId
	 *
	 * @return string
	 */
	public function GetTenantAllocatedSize($iTenantId)
	{
		$sSql = 'SELECT SUM(quota) as allocated_size FROM %sawm_accounts WHERE id_tenant = %d';

		return sprintf($sSql, $this->Prefix(), $iTenantId);
	}

	/**
	 * @param string $sSearchDesc = ''
	 *
	 * @return string
	 */
	public function GetTenantCount($sSearchDesc = '')
	{
		$sWhere = '';
		if (!empty($sSearchDesc))
		{
			$sWhere = ' WHERE login LIKE '.$this->escapeString('%'.strtolower($sSearchDesc).'%').
				' OR description LIKE '.$this->escapeString('%'.strtolower($sSearchDesc).'%');;
		}

		$sSql = 'SELECT COUNT(id_tenant) as tenants_count FROM %sawm_tenants%s';

		return sprintf($sSql, $this->Prefix(), $sWhere);
	}

	/**
	 * @param string $sWhere
	 *
	 * @return string
	 */
	protected function getTenantByWhere($sWhere)
	{
		return api_AContainer::DbGetObjectSqlString(
			$sWhere, $this->Prefix().'awm_tenants', CTenant::GetStaticMap(), $this->oHelper);
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return string
	 */
	public function GetTenantById($iTenantId)
	{
		return $this->getTenantByWhere(sprintf('%s = %d',
			$this->escapeColumn('id_tenant'), $iTenantId));
	}

	/**
	 * @param string $sTenantLogin
	 * @param string $sTenantPassword = null
	 *
	 * @return string
	 */
	public function GetTenantIdByLogin($sTenantLogin, $sTenantPassword = null)
	{
		$sAdd = '';
		if (null !== $sTenantPassword)
		{
			$sAdd = sprintf(' AND login_enabled = 1 AND disabled = 0 AND %s = %s',
				$this->escapeColumn('password'), $this->escapeString(
					CTenant::HashPassword($sTenantPassword)));
		}

		$sSql = 'SELECT id_tenant FROM %sawm_tenants WHERE %s = %s%s';
		return sprintf($sSql, $this->Prefix(), $this->escapeColumn('login'), $this->escapeString($sTenantLogin), $sAdd);
	}

	/**
	 * @param int $iIdTenant
	 *
	 * @return string
	 */
	public function GetTenantLoginById($iIdTenant)
	{
		$sSql = 'SELECT %s FROM %sawm_tenants WHERE %s = %d';
		return sprintf($sSql, $this->escapeColumn('login'), $this->Prefix(), $this->escapeColumn('id_tenant'), $iIdTenant);
	}

	/**
	 * @param int $iChannelId
	 *
	 * @return string
	 */
	public function GetTenantsIdsByChannelId($iChannelId)
	{
		$sSql = 'SELECT id_tenant FROM %sawm_tenants WHERE id_channel = %d';

		return sprintf($sSql, $this->Prefix(), $iChannelId);
	}

	/**
	 * @param int $iIdTenant
	 *
	 * @return string
	 */
	function GetTenantDomains($iTenantId)
	{
		$sSql = 'SELECT id_domain, name FROM %sawm_domains WHERE id_tenant = %d';

		return sprintf($sSql, $this->Prefix(), $iTenantId);
	}

	/**
	 * @param CTenant $oTenant
	 *
	 * @return string
	 */
	function CreateTenant(CTenant $oTenant)
	{
		return api_AContainer::DbCreateObjectSqlString($this->Prefix().'awm_tenants', $oTenant, $this->oHelper);
	}

	/**
	 * @param CTenant $oTenant
	 *
	 * @return string
	 */
	function UpdateTenant(CTenant $oTenant)
	{
		$aResult = api_AContainer::DbUpdateArray($oTenant, $this->oHelper);

		$sSql = 'UPDATE %sawm_tenants SET %s WHERE id_tenant = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult), $oTenant->IdTenant);
	}

	/**
	 * @param string $sLogin
	 * @param int $niExceptTenantId = null
	 *
	 * @return string
	 */
	public function TenantExists($sLogin, $niExceptTenantId = null)
	{
		$sAddWhere = (is_integer($niExceptTenantId)) ? ' AND id_tenant <> '.$niExceptTenantId : '';

		$sSql = 'SELECT COUNT(id_tenant) as tenants_count FROM %sawm_tenants WHERE login = %s%s';

		return sprintf($sSql, $this->Prefix(), $this->escapeString(strtolower($sLogin)), $sAddWhere);
	}

	/**
	 * @param array $aTenantIds
	 *
	 * @return string
	 */
	function DeleteTenants($aTenantIds)
	{
		$aIds = api_Utils::SetTypeArrayValue($aTenantIds, 'int');

		$sSql = 'DELETE FROM %sawm_tenants WHERE id_tenant in (%s)';
		return sprintf($sSql, $this->Prefix(), implode(',', $aIds));
	}
}

/**
 * @package Tenants
 */
class CApiTenantsCommandCreatorMySQL extends CApiTenantsCommandCreator
{
	/**
	 * @param int $iPage
	 * @param int $iTenantsPerPage
	 * @param string $sOrderBy = 'login'
	 * @param bool $bOrderType = true
	 * @param string $sSearchDesc = ''
	 *
	 * @return string
	 */
	public function GetTenantList($iPage, $iTenantsPerPage, $sOrderBy = 'login', $bOrderType = true, $sSearchDesc = '')
	{
		$sWhere = '';
		if (!empty($sSearchDesc))
		{
			$sWhere = ' WHERE login LIKE '.$this->escapeString('%'.strtolower($sSearchDesc).'%').
				' OR description LIKE '.$this->escapeString('%'.strtolower($sSearchDesc).'%');
		}

		$sOrderBy = empty($sOrderBy) ? 'login' : $sOrderBy;

		$sSql = 'SELECT id_tenant, login, description FROM %sawm_tenants %s ORDER BY %s %s LIMIT %d, %d';

		$sSql = sprintf($sSql, $this->Prefix(), $sWhere, $sOrderBy,
			((bool) $bOrderType) ? 'ASC' : 'DESC', ($iPage > 0) ? ($iPage - 1) * $iTenantsPerPage : 0,
			$iTenantsPerPage);

		return $sSql;
	}
}
