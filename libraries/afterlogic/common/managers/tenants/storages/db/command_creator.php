<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
			$sSearchDesc = '\'%'.$this->escapeString($sSearchDesc, true, true).'%\'';
			$sWhere = ' WHERE login LIKE '.$sSearchDesc.' OR description LIKE '.$sSearchDesc;
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
	 * @param int $mTenantId
	 * @param bool $bIdIsHash = false
	 *
	 * @return string
	 */
	public function GetTenantById($mTenantId, $bIdIsHash = false)
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
	 * @param int $iDomainId
	 *
	 * @return string
	 */
	public function GetTenantIdByDomainId($iDomainId)
	{
		$sSql = 'SELECT id_tenant FROM %sawm_domains WHERE %s = %d';
		return sprintf($sSql, $this->Prefix(), $this->escapeColumn('id_domain'), $iDomainId);
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
	 * @param int $iTenantId
	 * @param int|null $iExceptUserId = null
	 *
	 * @return string
	 */
	public function GetSubscriptionUserUsage($iTenantId, $iExceptUserId = null)
	{
		$sSql = 'SELECT _settings.id_subscription, COUNT(_settings.id_setting) AS cnt FROM %sawm_settings AS _settings
INNER JOIN %sawm_accounts AS _acct ON _acct.id_user = _settings.id_user
WHERE _acct.id_tenant = %d AND _acct.deleted = 0%s
GROUP BY _settings.id_subscription';

		$sExcept = is_int($iExceptUserId)? ' AND _acct.id_user NOT IN ('.$iExceptUserId.')' : '';

		return sprintf($sSql, $this->Prefix(), $this->Prefix(), $iTenantId, $sExcept);
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
	 * @param int $iTenantID
	 * @param string $sCapa
	 *
	 * @return string
	 */
	function UpdateTenantMainCapa($iTenantID, $sCapa)
	{
		$sSql = 'UPDATE %sawm_tenants SET %s = %s WHERE %s = %d';
		
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('capa'), $this->escapeString($sCapa),
			$this->escapeColumn('id_tenant'),  $iTenantID);
	}

	/**
	 * @param CTenant $oTenant
	 * @param int $iAllocatedSizeInBytes
	 *
	 * @return string
	 */
	public function AllocateFileUsage($oTenant, $iAllocatedSizeInBytes)
	{
		$sSql = 'UPDATE %sawm_tenants SET files_usage_bytes = %d WHERE id_tenant = %d';
		return sprintf($sSql, $this->Prefix(), abs($iAllocatedSizeInBytes), $oTenant->IdTenant);
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

	/**
	 * @param int $iTenantId
	 *
	 * @return string
	 */
	function DeleteTenantSubscriptions($iTenantId)
	{
		$sSql = 'DELETE FROM %sawm_subscriptions WHERE id_tenant = %d';
		return sprintf($sSql, $this->Prefix(), $iTenantId);
	}
	
	
		/**
	 * @param string $sWhere
	 *
	 * @return string
	 */
	protected function getSocialByWhere($sWhere)
	{
		return api_AContainer::DbGetObjectSqlString(
			$sWhere, $this->Prefix().'awm_tenant_socials', CTenantSocials::GetStaticMap(), $this->oHelper);
	}
	
	/**
	 * @param int $iIdTenant
	 *
	 * @return array | false
	 */
	public function GetSocials($iIdTenant)
	{
		return $this->getSocialByWhere(sprintf('%s = %d',
			$this->escapeColumn('id_tenant'), $iIdTenant));
	}		
	
	/**
	 * @param int $iIdSocial
	 *
	 * @return array | false
	 */
	public function GetSocialById($iIdSocial)
	{
		return $this->getSocialByWhere(sprintf('%s = %d',
			$this->escapeColumn('id'), $iIdSocial));
	}		
	
	/**
	 * @param int $iIdTenant
	 * @param string $sSocialName
	 *
	 * @return array | false
	 */
	public function GetSocialByName($iIdTenant, $sSocialName)
	{
		return $this->getSocialByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id'), $iIdTenant, $this->escapeColumn('social_name'), $this->escapeColumn(strtolower($sSocialName))));
	}			
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function SocialExists(CTenantSocials $oSocial)
	{
		$sSql = 'SELECT COUNT(id) as socials_count FROM %sawm_tenant_socials WHERE id_tenant = %d AND social_name = %s';

		return sprintf($sSql, $this->Prefix(), $oSocial->IdTenant, $this->escapeString($oSocial->SocialName));
	}	
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function CreateSocial(CTenantSocials &$oSocial)
	{
		return api_AContainer::DbCreateObjectSqlString($this->Prefix().'awm_tenant_socials', $oSocial, $this->oHelper);
	}
	
	/**
	 * @param int $iIdSocial
	 *
	 * @return bool
	 */
	public function DeleteSocial($iIdSocial)
	{
		$sSql = 'DELETE FROM %sawm_tenant_socials WHERE id = %d';
		return sprintf($sSql, $this->Prefix(), $iIdSocial);
	}
	
	/**
	 * @param int $iTenanatId
	 *
	 * @return bool
	 */
	public function DeleteSocialsByTenantId($iTenanatId)
	{
		$sSql = 'DELETE FROM %sawm_tenant_socials WHERE id_tenant = %d';
		return sprintf($sSql, $this->Prefix(), $iTenanatId);
	}	
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function UpdateSocial(CTenantSocials $oSocial)
	{
		$aResult = api_AContainer::DbUpdateArray($oSocial, $this->oHelper);

		$sSql = 'UPDATE %sawm_tenant_socials SET %s WHERE id = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult), $oSocial->Id);
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
			$sSearchDesc = '\'%'.$this->escapeString($sSearchDesc, true, true).'%\'';

			$sWhere = ' WHERE login LIKE '.$sSearchDesc.' OR description LIKE '.$sSearchDesc;
		}

		$sOrderBy = empty($sOrderBy) ? 'login' : $sOrderBy;

		$sSql = 'SELECT id_tenant, login, description FROM %sawm_tenants %s ORDER BY %s %s LIMIT %d OFFSET %d';

		$sSql = sprintf($sSql, $this->Prefix(), $sWhere, $sOrderBy,
			((bool) $bOrderType) ? 'ASC' : 'DESC',
			$iTenantsPerPage,
			($iPage > 0) ? ($iPage - 1) * $iTenantsPerPage : 0
		);

		return $sSql;
	}
}

class CApiTenantsCommandCreatorPostgreSQL extends CApiTenantsCommandCreatorMySQL
{
	// TODO
}
