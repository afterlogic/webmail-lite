<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Tenants
 * @subpackage Storages
 */
class CApiTenantsCommandCreator extends api_CommandCreator
{
	/**
	 * @param int $iTenantId
	 *
	 * @return string
	 */
	public function getTenantAllocatedSize($iTenantId)
	{
		$sSql = 'SELECT SUM(quota) as allocated_size FROM %sawm_accounts WHERE id_tenant = %d';

		return sprintf($sSql, $this->prefix(), $iTenantId);
	}
	
	/**
	 * @param string $sSearchDesc Default value is empty string.
	 *
	 * @return string
	 */
	public function getTenantCount($sSearchDesc = '')
	{
		$sWhere = '';
		if (!empty($sSearchDesc))
		{
			$sSearchDesc = '\'%'.$this->escapeString($sSearchDesc, true, true).'%\'';
			$sWhere = ' WHERE login LIKE '.$sSearchDesc.' OR description LIKE '.$sSearchDesc;
		}

		$sSql = 'SELECT COUNT(id_tenant) as tenants_count FROM %sawm_tenants%s';

		return sprintf($sSql, $this->prefix(), $sWhere);
	}

	/**
	 * @param string $sWhere
	 *
	 * @return string
	 */
	protected function getTenantByWhere($sWhere)
	{
		return api_AContainer::DbGetObjectSqlString(
			$sWhere, $this->prefix().'awm_tenants', CTenant::getStaticMap(), $this->oHelper);
	}

	/**
	 * @param int $mTenantId
	 * @param bool $bIdIsHash Default value is **false**.
	 *
	 * @return string
	 */
	public function getTenantById($mTenantId, $bIdIsHash = false)
	{
		if ($bIdIsHash)
		{
			return $this->getTenantByWhere(sprintf('SUBSTR(MD5(CONCAT(id_tenant, %s)),1,8) = %s',
				$this->escapeString(CApi::$sSalt),
				$this->escapeString($mTenantId))
			);
		}
		else
		{
			return $this->getTenantByWhere(sprintf('%s = %d',
				$this->escapeColumn('id_tenant'), $mTenantId));
		}
	}

	/**
	 * @param string $sTenantLogin
	 * @param string $sTenantPassword Default value is **null**.
	 *
	 * @return string
	 */
	public function getTenantIdByLogin($sTenantLogin, $sTenantPassword = null)
	{
		$sAdd = '';
		if (null !== $sTenantPassword)
		{
			$sAdd = sprintf(' AND login_enabled = 1 AND disabled = 0 AND %s = %s',
				$this->escapeColumn('password'), $this->escapeString(
					CTenant::hashPassword($sTenantPassword)));
		}

		$sSql = 'SELECT id_tenant FROM %sawm_tenants WHERE %s = %s%s';
		return sprintf($sSql, $this->prefix(), $this->escapeColumn('login'), $this->escapeString($sTenantLogin), $sAdd);
	}

	/**
	 * @param int $iDomainId
	 *
	 * @return string
	 */
	public function getTenantIdByDomainId($iDomainId)
	{
		$sSql = 'SELECT id_tenant FROM %sawm_domains WHERE %s = %d';
		return sprintf($sSql, $this->prefix(), $this->escapeColumn('id_domain'), $iDomainId);
	}

	/**
	 * @param int $iIdTenant
	 *
	 * @return string
	 */
	public function getTenantLoginById($iIdTenant)
	{
		$sSql = 'SELECT %s FROM %sawm_tenants WHERE %s = %d';
		return sprintf($sSql, $this->escapeColumn('login'), $this->prefix(), $this->escapeColumn('id_tenant'), $iIdTenant);
	}

	/**
	 * @param int $iChannelId
	 *
	 * @return string
	 */
	public function getTenantsIdsByChannelId($iChannelId)
	{
		$sSql = 'SELECT id_tenant FROM %sawm_tenants WHERE id_channel = %d';

		return sprintf($sSql, $this->prefix(), $iChannelId);
	}

