(function () {
	AfterLogicApi.addPluginHook('view-model-defined', function (sViewModelName, oViewModel) {
		var
			oExternalServices = AfterLogicApi.getPluginSettings('ExternalServices')
		;
		
		if (oViewModel && ('CWrapLoginViewModel' === sViewModelName || 
				'CExternalServicesViewModel' === sViewModelName ||
					'CComposeViewModel' === sViewModelName || 
					'CHelpdeskLoginViewModel' === sViewModelName))
		{
			if (oViewModel.servicesAccounts)
			{
				oViewModel.servicesAccounts(oExternalServices ? oExternalServices.Users : []);
			}
			else
			{
				oViewModel.servicesAccounts = ko.observableArray(oExternalServices ? oExternalServices.Users : []);
			}
			_.each(oViewModel.servicesAccounts(), function (oItem){
				var aScopes = [];
				_.each(oItem.UserScopes, function (sValue, sKey){
					aScopes.push({
						'name': sKey, 
						'displayName': AfterLogicApi.i18n('PLUGIN_EXTERNAL_SERVICES/SCOPE_' + sKey.toUpperCase()),
						'value': ko.observable(sValue)
					});
				}, oViewModel);
				
				if (!oItem.isFilestorage)
				{
					oItem.isFilestorage =  ko.observable(false);
					oItem.userScopes = ko.observableArray(aScopes);
					oItem.userScopes.subscribe(function(aValue) {
						oItem.isFilestorage(false);
						_.each(aValue, function(oScopeItem) {
							if (oScopeItem.name === 'filestorage' && oScopeItem.value())
							{
								oItem.isFilestorage(true);
							}
						}, oItem);
					}, oItem);
					oItem.userScopes.valueHasMutated();
					
					oItem.connected = ko.observable(oItem.Connected && !oItem.Disabled);
					
					oItem.userName = ko.observable(oItem.Connected && !oItem.Disabled ? '(' + oItem.Name + ')' : '');
				}
			}, oViewModel);
			
			if ('CWrapLoginViewModel' === sViewModelName)
			{
				oViewModel.servicesConnectors = ko.observableArray(oExternalServices ? oExternalServices.Connectors : []);
				oViewModel.externalAuthClick = function (sSocialName) {
					$.cookie('external-services-redirect', 'login');
					$.cookie('external-services-scopes', 'auth');
					if (typeof AfterLogicApi.getRequestParam('invite-auth') === 'string')
					{
						$.cookie('external-services-invite-hash', AfterLogicApi.getRequestParam('invite-auth'));
					}
					window.location.href = '?external-services=' + sSocialName;
				};
				
				if (AfterLogicApi.getRequestParam('error') === '109')
				{
					if (AfterLogicApi.getRequestParam('email'))
					{
						AfterLogicApi.showError(AfterLogicApi.i18n('PLUGIN_EXTERNAL_SERVICES/UNKNOWN_ACCOUNT', {'EMAIL': AfterLogicApi.getRequestParam('email')}));
					}
					else
					{
						AfterLogicApi.showError(AfterLogicApi.i18n('PLUGIN_EXTERNAL_SERVICES/CANT_GET_EMAIL', {'SERVICE_NAME': AfterLogicApi.getRequestParam('service')}));
					}
				}
				else if (AfterLogicApi.getRequestParam('error') === 'es-001')
				{
					AfterLogicApi.showError(AfterLogicApi.i18n('PLUGIN_EXTERNAL_SERVICES/CONNECTOR_IS_NOT_ALLOWED', {'CONNECTOR': AfterLogicApi.getRequestParam('service')}));
				}
				else if (AfterLogicApi.getRequestParam('error') === 'es-002')
				{
					if (AfterLogicApi.getRequestParam('email'))
					{
						AfterLogicApi.showError(AfterLogicApi.i18n('PLUGIN_EXTERNAL_SERVICES/AUTHENTICATION_IS_DISABLED', {'EMAIL': AfterLogicApi.getRequestParam('email')}));
					}
					else
					{
						AfterLogicApi.showError(AfterLogicApi.i18n('PLUGIN_EXTERNAL_SERVICES/CANT_GET_EMAIL', {'SERVICE_NAME': AfterLogicApi.getRequestParam('service')}));
					}
				}
				else if(AfterLogicApi.getRequestParam('error') === 'es-003')
				{
					AfterLogicApi.showError(AfterLogicApi.i18n('PLUGIN_EXTERNAL_SERVICES/INFO_ACCOUNT_ALREADY_ASSIGNED'));
				}
			}
			if ('CHelpdeskLoginViewModel' === sViewModelName)
			{
				oViewModel.servicesConnectors = ko.observableArray(oExternalServices ? oExternalServices.Connectors : []);
				oViewModel.externalAuthClick = function (sSocialName) {
					$.cookie('external-services-redirect', 'helpdesk');
					$.cookie('external-services-scopes', 'auth');
					window.location.href = '?external-services=' + sSocialName;
				};
			}
			if ('CComposeViewModel' === sViewModelName)
			{
				if (!window.Dropbox)
				{
					AfterLogicApi.loadScript('https://www.dropbox.com/static/api/2/dropins.js', null, {
						'id': 'dropboxjs',
						'data-app-key': AfterLogicApi.getAppDataItem('SocialDropboxId')
					});
				}
				
				oViewModel.onGoogleApiLoad = function ()
				{
					window.gapi.client.load('drive', 'v2', _.bind(oViewModel.doGoogleAuth, oViewModel, true));
					window.gapi.load('picker');
				};						

				oViewModel.doGoogleAuth = function (bImmediate, fCallback) 
				{
					var oauthToken = window.gapi.auth.getToken();
					if (!oauthToken || _.isEmpty(oauthToken) || oauthToken && oauthToken.error)
					{
						window.gapi.auth.authorize({
								'client_id': AfterLogicApi.getAppDataItem('SocialGoogleId'),
								'scope': ['https://www.googleapis.com/auth/drive.readonly'],
								'immediate': bImmediate
							},
							fCallback);
					 }
				};
				
				oViewModel.showGooglePicker = function () 
				{
					var 
						oUser = AfterLogicApi.getAppDataItem('User')
					;

					oViewModel.picker = new window.google.picker.PickerBuilder()
						.addView(new window.google.picker.DocsView().setIncludeFolders(true))
						.setOAuthToken(window.gapi.auth.getToken().access_token)
						.setAppId(AfterLogicApi.getAppDataItem('SocialGoogleId'))
						.setOrigin(window.location.protocol + '//' +  window.location.host)
						.setCallback(_.bind(oViewModel.googlePickerCallback, this))
						.enableFeature(window.google.picker.Feature.NAV_HIDDEN)
						.enableFeature(window.google.picker.Feature.MULTISELECT_ENABLED)
						.setLocale(oUser ? oUser.DefaultLanguageShort : 'en')
						.build();

					oViewModel.picker.setVisible(true);
					oViewModel.picker.Ab.style.zIndex = 2000;
				};

				if (AfterLogicApi.getAppDataItem('SocialGoogle'))
				{
					AfterLogicApi.loadScript('https://apis.google.com/js/client.js?onload=onGoogleApiLoad',
						_.bind(oViewModel.onGoogleApiLoad, oViewModel), null, 'onGoogleApiLoad');
				}
				
				oViewModel.getAttachFromTooltip = function (sService)
				{
					return AfterLogicApi.i18n('PLUGIN_EXTERNAL_SERVICES/ATTACH_FROM_' + sService.toUpperCase() + '_CLICK');
				};
				
				oViewModel.onShowAttachFromClick = function (sService)
				{
					if (sService === 'google')
					{
						var 
							oauthToken = window.gapi.auth.getToken()
						;

						if (oauthToken && !oauthToken.error) 
						{
							oViewModel.showGooglePicker();
						}
						else
						{
							oViewModel.doGoogleAuth(false, _.bind(oViewModel.showGooglePicker, oViewModel));							
						}
					}
					else if (sService === 'dropbox')
					{
						if (window.Dropbox)
						{
							window.Dropbox.choose({
									success: _.bind(this.dropboxCallback, this),
									cancel: function() {},
									linkType: "direct",
									multiselect: true
								}
							);
						}
					}
				};

				oViewModel.googlePickerCallback = function (aData)
				{
					var
						oAttach = null,
						aUrls = [],
						oParameters = {},
						self = this
					;
					if (aData[window.google.picker.Response.ACTION] === window.google.picker.Action.PICKED)
					{
						_.each(aData[window.google.picker.Response.DOCUMENTS], function (oFileItem) {
							oAttach = AfterLogicApi.createObjectInstance('CMailAttachmentModel');
							if (oAttach)
							{
								oAttach
									.fileName(oFileItem.name)
									.hash(oFileItem.id)
									.size(oFileItem.sizeBytes)
									.uploadStarted(true)
									.type(oFileItem.mimeType)
									.thumb(oAttach.type().substr(0, 5) === 'image');
							}

							self.attachments.push(oAttach);
							aUrls.push(oFileItem.id);

						}, this);

						oParameters = {
							'Action': 'FilesUploadByLink',
							'Links': aUrls,
							'LinksAsIds': true,
							'AccessToken': window.gapi.auth.getToken().access_token
						};

						oViewModel.messageUploadAttachmentsStarted(true);
						AfterLogicApi.sendAjaxRequest(oParameters, oViewModel.onFilesUpload, this);
					}

				};
				// ----------------

				// DropBox
				oViewModel.dropboxCallback = function (aFileItems)
				{
					var
						oAttach = null,
						aUrls = [],
						oParameters = {},
						self = this
					;

					_.each(aFileItems, function (oFileItem) {
						oAttach = AfterLogicApi.createObjectInstance('CMailAttachmentModel');
						if (oAttach)
						{
							oAttach
								.fileName(oFileItem.name)
								.hash(oFileItem.link)
								.size(oFileItem.bytes)
								.uploadStarted(true)
								.thumb(!!oFileItem.thumbnailLink);

							self.attachments.push(oAttach);
						}
						aUrls.push(oFileItem.link);
					}, self);


					oParameters = {
						'Action': 'FilesUploadByLink',
						'Links': aUrls
					};

					oViewModel.messageUploadAttachmentsStarted(true);
					AfterLogicApi.sendAjaxRequest(oParameters, oViewModel.onFilesUpload, this);
				};
				// ----------------				
			}
		}
	});
}());