	/**
	 * @param int $iTenantId
	 * @param int|null $iExceptUserId = null
	 *
	 * @return string
	 */
	public function getSubscriptionUserUsage($iTenantId, $iExceptUserId = null)
	{
		$sSql = 'SELECT _settings.id_subscription, COUNT(_settings.id_setting) AS cnt FROM %sawm_settings AS _settings
INNER JOIN %sawm_accounts AS _acct ON _acct.id_user = _settings.id_user
WHERE _acct.id_tenant = %d AND _acct.deleted = 0%s
GROUP BY _settings.id_subscription';

		$sExcept = is_int($iExceptUserId)? ' AND _acct.id_user NOT IN ('.$iExceptUserId.')' : '';

		return sprintf($sSql, $this->prefix(), $this->prefix(), $iTenantId, $sExcept);
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return string
	 */
	function getTenantDomains($iTenantId)
	{
		$sSql = 'SELECT id_domain, name FROM %sawm_domains WHERE id_tenant = %d';

		return sprintf($sSql, $this->prefix(), $iTenantId);
	}

	/**
	 * @param CTenant $oTenant
	 *
	 * @return string
	 */
	function createTenant(CTenant $oTenant)
	{
		return api_AContainer::DbCreateObjectSqlString($this->prefix().'awm_tenants', $oTenant, $this->oHelper);
	}

	/**
	 * @param CTenant $oTenant
	 *
	 * @return string
	 */
	function updateTenant(CTenant $oTenant)
	{
		$aResult = api_AContainer::DbUpdateArray($oTenant, $this->oHelper);

		$sSql = 'UPDATE %sawm_tenants SET %s WHERE id_tenant = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult), $oTenant->IdTenant);
	}

	/**
	 * @param int $iTenantId
	 * @param string $sCapa
	 *
	 * @return string
	 */
	function updateTenantMainCapa($iTenantId, $sCapa)
	{
		$sSql = 'UPDATE %sawm_tenants SET %s = %s WHERE %s = %d';
		
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('capa'), $this->escapeString($sCapa),
			$this->escapeColumn('id_tenant'),  $iTenantId);
	}

	/**
	 * @param CTenant $oTenant
	 * @param int $iAllocatedSizeInBytes
	 *
	 * @return string
	 */
	public function allocateFileUsage($oTenant, $iAllocatedSizeInBytes)
	{
		$sSql = 'UPDATE %sawm_tenants SET files_usage_bytes = %d WHERE id_tenant = %d';
		return sprintf($sSql, $this->prefix(), abs($iAllocatedSizeInBytes), $oTenant->IdTenant);
	}

	/**
	 * @param string $sLogin
	 * @param int $niExceptTenantId Default value is **null**.
	 *
	 * @return string
	 */
	public function isTenantExists($sLogin, $niExceptTenantId = null)
	{
		$sAddWhere = (is_integer($niExceptTenantId)) ? ' AND id_tenant <> '.$niExceptTenantId : '';

		$sSql = 'SELECT COUNT(id_tenant) as tenants_count FROM %sawm_tenants WHERE login = %s%s';

		return sprintf($sSql, $this->prefix(), $this->escapeString(strtolower($sLogin)), $sAddWhere);
	}

	/**
	 * @param array $aTenantIds
	 *
	 * @return string
	 */
	function deleteTenants($aTenantIds)
	{
		$aIds = api_Utils::SetTypeArrayValue($aTenantIds, 'int');

		$sSql = 'DELETE FROM %sawm_tenants WHERE id_tenant in (%s)';
		return sprintf($sSql, $this->prefix(), implode(',', $aIds));
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return string
	 */
	function deleteTenantSubscriptions($iTenantId)
	{
		$sSql = 'DELETE FROM %sawm_subscriptions WHERE id_tenant = %d';
		return sprintf($sSql, $this->prefix(), $iTenantId);
	}
	
	
		/**
	 * @param string $sWhere
	 *
	 * @return string
	 */
	protected function getSocialByWhere($sWhere)
	{
		return api_AContainer::DbGetObjectSqlString(
			$sWhere, $this->prefix().'awm_tenant_socials', CTenantSocials::getStaticMap(), $this->oHelper);
	}
	
	/**
	 * @param int $iIdTenant
	 *
	 * @return array|false
	 */
	public function getSocials($iIdTenant)
	{
		return $this->getSocialByWhere(sprintf('%s = %d',
			$this->escapeColumn('id_tenant'), $iIdTenant));
	}		
	
	/**
	 * @param int $iIdSocial
	 *
	 * @return array|false
	 */
	public function getSocialById($iIdSocial)
	{
		return $this->getSocialByWhere(sprintf('%s = %d',
			$this->escapeColumn('id'), $iIdSocial));
	}		
	
	/**
	 * @param int $iIdTenant
	 * @param string $sSocialName
	 *
	 * @return array|false
	 */
	public function getSocialByName($iIdTenant, $sSocialName)
	{
		return $this->getSocialByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id'), $iIdTenant, $this->escapeColumn('social_name'), $this->escapeColumn(strtolower($sSocialName))));
	}			
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function isSocialExists(CTenantSocials $oSocial)
	{
		$sSql = 'SELECT COUNT(id) as socials_count FROM %sawm_tenant_socials WHERE id_tenant = %d AND social_name = %s';

		return sprintf($sSql, $this->prefix(), $oSocial->IdTenant, $this->escapeString($oSocial->SocialName));
	}	
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function createSocial(CTenantSocials &$oSocial)
	{
		return api_AContainer::DbCreateObjectSqlString($this->prefix().'awm_tenant_socials', $oSocial, $this->oHelper);
	}
	
	/**
	 * @param int $iIdSocial
	 *
	 * @return bool
	 */
	public function deleteSocial($iIdSocial)
	{
		$sSql = 'DELETE FROM %sawm_tenant_socials WHERE id = %d';
		return sprintf($sSql, $this->prefix(), $iIdSocial);
	}
	
	/**
	 * @param int $iTenanatId
	 *
	 * @return bool
	 */
	public function deleteSocialsByTenantId($iTenanatId)
	{
		$sSql = 'DELETE FROM %sawm_tenant_socials WHERE id_tenant = %d';
		return sprintf($sSql, $this->prefix(), $iTenanatId);
	}	
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function updateSocial(CTenantSocials $oSocial)
	{
		$aResult = api_AContainer::DbUpdateArray($oSocial, $this->oHelper);

		$sSql = 'UPDATE %sawm_tenant_socials SET %s WHERE id = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult), $oSocial->Id);
	}		
}

/**
 * @package Tenants
 * @subpackage Storages
 */
class CApiTenantsCommandCreatorMySQL extends CApiTenantsCommandCreator
{
	/**
	 * @param int $iPage
	 * @param int $iTenantsPerPage
	 * @param string $sOrderBy Default value is **'login'**.
	 * @param bool $bOrderType Default value is **true**.
	 * @param string $sSearchDesc Default value is empty string.
	 *
	 * @return string
	 */
	public function getTenantList($iPage, $iTenantsPerPage, $sOrderBy = 'login', $bOrderType = true, $sSearchDesc = '')
	{
		$sWhere = '';
		if (!empty($sSearchDesc))
		{
			$sSearchDesc = '\'%'.$this->escapeString($sSearchDesc, true, true).'%\'';

			$sWhere = ' WHERE login LIKE '.$sSearchDesc.' OR description LIKE '.$sSearchDesc;
		}

		$sOrderBy = empty($sOrderBy) ? 'login' : $sOrderBy;

		$sSql = 'SELECT id_tenant, login, description FROM %sawm_tenants %s ORDER BY %s %s LIMIT %d OFFSET %d';

		$sSql = sprintf($sSql, $this->prefix(), $sWhere, $sOrderBy,
			((bool) $bOrderType) ? 'ASC' : 'DESC',
			$iTenantsPerPage,
			($iPage > 0) ? ($iPage - 1) * $iTenantsPerPage : 0
		);

		return $sSql;
	}
}

/**
 * @package Tenants
 * @subpackage Storages
 */
class CApiTenantsCommandCreatorPostgreSQL extends CApiTenantsCommandCreatorMySQL
{
	// TODO
}
