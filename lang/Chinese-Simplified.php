<?php
define('PROC_ERROR_ACCT_CREATE', '创建帐号失败');
define('PROC_WRONG_ACCT_PWD', '帐号密码错误');
define('PROC_CANT_LOG_NONDEF', '无法登陆到非默认帐号');
define('PROC_CANT_INS_NEW_FILTER', '无法添加新过滤');
define('PROC_FOLDER_EXIST', '文件夹已经存在');
define('PROC_CANT_CREATE_FLD', '无法创建新文件夹');
define('PROC_CANT_INS_NEW_GROUP', '无法创建新组');
define('PROC_CANT_INS_NEW_CONT', '无法添加新联系人');
define('PROC_CANT_INS_NEW_CONTS', '无法添加新联系人（批量）');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', '无法添加联系人到组');
define('PROC_ERROR_ACCT_UPDATE', '修改帐号出现了一个错误');
define('PROC_CANT_UPDATE_CONT_SETTINGS', '无法更新联系人的设置');
define('PROC_CANT_GET_SETTINGS', '无法进入设置');
define('PROC_CANT_UPDATE_ACCT', '更新帐号失败');
define('PROC_ERROR_DEL_FLD', '删除文件夹时出现了一个错误');
define('PROC_CANT_UPDATE_CONT', '无法更新联系人');
define('PROC_CANT_GET_FLDS', '无法取得文件列表');
define('PROC_CANT_GET_MSG_LIST', '无法取得邮件列表');
define('PROC_MSG_HAS_DELETED', '此邮件从已经从邮件服务器删除');
define('PROC_CANT_LOAD_CONT_SETTINGS', '无法加载联系人设置');
define('PROC_CANT_LOAD_SIGNATURE', '无法加载帐号的签名');
define('PROC_CANT_GET_CONT_FROM_DB', '无法从数据库取得联系人');
define('PROC_CANT_GET_CONTS_FROM_DB', '无法从数据库取得联系人列表');
define('PROC_CANT_DEL_ACCT_BY_ID', '删除帐号失败');
define('PROC_CANT_DEL_FILTER_BY_ID', '无法删除过滤');
define('PROC_CANT_DEL_CONT_GROUPS', '删除联系人或组别失败');
define('PROC_WRONG_ACCT_ACCESS', '越权访问另一个帐号。');
define('PROC_SESSION_ERROR', '会话被终止，请检查是否你的登陆已超时，可能服务器时间或客户端时间不正确。');

define('MailBoxIsFull', '邮箱已满');
define('WebMailException', '内部服务器错误，请联系系统管理员。');
define('InvalidUid', '邮箱用户名错误');
define('CantCreateContactGroup', '无法创建联系人组别');
define('CantCreateUser', '创建用户失败');
define('CantCreateAccount', '创建帐号失败');
define('SessionIsEmpty', '会话为空');
define('FileIsTooBig', '文件过大');

define('PROC_CANT_MARK_ALL_MSG_READ', '无法标记所有邮件为已读');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', '无法标记所有邮件为未读');
define('PROC_CANT_PURGE_MSGS', '无法清除邮件');
define('PROC_CANT_DEL_MSGS', '无法删除邮件');
define('PROC_CANT_UNDEL_MSGS', '无法恢复被删除的邮件');
define('PROC_CANT_MARK_MSGS_READ', '无法标记邮件为已读');
define('PROC_CANT_MARK_MSGS_UNREAD', '无法标记邮件为未读');
define('PROC_CANT_SET_MSG_FLAGS', '无法设置邮件标记');
define('PROC_CANT_REMOVE_MSG_FLAGS', '无法移除邮件标记');
define('PROC_CANT_CHANGE_MSG_FLD', '无法更变邮件文件夹');
define('PROC_CANT_SEND_MSG', '邮件发送失败。');
define('PROC_CANT_SAVE_MSG', '邮件保存失败。');
define('PROC_CANT_GET_ACCT_LIST', '无法取得帐户列表');
define('PROC_CANT_GET_FILTER_LIST', '无法取得过滤列表');

define('PROC_CANT_LEAVE_BLANK', '请不能留空白字段');

define('PROC_CANT_UPD_FLD', '无法更改文件夹');
define('PROC_CANT_UPD_FILTER', '无法更新过滤');

define('ACCT_CANT_ADD_DEF_ACCT', '不能添加此帐号，因为另一个用户已经把它作为默认帐号。');
define('ACCT_CANT_UPD_TO_DEF_ACCT', '此帐号的状态不能更改为默认。');
define('ACCT_CANT_CREATE_IMAP_ACCT', '无法创建新帐号 (IMAP4 连接错误)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', '不能删除最后一个默认帐号');

define('LANG_LoginInfo', '登陆信息');
define('LANG_Email', 'Email');
define('LANG_Login', '登陆');
define('LANG_Password', '密码');
define('LANG_IncServer', '来信');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', '发信');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', '使用SMTP认证');
define('LANG_SignMe', '下载自动登陆');
define('LANG_Enter', '进入邮箱');

// interface strings

define('JS_LANG_TitleLogin', '登陆');
define('JS_LANG_TitleMessagesListView', '邮件列表');
define('JS_LANG_TitleMessagesList', '邮件列表');
define('JS_LANG_TitleViewMessage', '查看邮件');
define('JS_LANG_TitleNewMessage', '写邮件');
define('JS_LANG_TitleSettings', '设置');
define('JS_LANG_TitleContacts', '联系人');

define('JS_LANG_StandardLogin', '标准登陆模式');
define('JS_LANG_AdvancedLogin', '高级登陆模式');

define('JS_LANG_InfoWebMailLoading', '正在加载Webmail...');
define('JS_LANG_Loading', '正在加载...');
define('JS_LANG_InfoMessagesLoad', 'WebMail 正在加载邮件列表...');
define('JS_LANG_InfoEmptyFolder', '文件夹是空的');
define('JS_LANG_InfoPageLoading', '此页面仍然正加载...');
define('JS_LANG_InfoSendMessage', '邮件已发送');
define('JS_LANG_InfoSaveMessage', '邮件已保存');
define('JS_LANG_InfoHaveImported', '你已经导入');
define('JS_LANG_InfoNewContacts', '新联系进入你的联系人列表。');
define('JS_LANG_InfoToDelete', '删除');
define('JS_LANG_InfoDeleteContent', '请先删除文件夹里的内容。');
define('JS_LANG_InfoDeleteNotEmptyFolders', '不允许删除非空的文件夹，要删除文件夹 (已经禁用复选框)，请先删除里面的内容。');
define('JS_LANG_InfoRequiredFields', '* 必需字段');

define('JS_LANG_ConfirmAreYouSure', '确认操作？');
define('JS_LANG_ConfirmDirectModeAreYouSure', '已经选定的邮件将永久删除! 你确定要这样做吗？');
define('JS_LANG_ConfirmSaveSettings', '设置未保存，请点击 确定 保存。');
define('JS_LANG_ConfirmSaveContactsSettings', '联系人设置未保存，请点击 确定 保存。');
define('JS_LANG_ConfirmSaveAcctProp', '帐号属性未保存，请点击 确定 保存。');
define('JS_LANG_ConfirmSaveFilter', '过滤属性未保存，请点击 确定 保存。');
define('JS_LANG_ConfirmSaveSignature', '签名未保存，请点击 确定 保存。');
define('JS_LANG_ConfirmSavefolders', '文件夹未保存，请点击 确定 保存。');
define('JS_LANG_ConfirmHtmlToPlain', '注意: 把HTML格式的邮件改为纯文本后, 您将丢失当前的格式，点击 OK 继续。');
define('JS_LANG_ConfirmAddFolder', '要使 添加/移除 文件夹 生效，请点击 确定 保存。');
define('JS_LANG_ConfirmEmptySubject', '邮件主题是空的，要继续吗？');

define('JS_LANG_WarningEmailBlank', 'Email不能为空');
define('JS_LANG_WarningLoginBlank', 'Login不能为空');
define('JS_LANG_WarningToBlank', '收件人地址不为能为空');
define('JS_LANG_WarningServerPortBlank', 'POP3、SMTP、端口不能为空。');
define('JS_LANG_WarningEmptySearchLine', '搜索关键字不能为空。');
define('JS_LANG_WarningMarkListItem', '至少选择一个项目。');
define('JS_LANG_WarningFolderMove', '文件夹不能被移动，因为这是另一个级别。');
define('JS_LANG_WarningContactNotComplete', '请输入Email或名字。');
define('JS_LANG_WarningGroupNotComplete', '请输入组别名。');

define('JS_LANG_WarningEmailFieldBlank', 'Email不能为空。');
define('JS_LANG_WarningIncServerBlank', 'POP3(IMAP4) 服务器不能为空。');
define('JS_LANG_WarningIncPortBlank', 'POP3(IMAP4)服务器端口不能为空。');
define('JS_LANG_WarningIncLoginBlank', 'POP3(IMAP4)登陆名不能为空。');
define('JS_LANG_WarningIncPortNumber', 'POP3(IMAP4)端口应该为正数数字。');
define('JS_LANG_DefaultIncPortNumber', '默认POP3(IMAP4) 端口号是 110(143).');
define('JS_LANG_WarningIncPassBlank', 'POP3(IMAP4)密码不能为空');
define('JS_LANG_WarningOutPortBlank', 'SMTP服务器端口不能为空。');
define('JS_LANG_WarningOutPortNumber', 'SMTP端口应该为正数数字。');
define('JS_LANG_WarningCorrectEmail', '请输入正确的 e-mail.');
define('JS_LANG_DefaultOutPortNumber', '默认 SMTP 端口是 25.');

define('JS_LANG_WarningCsvExtention', '扩展名为 .csv');
define('JS_LANG_WarningImportFileType', '复制联系人，请选择相应的程序。');
define('JS_LANG_WarningEmptyImportFile', '请点“浏览...”选择文件。');

define('JS_LANG_WarningContactsPerPage', '联系人每页数字为正数');
define('JS_LANG_WarningMessagesPerPage', '邮件列表每页数字为正数');
define('JS_LANG_WarningMailsOnServerDays', '在服务器days字段，请指定一个正数在邮件里。');
define('JS_LANG_WarningEmptyFilter', '请输入字符');
define('JS_LANG_WarningEmptyFolderName', '请输入文件夹名');

define('JS_LANG_ErrorConnectionFailed', '连接失败');
define('JS_LANG_ErrorRequestFailed', '数据传送完成');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'XMLHttp 缺少请求对象');
define('JS_LANG_ErrorWithoutDesc', '错误没有描述');
define('JS_LANG_ErrorParsing', 'XML解析错误');
define('JS_LANG_ResponseText', '响应文字:');
define('JS_LANG_ErrorEmptyXmlPacket', 'XML 数据包为空');
define('JS_LANG_ErrorImportContacts', '导入联系人出错');
define('JS_LANG_ErrorNoContacts', '没有导入联系人');
define('JS_LANG_ErrorCheckMail', '接收邮件被终止，发生了一个错误，部分邮件可能未接收完。');

define('JS_LANG_LoggingToServer', '正在登陆服务器...');
define('JS_LANG_GettingMsgsNum', '正在获取邮件数...');
define('JS_LANG_RetrievingMessage', '恢复邮件');
define('JS_LANG_DeletingMessage', '正在删除邮件');
define('JS_LANG_DeletingMessages', '正在删除邮件');
define('JS_LANG_Of', 'of');
define('JS_LANG_Connection', '连接');
define('JS_LANG_Charset', '编码');
define('JS_LANG_AutoSelect', '自动选择');

define('JS_LANG_Contacts', '联系人');
define('JS_LANG_ClassicVersion', '传统模式');
define('JS_LANG_Logout', '退出');
define('JS_LANG_Settings', '设置');

define('JS_LANG_LookFor', '查找: ');
define('JS_LANG_SearchIn', '搜索: ');
define('JS_LANG_QuickSearch', '搜索 "发件人", "收件人" 和 "邮件主题" (更快).');
define('JS_LANG_SlowSearch', '搜索全部邮件');
define('JS_LANG_AllMailFolders', '所有邮件文件夹');
define('JS_LANG_AllGroups', '所有组别');

define('JS_LANG_NewMessage', '写邮件');
define('JS_LANG_CheckMail', '收邮件');
define('JS_LANG_EmptyTrash', '清空垃圾箱');
define('JS_LANG_MarkAsRead', '标记为已读');
define('JS_LANG_MarkAsUnread', '标记为未读');
define('JS_LANG_MarkFlag', '标记');
define('JS_LANG_MarkUnflag', '取消标记');
define('JS_LANG_MarkAllRead', '标记所有邮件为已读');
define('JS_LANG_MarkAllUnread', '标记所有邮件为未读');
define('JS_LANG_Reply', '回复');
define('JS_LANG_ReplyAll', '回复所有');
define('JS_LANG_Delete', '删除');
define('JS_LANG_Undelete', '恢复');
define('JS_LANG_PurgeDeleted', '清空已删除');
define('JS_LANG_MoveToFolder', '移动到');
define('JS_LANG_Forward', '转发');

define('JS_LANG_HideFolders', '隐藏文件夹');
define('JS_LANG_ShowFolders', '显示文件夹');
define('JS_LANG_ManageFolders', '管理文件夹');
define('JS_LANG_SyncFolder', '同步文件夹');
define('JS_LANG_NewMessages', '写邮件');
define('JS_LANG_Messages', '邮件');

define('JS_LANG_From', '发件人');
define('JS_LANG_To', '收件人');
define('JS_LANG_Date', '日期');
define('JS_LANG_Size', '大小');
define('JS_LANG_Subject', '主题');

define('JS_LANG_FirstPage', '首页');
define('JS_LANG_PreviousPage', '上一页');
define('JS_LANG_NextPage', '下一页');
define('JS_LANG_LastPage', '尾页');

define('JS_LANG_SwitchToPlain', '转到纯文本查看');
define('JS_LANG_SwitchToHTML', '转到HTML查看');
define('JS_LANG_AddToAddressBook', '添加联系人');
define('JS_LANG_ClickToDownload', '点击下载 ');
define('JS_LANG_View', 'View');
define('JS_LANG_ShowFullHeaders', '显示完整的邮件头');
define('JS_LANG_HideFullHeaders', '隐藏完整的邮件头');

define('JS_LANG_MessagesInFolder', '封邮件在此文件夹');
define('JS_LANG_YouUsing', '您正在使用');
define('JS_LANG_OfYour', '属于您的');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', '发送');
define('JS_LANG_SaveMessage', '保存');
define('JS_LANG_Print', '打印');
define('JS_LANG_PreviousMsg', '上一封');
define('JS_LANG_NextMsg', '下一封');
define('JS_LANG_AddressBook', '地址簿');
define('JS_LANG_ShowBCC', '添加密送');
define('JS_LANG_HideBCC', '删除密送');
define('JS_LANG_CC', '抄送');
define('JS_LANG_BCC', '密送地址');
define('JS_LANG_ReplyTo', '回复');
define('JS_LANG_AttachFile', '附件');
define('JS_LANG_Attach', '附件');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', '原始邮件');
define('JS_LANG_Sent', '已发送');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', '低');
define('JS_LANG_Normal', '中');
define('JS_LANG_High', '高');
define('JS_LANG_Importance', '优先级');
define('JS_LANG_Close', '关闭');

define('JS_LANG_Common', '常规设置');
define('JS_LANG_EmailAccounts', '邮件帐号');

define('JS_LANG_MsgsPerPage', '封/每页');
define('JS_LANG_DisableRTE', '禁用富文本编辑器');
define('JS_LANG_Skin', '皮肤');
define('JS_LANG_DefCharset', '默认编码');
define('JS_LANG_DefCharsetInc', '默认接收编码');
define('JS_LANG_DefCharsetOut', '默认发送编码');
define('JS_LANG_DefTimeOffset', '默认时区');
define('JS_LANG_DefLanguage', '默认语言');
define('JS_LANG_DefDateFormat', '默认日期格式');
define('JS_LANG_ShowViewPane', '邮件预览');
define('JS_LANG_Save', '保存');
define('JS_LANG_Cancel', '取消');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', '移除');
define('JS_LANG_AddNewAccount', '添加新帐号');
define('JS_LANG_Signature', '签名');
define('JS_LANG_Filters', '过滤');
define('JS_LANG_Properties', '属性');
define('JS_LANG_UseForLogin', '使用此帐号登陆');
define('JS_LANG_MailFriendlyName', '您的名字');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', '接收邮件');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', '端口');
define('JS_LANG_MailIncLogin', '登陆帐号');
define('JS_LANG_MailIncPass', '密码');
define('JS_LANG_MailOutHost', '发送邮件');
define('JS_LANG_MailOutPort', '端口');
define('JS_LANG_MailOutLogin', 'SMTP 帐号');
define('JS_LANG_MailOutPass', 'SMTP 密码');
define('JS_LANG_MailOutAuth1', '使用 SMTP 认证');
define('JS_LANG_MailOutAuth2', '(如果 SMTP 用户名及密码和 POP3/IMAP4 一样，SMTP参数可以留空)');
define('JS_LANG_UseFriendlyNm1', '在“发件人”一栏，请使用友好的名字');
define('JS_LANG_UseFriendlyNm2', '(您的名字 &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', '获取/同步邮件');
define('JS_LANG_MailMode0', '从服务器删除');
define('JS_LANG_MailMode1', '从服务器保留');
define('JS_LANG_MailMode2', '从服务器保留');
define('JS_LANG_MailsOnServerDays', '天');
define('JS_LANG_MailMode3', '删除垃圾邮件时从服务器也删除');
define('JS_LANG_InboxSyncType', '同步收件箱类型');

define('JS_LANG_SyncTypeNo', '不同步');
define('JS_LANG_SyncTypeNewHeaders', '新邮件头');
define('JS_LANG_SyncTypeAllHeaders', '所有邮件头');
define('JS_LANG_SyncTypeNewMessages', '新邮件');
define('JS_LANG_SyncTypeAllMessages', '所有邮件');
define('JS_LANG_SyncTypeDirectMode', '直接模式');

define('JS_LANG_Pop3SyncTypeEntireHeaders', '仅邮件头');
define('JS_LANG_Pop3SyncTypeEntireMessages', '完整邮件');
define('JS_LANG_Pop3SyncTypeDirectMode', '直接模式');

define('JS_LANG_DeleteFromDb', '从数据库删除服务器不存在的邮件');

define('JS_LANG_EditFilter', '编辑过滤');
define('JS_LANG_NewFilter', '添加过滤');
define('JS_LANG_Field', '字段');
define('JS_LANG_Condition', '条件');
define('JS_LANG_ContainSubstring', '包含字符');
define('JS_LANG_ContainExactPhrase', '包含短语');
define('JS_LANG_NotContainSubstring', '不含有字符');
define('JS_LANG_FilterDesc_At', '在');
define('JS_LANG_FilterDesc_Field', '字段');
define('JS_LANG_Action', '操作');
define('JS_LANG_DoNothing', '什么也不做');
define('JS_LANG_DeleteFromServer', '直接从服务器删除');
define('JS_LANG_MarkGrey', '标记为灰色');
define('JS_LANG_Add', '添加');
define('JS_LANG_OtherFilterSettings', '其他过滤设置');
define('JS_LANG_ConsiderXSpam', '考虑 X-Spam 邮件头');
define('JS_LANG_Apply', '应用');

define('JS_LANG_InsertLink', '插入链接');
define('JS_LANG_RemoveLink', '移除链接');
define('JS_LANG_Numbering', '编号');
define('JS_LANG_Bullets', '小圆球');
define('JS_LANG_HorizontalLine', '水平线');
define('JS_LANG_Bold', '粗体');
define('JS_LANG_Italic', '斜体');
define('JS_LANG_Underline', '下划线');
define('JS_LANG_AlignLeft', '左对齐');
define('JS_LANG_Center', '居中');
define('JS_LANG_AlignRight', '右对齐');
define('JS_LANG_Justify', '对齐');
define('JS_LANG_FontColor', '字体颜色');
define('JS_LANG_Background', '背景');
define('JS_LANG_SwitchToPlainMode', '转到文本模式');
define('JS_LANG_SwitchToHTMLMode', '转到HTML模式');

define('JS_LANG_Folder', '文件夹');
define('JS_LANG_Msgs', '邮件数');
define('JS_LANG_Synchronize', '同步');
define('JS_LANG_ShowThisFolder', '显示');
define('JS_LANG_Total', '总计');
define('JS_LANG_DeleteSelected', '删除已选');
define('JS_LANG_AddNewFolder', '新建文件夹');
define('JS_LANG_NewFolder', '新文件夹');
define('JS_LANG_ParentFolder', '父文件夹');
define('JS_LANG_NoParent', '没有父文件夹');
define('JS_LANG_FolderName', '文件夹名');

define('JS_LANG_ContactsPerPage', '每页显示联系人');
define('JS_LANG_WhiteList', '白名单地址');

define('JS_LANG_CharsetDefault', '默认');
define('JS_LANG_CharsetArabicAlphabetISO', '阿拉伯文 (ISO)');
define('JS_LANG_CharsetArabicAlphabet', '阿拉伯文 (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', '波罗的海文 (ISO)');
define('JS_LANG_CharsetBalticAlphabet', '波罗的海文 (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', '中欧 (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', '中欧 (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', '简体中文 (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', '简体中文 (GB2312)');
define('JS_LANG_CharsetChineseTraditional', '繁体中文 (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', '西里尔文 (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', '西里尔文 (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', '西里尔文 (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', '希腊文 (ISO)');
define('JS_LANG_CharsetGreekAlphabet', '希腊文 (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', '希伯来文 (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', '希伯来文 (Windows)');
define('JS_LANG_CharsetJapanese', '日本');
define('JS_LANG_CharsetJapaneseShiftJIS', '日本 (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', '韩文 (EUC)');
define('JS_LANG_CharsetKoreanISO', '韩文 (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', '拉丁文 (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', '土耳其文');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Unicode (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Unicode (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', '越南文 (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', '西欧 (ISO)');
define('JS_LANG_CharsetWesternAlphabet', '西欧 (Windows)');

define('JS_LANG_TimeDefault', '默认');
define('JS_LANG_TimeEniwetok', 'Eniwetok, Kwajalein, Dateline Time');
define('JS_LANG_TimeMidwayIsland', 'Midway Island, Samoa');
define('JS_LANG_TimeHawaii', 'Hawaii');
define('JS_LANG_TimeAlaska', 'Alaska');
define('JS_LANG_TimePacific', 'Pacific Time (US & Canada); Tijuana');
define('JS_LANG_TimeArizona', 'Arizona');
define('JS_LANG_TimeMountain', 'Mountain Time (US & Canada)');
define('JS_LANG_TimeCentralAmerica', 'Central America');
define('JS_LANG_TimeCentral', 'Central Time (US & Canada)');
define('JS_LANG_TimeMexicoCity', 'Mexico City, Tegucigalpa');
define('JS_LANG_TimeSaskatchewan', 'Saskatchewan');
define('JS_LANG_TimeIndiana', 'Indiana (East)');
define('JS_LANG_TimeEastern', 'Eastern Time (US & Canada)');
define('JS_LANG_TimeBogota', 'Bogota, Lima, Quito');
define('JS_LANG_TimeSantiago', 'Santiago');
define('JS_LANG_TimeCaracas', 'Caracas, La Paz');
define('JS_LANG_TimeAtlanticCanada', 'Atlantic Time (Canada)');
define('JS_LANG_TimeNewfoundland', 'Newfoundland');
define('JS_LANG_TimeGreenland', 'Greenland');
define('JS_LANG_TimeBuenosAires', 'Buenos Aires, Georgetown');
define('JS_LANG_TimeBrasilia', 'Brasilia');
define('JS_LANG_TimeMidAtlantic', 'Mid-Atlantic');
define('JS_LANG_TimeCapeVerde', 'Cape Verde Is.');
define('JS_LANG_TimeAzores', 'Azores');
define('JS_LANG_TimeMonrovia', 'Casablanca, Monrovia');
define('JS_LANG_TimeGMT', 'Dublin, Edinburgh, Lisbon, London');
define('JS_LANG_TimeBerlin', 'Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna');
define('JS_LANG_TimePrague', 'Belgrade, Bratislava, Budapest, Ljubljana, Prague');
define('JS_LANG_TimeParis', 'Brussels, Copenhagen, Madrid, Paris');
define('JS_LANG_TimeSarajevo', 'Sarajevo, Skopje, Sofija, Warsaw, Zagreb');
define('JS_LANG_TimeWestCentralAfrica', 'West Central Africa');
define('JS_LANG_TimeAthens', 'Athens, Istanbul, Minsk');
define('JS_LANG_TimeEasternEurope', 'Bucharest');
define('JS_LANG_TimeCairo', 'Cairo');
define('JS_LANG_TimeHarare', 'Harare, Pretoria');
define('JS_LANG_TimeHelsinki', 'Helsinki, Riga, Tallinn, Vilnius');
define('JS_LANG_TimeIsrael', 'Israel, Jerusalem Standard Time');
define('JS_LANG_TimeBaghdad', 'Baghdad');
define('JS_LANG_TimeArab', 'Arab, Kuwait, Riyadh');
define('JS_LANG_TimeMoscow', 'Moscow, St. Petersburg, Volgograd');
define('JS_LANG_TimeEastAfrica', 'East Africa, Nairobi');
define('JS_LANG_TimeTehran', 'Tehran');
define('JS_LANG_TimeAbuDhabi', 'Abu Dhabi, Muscat');
define('JS_LANG_TimeCaucasus', 'Baku, Tbilisi, Yerevan');
define('JS_LANG_TimeKabul', 'Kabul');
define('JS_LANG_TimeEkaterinburg', 'Ekaterinburg');
define('JS_LANG_TimeIslamabad', 'Islamabad, Karachi, Sverdlovsk, Tashkent');
define('JS_LANG_TimeBombay', 'Calcutta, Chennai, Mumbai, New Delhi, India Standard Time');
define('JS_LANG_TimeNepal', 'Kathmandu, Nepal');
define('JS_LANG_TimeAlmaty', 'Almaty, North Central Asia');
define('JS_LANG_TimeDhaka', 'Astana, Dhaka');
define('JS_LANG_TimeSriLanka', 'Sri Jayawardenepura, Sri Lanka');
define('JS_LANG_TimeRangoon', 'Rangoon');
define('JS_LANG_TimeBangkok', 'Bangkok, Novosibirsk, Hanoi, Jakarta');
define('JS_LANG_TimeKrasnoyarsk', 'Krasnoyarsk');
define('JS_LANG_TimeBeijing', '北京, Chongqing, Hong Kong SAR, Urumqi');
define('JS_LANG_TimeUlaanBataar', 'Ulaan Bataar');
define('JS_LANG_TimeSingapore', 'Kuala Lumpur, Singapore');
define('JS_LANG_TimePerth', 'Perth, Western Australia');
define('JS_LANG_TimeTaipei', 'Taipei');
define('JS_LANG_TimeTokyo', 'Osaka, Sapporo, Tokyo, Irkutsk');
define('JS_LANG_TimeSeoul', 'Seoul, Korea Standard time');
define('JS_LANG_TimeYakutsk', 'Yakutsk');
define('JS_LANG_TimeAdelaide', 'Adelaide, Central Australia');
define('JS_LANG_TimeDarwin', 'Darwin');
define('JS_LANG_TimeBrisbane', 'Brisbane, East Australia');
define('JS_LANG_TimeSydney', 'Canberra, Melbourne, Sydney, Hobart');
define('JS_LANG_TimeGuam', 'Guam, Port Moresby');
define('JS_LANG_TimeHobart', 'Hobart, Tasmania');
define('JS_LANG_TimeVladivostock', 'Vladivostok');
define('JS_LANG_TimeSolomonIs', 'Solomon Is., New Caledonia');
define('JS_LANG_TimeWellington', 'Auckland, Wellington, Magadan');
define('JS_LANG_TimeFiji', 'Fiji Islands, Kamchatka, Marshall Is.');
define('JS_LANG_TimeTonga', 'Nuku\'alofa, Tonga');

define('JS_LANG_DateDefault', 'Default');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Month (01 Jan)');
define('JS_LANG_DateAdvanced', '高级选项');

define('JS_LANG_NewContact', '新建联系人');
define('JS_LANG_NewGroup', '新建组');
define('JS_LANG_AddContactsTo', '新建联系人在');
define('JS_LANG_ImportContacts', '导入联系人');

define('JS_LANG_Name', '名字');
define('JS_LANG_Email', 'Email');
define('JS_LANG_DefaultEmail', '默认 Email');
define('JS_LANG_NotSpecifiedYet', '未指定');
define('JS_LANG_ContactName', '名字');
define('JS_LANG_Birthday', '生日');
define('JS_LANG_Month', '月');
define('JS_LANG_January', 'January');
define('JS_LANG_February', 'February');
define('JS_LANG_March', 'March');
define('JS_LANG_April', 'April');
define('JS_LANG_May', 'May');
define('JS_LANG_June', 'June');
define('JS_LANG_July', 'July');
define('JS_LANG_August', 'August');
define('JS_LANG_September', 'September');
define('JS_LANG_October', 'October');
define('JS_LANG_November', 'November');
define('JS_LANG_December', 'December');
define('JS_LANG_Day', '日');
define('JS_LANG_Year', '年');
define('JS_LANG_UseFriendlyName1', '使用友好名字');
define('JS_LANG_UseFriendlyName2', '(如, 阿呆 &lt;johndoe@mail.com&gt;)');
define('JS_LANG_Personal', '个人');
define('JS_LANG_PersonalEmail', '个人 E-mail');
define('JS_LANG_StreetAddress', '街道');
define('JS_LANG_City', '城市');
define('JS_LANG_Fax', '传真');
define('JS_LANG_StateProvince', '省/自治区');
define('JS_LANG_Phone', '电话');
define('JS_LANG_ZipCode', '邮编');
define('JS_LANG_Mobile', '手机');
define('JS_LANG_CountryRegion', '国家/地区');
define('JS_LANG_WebPage', '网址');
define('JS_LANG_Go', 'Go');
define('JS_LANG_Home', '个人信息');
define('JS_LANG_Business', '业务联系');
define('JS_LANG_BusinessEmail', '业务 E-mail');
define('JS_LANG_Company', '公司');
define('JS_LANG_JobTitle', '职位');
define('JS_LANG_Department', '部门');
define('JS_LANG_Office', '办公室');
define('JS_LANG_Pager', '呼机');
define('JS_LANG_Other', '其他');
define('JS_LANG_OtherEmail', '其他邮件');
define('JS_LANG_Notes', '备注');
define('JS_LANG_Groups', '组别');
define('JS_LANG_ShowAddFields', '显示附加信息');
define('JS_LANG_HideAddFields', '隐藏附加信息');
define('JS_LANG_EditContact', '编辑联系信息');
define('JS_LANG_GroupName', '组名');
define('JS_LANG_AddContacts', '添加联系人');
define('JS_LANG_CommentAddContacts', '(多个地址用逗号,分开)');
define('JS_LANG_CreateGroup', '创建分组');
define('JS_LANG_Rename', '重新命名');
define('JS_LANG_MailGroup', '邮件组');
define('JS_LANG_RemoveFromGroup', '从分组中移除');
define('JS_LANG_UseImportTo', '从Outlook导入联系人。');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', '选择文件 (.CSV 格式)');
define('JS_LANG_Import', '导入');
define('JS_LANG_ContactsMessage', '这是联系人页面!!!');
define('JS_LANG_ContactsCount', '联系人');
define('JS_LANG_GroupsCount', '组别');

// webmail 4.1 constants
define('PicturesBlocked', '为了安全，此邮件里的图片已经被阻止');
define('ShowPictures', '显示图片');
define('ShowPicturesFromSender', '总是显示该发件人的邮件中的图片');
define('AlwaysShowPictures', '总是显示邮件中的图片');

define('TreatAsOrganization', '作为一个团体');

define('WarningGroupAlreadyExist', '这个组名已经存在，请指定另一个名字。');
define('WarningCorrectFolderName', '请指定一个正确的文件夹名字。');
define('WarningLoginFieldBlank', '登陆名不能为空。');
define('WarningCorrectLogin', '请指定一个正确的登陆名');
define('WarningPassBlank', '密码不能为空。');
define('WarningCorrectIncServer', '请指定一个 POP3(IMAP) 帐号。');
define('WarningCorrectSMTPServer', '请指定一个正确的发邮件帐号。');
define('WarningFromBlank', '发件人不能为空。');
define('WarningAdvancedDateFormat', '请指定一个日期/时间格式');

define('AdvancedDateHelpTitle', '日期（高级）');
define('AdvancedDateHelpIntro', '选择“高级选项”时, 您可以使用文本框设置您的日期格式, 建议不要在此使用. 格式 \':\' or \'/\' 定界符 char:');
define('AdvancedDateHelpConclusion', '例如, 如果为 &quot;mm/dd/yyyy&quot;（高级选项）, 显示为： month/day/year (i.e. 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', '月中此曰 (1 - 31)');
define('AdvancedDateHelpNumericMonth', '月 (1 - 12)');
define('AdvancedDateHelpTextualMonth', '月 (Jan - Dec)');
define('AdvancedDateHelpYear2', '年, 2 两位');
define('AdvancedDateHelpYear4', '年, 4 四位');
define('AdvancedDateHelpDayOfYear', '年中此日 (1 - 366)');
define('AdvancedDateHelpQuarter', '季度');
define('AdvancedDateHelpDayOfWeek', '一周内每天 (Mon - Sun)');
define('AdvancedDateHelpWeekOfYear', '全年的第几周 (1 - 53)');

define('InfoNoMessagesFound', '没有找到邮件。');
define('ErrorSMTPConnect', '无法连接到SMTP服务器，请检查SMTP设置。');
define('ErrorSMTPAuth', '验证失败，用户名或密码错误。');
define('ReportMessageSent', '邮件已经发送。');
define('ReportMessageSaved', '邮件已经保存。');
define('ErrorPOP3Connect', '无法连接POP3服务器，请检查POP3设置。');
define('ErrorIMAP4Connect', '无法连接 IMAP4 服务器, 请检查 IMAP4 设置。');
define('ErrorPOP3IMAP4Auth', '验证失败，错误的登陆名或密码。');
define('ErrorGetMailLimit', '对不起，您的邮箱大小超出限制。');

define('ReportSettingsUpdatedSuccessfuly', '设置更新成功。');
define('ReportAccountCreatedSuccessfuly', '帐号创建成功。');
define('ReportAccountUpdatedSuccessfuly', '帐号更新成功。');
define('ConfirmDeleteAccount', '确认要删除此帐号吗？');
define('ReportFiltersUpdatedSuccessfuly', '过滤器更新成功。');
define('ReportSignatureUpdatedSuccessfuly', '签名更新成功。');
define('ReportFoldersUpdatedSuccessfuly', '过滤器更新成功');
define('ReportContactsSettingsUpdatedSuccessfuly', '联系人设置成功。');

define('ErrorInvalidCSV', '您选择的CSV格式文件无效。');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', '组别');
define('ReportGroupSuccessfulyAdded2', '添加成功。');
define('ReportGroupUpdatedSuccessfuly', '级别更新成功。');
define('ReportContactSuccessfulyAdded', '联系人添加成功。');
define('ReportContactUpdatedSuccessfuly', '联系人更新成功。');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', '联系人已经添加到组。');
define('AlertNoContactsGroupsSelected', '没有选择联系人或组。');

define('InfoListNotContainAddress', '如果在列表中找不到包含的地址，请尝试输入第一个字符。');

define('DirectAccess', 'D');
define('DirectAccessTitle', '直接模式， WebMail 直接访问邮件服务器。');

define('FolderInbox', '收件箱');
define('FolderSentItems', '已发送');
define('FolderDrafts', '草稿箱');
define('FolderTrash', '已删除');

define('FileLargerAttachment', '文件大小已超过附件大小限制。');
define('FilePartiallyUploaded', '由于一个未知错误，文件只上传了一部分。');
define('NoFileUploaded', '文件没有上传。');
define('MissingTempFolder', '缺少临时文件夹。');
define('MissingTempFile', '缺少临时文件夹。');
define('UnknownUploadError', '发生未知文件上传错误。');
define('FileLargerThan', '文件上传时发生错误，可能是您上传的文件过大。');
define('PROC_CANT_LOAD_DB', '无法连接到数据库。');
define('PROC_CANT_LOAD_LANG', '没有找到必需的语言配置文件。');
define('PROC_CANT_LOAD_ACCT', '此帐号不存在，可能是被删除了。');

define('DomainDosntExist', '服务器上不存在此域名。');
define('ServerIsDisable', '被管理员禁止使用域名服务器。');

define('PROC_ACCOUNT_EXISTS', '帐号创建失败，此帐号已有人使用。');
define('PROC_CANT_GET_MESSAGES_COUNT', '无法获取邮件数量。');
define('PROC_CANT_MAIL_SIZE', '无法获取邮件大小。');

define('Organization', '团体机构');
define('WarningOutServerBlank', '发邮件字段不能为空');

define('JS_LANG_Refresh', '刷新');
define('JS_LANG_MessagesInInbox', '邮件收件箱');
define('JS_LANG_InfoEmptyInbox', '收件箱为空');

// webmail 4.2 constants
define('BackToList', '返回');
define('InfoNoContactsGroups', '没有联系人或组。');
define('InfoNewContactsGroups', '您可在新建联系人/组或者从Outlook导入.CSV 格式文件');
define('DefTimeFormat', '默认时间格式');
define('SpellNoSuggestions', '没有建议');
define('SpellWait', '请稍等...');

define('InfoNoMessageSelected', '没有选择邮件。');
define('InfoSingleDoubleClick', '你可以在列表中单击邮件进行预览，或者双击查看完整的内容。');
// calendar
define('TitleDay', '查看一天');
define('TitleWeek', '查看一周');
define('TitleMonth', '查看一个月');

define('ErrorNotSupportBrowser', 'Webmail日期不支持您的浏览器，请使用FireFox 2.0 或更高版本, Opera 9.0 或更高版本, Internet Explorer 6.0 或更高版本, Safari 3.0.2 或更高版本。');
define('ErrorTurnedOffActiveX', 'ActiveX 被关闭 . <br/>请转到相应的程序。');

define('Calendar', '日程');

define('TabDay', '日');
define('TabWeek', '周');
define('TabMonth', '月');

define('ToolNewEvent', '新事件');
define('ToolBack', '返回');
define('ToolToday', '今日');
define('AltNewEvent', '新事件');
define('AltBack', '返回');
define('AltToday', '今日');
define('CalendarHeader', '日程');
define('CalendarsManager', '日程管理');

define('CalendarActionNew', '新日程');
define('EventHeaderNew', '新事件');
define('CalendarHeaderNew', '新日程');

define('EventSubject', '主题');
define('EventCalendar', '日程');
define('EventFrom', '发件人');
define('EventTill', '抽屉');
define('CalendarDescription', '描述');
define('CalendarColor', '颜色');
define('CalendarName', '日程名称');
define('CalendarDefaultName', '我的日程');

define('ButtonSave', '保存');
define('ButtonCancel', '取消');
define('ButtonDelete', '删除');

define('AltPrevMonth', '上一月');
define('AltNextMonth', '下一月');

define('CalendarHeaderEdit', '编辑日程');
define('CalendarActionEdit', '编辑日程');
define('ConfirmDeleteCalendar', '确定要删除此日程吗？');
define('InfoDeleting', '正在删除...');
define('WarningCalendarNameBlank', '日程名称不能为空');
define('ErrorCalendarNotCreated', '日程没有创建');
define('WarningSubjectBlank', '主题不能为空。');
define('WarningIncorrectTime', '时间不能包含非法字符。');
define('WarningIncorrectFromTime', '错误的发件人时间');
define('WarningIncorrectTillTime', '抽屉时间错误');
define('WarningStartEndDate', '结束时间必须大于开始时间。');
define('WarningStartEndTime', '结束时间必须大于开始时间。');
define('WarningIncorrectDate', '请填写正确的日期。');
define('InfoLoading', '正在加载...');
define('EventCreate', '新建事件');
define('CalendarHideOther', '隐藏其他日程');
define('CalendarShowOther', '显示其他日程');
define('CalendarRemove', '移除日程');
define('EventHeaderEdit', '编辑事件');

define('InfoSaving', '正在保存...');
define('SettingsDisplayName', '显示名称');
define('SettingsTimeFormat', '时间格式');
define('SettingsDateFormat', '日期格式');
define('SettingsShowWeekends', '显示周末');
define('SettingsWorkdayStarts', '工作日开始');
define('SettingsWorkdayEnds', '结束');
define('SettingsShowWorkday', '显示工作日');
define('SettingsWeekStartsOn', '每星期启动');
define('SettingsDefaultTab', '默认标签');
define('SettingsCountry', '国家');
define('SettingsTimeZone', '时区');
define('SettingsAllTimeZones', '所有时区');

define('WarningWorkdayStartsEnds', ' \'工作日结束\' 必须大于 \'节假日开始\' 时间');
define('ReportSettingsUpdated', '设置更新成功。');

define('SettingsTabCalendar', '日程');

define('FullMonthJanuary', 'January');
define('FullMonthFebruary', 'February');
define('FullMonthMarch', 'March');
define('FullMonthApril', 'April');
define('FullMonthMay', 'May');
define('FullMonthJune', 'June');
define('FullMonthJuly', 'July');
define('FullMonthAugust', 'August');
define('FullMonthSeptember', 'September');
define('FullMonthOctober', 'October');
define('FullMonthNovember', 'November');
define('FullMonthDecember', 'December');

define('ShortMonthJanuary', 'Jan');
define('ShortMonthFebruary', 'Feb');
define('ShortMonthMarch', 'Mar');
define('ShortMonthApril', 'Apr');
define('ShortMonthMay', 'May');
define('ShortMonthJune', 'Jun');
define('ShortMonthJuly', 'Jul');
define('ShortMonthAugust', 'Aug');
define('ShortMonthSeptember', 'Sep');
define('ShortMonthOctober', 'Oct');
define('ShortMonthNovember', 'Nov');
define('ShortMonthDecember', 'Dec');

define('FullDayMonday', '星期一');
define('FullDayTuesday', '星期二');
define('FullDayWednesday', '星期三');
define('FullDayThursday', '星期四');
define('FullDayFriday', '星期五');
define('FullDaySaturday', '星期六');
define('FullDaySunday', '星期日');

define('DayToolMonday', 'Mon');
define('DayToolTuesday', 'Tue');
define('DayToolWednesday', 'Wed');
define('DayToolThursday', 'Thu');
define('DayToolFriday', 'Fri');
define('DayToolSaturday', 'Sat');
define('DayToolSunday', 'Sun');

define('CalendarTableDayMonday', 'M');
define('CalendarTableDayTuesday', 'T');
define('CalendarTableDayWednesday', 'W');
define('CalendarTableDayThursday', 'T');
define('CalendarTableDayFriday', 'F');
define('CalendarTableDaySaturday', 'S');
define('CalendarTableDaySunday', 'S');

define('ErrorParseJSON', '服务器无法解析JSON响应。');

define('ErrorLoadCalendar', '无法加载日程表');
define('ErrorLoadEvents', '无法加载事件表');
define('ErrorUpdateEvent', '无法保存事件');
define('ErrorDeleteEvent', '无法删除事件');
define('ErrorUpdateCalendar', '无法保存日程');
define('ErrorDeleteCalendar', '无法删除日程');
define('ErrorGeneral', '服务器发生错误，请一会再试。');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', '分享日程表');
define('ShareActionEdit', '分享日程表');
define('CalendarPublicate', '所有人可以看到此日程表');
define('CalendarPublicationLink', '连接');
define('ShareCalendar', '分享此日程表');
define('SharePermission1', '可以更改和管理分享');
define('SharePermission2', '可以更改事件');
define('SharePermission3', '能看所有详细事件');
define('SharePermission4', '只可以看 free/busy (隐藏详细)');
define('ButtonClose', '关闭');
define('WarningEmailFieldFilling', '请先输入 e-mail ');
define('EventHeaderView', '查看事件');
define('ErrorUpdateSharing', '无法保存分享和发表数据');
define('ErrorUpdateSharing1', '不能分享给 %s 用户，那是无效的。');
define('ErrorUpdateSharing2', '不可能分享此日程表给用户 %s');
define('ErrorUpdateSharing3', '此日程表已经分享给用户 %s');
define('Title_MyCalendars', '我的日程表');
define('Title_SharedCalendars', '分享日程表');
define('ErrorGetPublicationHash', '无法建立发布连接');
define('ErrorGetSharing', '无法添加分享');
define('CalendarPublishedTitle', '此日程表已经发布');
define('RefreshSharedCalendars', '刷新分享日程表');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', '成员');

define('ReportMessagePartDisplayed', '注意：这只是显示邮件的一部分。');
define('ReportViewEntireMessage', '查看完整的邮件');
define('ReportClickHere', '点击这里');
define('ErrorContactExists', '此联系人的 e-mail 已经存在。');

define('Attachments', '附件');

define('InfoGroupsOfContact', '选中标记的联系人已经被标记为这个组的成员。');
define('AlertNoContactsSelected', '没有选择联系人。');
define('MailSelected', '邮箱已选择地址');
define('CaptionSubscribed', '订阅');

define('OperationSpam', '垃圾邮件');
define('OperationNotSpam', '不是垃圾邮件');
define('FolderSpam', '垃圾邮件');

// webmail 4.4 contacts
define('ContactMail', '邮件联系人');
define('ContactViewAllMails', '查看此联系人的所有邮件');
define('ContactsMailThem', '给他们发邮件');
define('DateToday', '今天');
define('DateYesterday', '昨天');
define('MessageShowDetails', '查看详细');
define('MessageHideDetails', '隐藏详细');
define('MessageNoSubject', '没有主题');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', '发给');
define('SearchClear', '清空搜索');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', ' "#s" 在文件夹 #f 的搜索结果:');
define('SearchResultsInAllFolders', ' "#s" 在所有邮箱文件夹的搜索结果:');
define('AutoresponderTitle', '自动回复');
define('AutoresponderEnable', '启用自动回复');
define('AutoresponderSubject', '主题');
define('AutoresponderMessage', '邮件');
define('ReportAutoresponderUpdatedSuccessfuly', '自动回复设置更新成功');
define('FolderQuarantine', '隔离');

//calendar
define('EventRepeats', '重复');
define('NoRepeats', '不重复');
define('DailyRepeats', '每天');
define('WorkdayRepeats', '每个工作日 (Mon. - Fri.)');
define('OddDayRepeats', '每个 Mon., Wed. and Fri.');
define('EvenDayRepeats', '每个 Tues. and Thurs.');
define('WeeklyRepeats', '每周');
define('MonthlyRepeats', '每月');
define('YearlyRepeats', '每年');
define('RepeatsEvery', '重复每个');
define('ThisInstance', '仅一次');
define('AllEvents', '所有事件系列');
define('AllFollowing', '所有下列的');
define('ConfirmEditRepeatEvent', '你想更改这一系列事件吗？');
define('RepeatEventHeaderEdit', '编辑循环事件');
define('First', '第一个');
define('Second', '第二个');
define('Third', '第三个');
define('Fourth', '第四个');
define('Last', '最后一个');
define('Every', '每个');
define('SetRepeatEventEnd', '设置终止日期');
define('NoEndRepeatEvent', '没有终止日期');
define('EndRepeatEventAfter', '终结');
define('Occurrences', '事件');
define('EndRepeatEventBy', '结束');
define('EventCommonDataTab', '主参数');
define('EventRepeatDataTab', '循环参数');
define('RepeatEventNotPartOfASeries', '此事件已经改变且不再是系列中的一部分');
define('UndoRepeatExclusion', '取消改变为包含在此系列');

define('MonthMoreLink', '%d 更多...');
define('NoNewSharedCalendars', '没有新日程');
define('NNewSharedCalendars', '找到 %d 个新日程');
define('OneNewSharedCalendars', '找到 1 个新日程');
define('ConfirmUndoOneRepeat', '你想恢复这个事件吗？');

define('RepeatEveryDayInfin', '每天');
define('RepeatEveryDayTimes', '每天, %TIMES% 次');
define('RepeatEveryDayUntil', '每天, 直到 %UNTIL%');
define('RepeatDaysInfin', '每 %PERIOD% 天');
define('RepeatDaysTimes', '每 %PERIOD% 天, %TIMES% 次');
define('RepeatDaysUntil', '每 %PERIOD% 天, 直到 %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', '每周工作日');
define('RepeatEveryWeekWeekdaysTimes', '每周工作日, %TIMES% 次');
define('RepeatEveryWeekWeekdaysUntil', '每周工作日, 直到 %UNTIL%');
define('RepeatWeeksWeekdaysInfin', '每 %PERIOD% 周，在工作日');
define('RepeatWeeksWeekdaysTimes', '每 %PERIOD% 周，在工作日, %TIMES% 次');
define('RepeatWeeksWeekdaysUntil', '每 %PERIOD% 周，在工作日, 直到 %UNTIL%');

define('RepeatEveryWeekInfin', '每周 %DAYS%');
define('RepeatEveryWeekTimes', '每周 %DAYS%, %TIMES% 次');
define('RepeatEveryWeekUntil', '每周 %DAYS%, 直到 %UNTIL%');
define('RepeatWeeksInfin', '每 %PERIOD% 周 %DAYS%');
define('RepeatWeeksTimes', '每 %PERIOD% 周 %DAYS%, %TIMES% 次');
define('RepeatWeeksUntil', '每 %PERIOD% 周 %DAYS%, 直到 %UNTIL%');

define('RepeatEveryMonthDateInfin', '每月 %DATE% 日');
define('RepeatEveryMonthDateTimes', '每月 %DATE% 日, %TIMES% 次');
define('RepeatEveryMonthDateUntil', '每月 %DATE% 日, 直到 %UNTIL%');
define('RepeatMonthsDateInfin', '每 %PERIOD% months on day %DATE%');
define('RepeatMonthsDateTimes', '每 %PERIOD% months on day %DATE%, %TIMES% times');
define('RepeatMonthsDateUntil', '每 %PERIOD% months on day %DATE%, 直到 %UNTIL%');

define('RepeatEveryMonthWDInfin', '每月 %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', '每月 %NUMBER% %DAY%, %TIMES% 次');
define('RepeatEveryMonthWDUntil', '每月 %NUMBER% %DAY%, 直到 %UNTIL%');
define('RepeatMonthsWDInfin', '每 %PERIOD% 月 %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', '每 %PERIOD% 月 %NUMBER% %DAY%, %TIMES% 次');
define('RepeatMonthsWDUntil', '每 %PERIOD% 月 %NUMBER% %DAY%, 直到 %UNTIL%');

define('RepeatEveryYearDateInfin', '每年 %DATE%');
define('RepeatEveryYearDateTimes', '每年 %DATE%, %TIMES% 次');
define('RepeatEveryYearDateUntil', '每年 %DATE%, 直到 %UNTIL%');
define('RepeatYearsDateInfin', '每 %PERIOD% 年 %DATE%');
define('RepeatYearsDateTimes', '每 %PERIOD% 年 %DATE%, %TIMES% 次');
define('RepeatYearsDateUntil', '每 %PERIOD% 年 %DATE%, 直到 %UNTIL%');

define('RepeatEveryYearWDInfin', '每年 %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', '每年 %NUMBER% %DAY%, %TIMES% 次');
define('RepeatEveryYearWDUntil', '每年 %NUMBER% %DAY%, 直到 %UNTIL%');
define('RepeatYearsWDInfin', '每 %PERIOD% 年%NUMBER% %DAY%');
define('RepeatYearsWDTimes', '每 %PERIOD% 年 %NUMBER% %DAY%, %TIMES% 次');
define('RepeatYearsWDUntil', '每 %PERIOD% 年 %NUMBER% %DAY%, 直到 %UNTIL%');

define('RepeatDescDay', '天');
define('RepeatDescWeek', '周');
define('RepeatDescMonth', '月');
define('RepeatDescYear', '年');

// webmail 4.5 contacts
define('WarningUntilDateBlank', '请指定结束循环日期');
define('WarningWrongUntilDate', '结束循环日期必须大于开始循环日期');

define('OnDays', '那一天');
define('CancelRecurrence', '取消循环');
define('RepeatEvent', '重复此事件');

define('Spellcheck', '检查拼写');
define('LoginLanguage', '语言');
define('LanguageDefault', '默认');

// webmail 4.5.x new
define('EmptySpam', '清空垃圾邮件');
define('Saving', '正在保存...');
define('Sending', '正在发送...');
define('LoggingOffFromServer', '正从服务器退出...');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', '不能标记为垃圾邮件');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', '不能标记为非垃圾邮件');
define('ExportToICalendar', '导出日程');
define('ErrorMaximumUsersLicenseIsExceeded', '您的用户数已超出限定范围。');
define('RepliedMessageTitle', '已回复');
define('ForwardedMessageTitle', '已转发');
define('RepliedForwardedMessageTitle', '已回复和转发');
define('ErrorDomainExist', '创建失败，用户相应的域名还没有添加，请先添加域名。');

// webmail 4.7
define('RequestReadConfirmation', '已读回执');
define('FolderTypeDefault', '默认');
define('ShowFoldersMapping', '使用另一个和系统文件夹一样的文件夹 (e.g. use MyFolder as Sent Items)');
define('ShowFoldersMappingNote', '例如, 更新“已发送”为我的“新文件夹”,  "已发送"指定到“我的文件夹”里面。');
define('FolderTypeMapTo', '用于');

define('ReminderEmailExplanation', '此邮件涉及到您的帐号 %EMAIL% ,因您已经安排好事件通知在你的日程： %CALENDAR_NAME%');
define('ReminderOpenCalendar', '打开日程表');

define('AddReminder', '提供');
define('AddReminderBefore', '在此事件之前提醒我 % ');
define('AddReminderAnd', '且 % 之前');
define('AddReminderAlso', '且在 % 之前');
define('AddMoreReminder', '更多提醒');
define('RemoveAllReminders', '移除所有提醒');
define('ReminderNone', '无');
define('ReminderMinutes', '分');
define('ReminderHour', '时');
define('ReminderHours', '时');
define('ReminderDay', '天');
define('ReminderDays', '天');
define('ReminderWeek', '周');
define('ReminderWeeks', '周');
define('Allday', '全天');

define('Folders', '文件夹');
define('NoSubject', '没有主题');
define('SearchResultsFor', '搜索结果');

define('Back', '返回');
define('Next', '下一个');
define('Prev', '前一个');

define('MsgList', '邮件');
define('Use24HTimeFormat', '使用24小时格式');
define('UseCalendars', '使用日程表');
define('Event', '事件');
define('CalendarSettingsNullLine', '没有日程');
define('CalendarEventNullLine', '没有事件');
define('ChangeAccount', '更改帐号');

define('TitleCalendar', '日程表');
define('TitleEvent', '事件');
define('TitleFolders', '文件夹');
define('TitleConfirmation', '确认');

define('Yes', '是');
define('No', '否');

define('EditMessage', '新邮件');

define('AccountNewPassword', '新密码');
define('AccountConfirmNewPassword', '确认新密码');
define('AccountPasswordsDoNotMatch', '两次密码不匹配。');

define('ContactTitle', '标题');
define('ContactFirstName', '名');
define('ContactSurName', '姓');

define('ContactNickName', '昵称');

define('CaptchaTitle', '验证码');
define('CaptchaReloadLink', '刷新');
define('CaptchaError', '验证码不正确。');

define('WarningInputCorrectEmails', '请指定正确的邮件地址。');
define('WrongEmails', '错误的邮件地址:');

define('ConfirmBodySize1', '输入框已经达到最大');
define('ConfirmBodySize2', '您输入的字符已超过限定长度，要重新编辑请点击“取消”');
define('BodySizeCounter', '计算器');
define('InsertImage', '插入图片');
define('ImagePath', '图片路径');
define('ImageUpload', '插入');
define('WarningImageUpload', '附上的不是图片文件，请另选择一个图片文件。');

define('ConfirmExitFromNewMessage', '更改没有保存，确定要离开此页面吗？点“取消”留在本页面。');

define('SensivityConfidential', '请把邮件视为机密。');
define('SensivityPrivate', '请把邮件视为不公开的');
define('SensivityPersonal', '请把邮件视为亲启');

define('ReturnReceiptTopText', '当您收到此邮件的时候，发件人也收到了通知。');
define('ReturnReceiptTopLink', '单击这里通知发件人');
define('ReturnReceiptSubject', '回执 (已显示)');
define('ReturnReceiptMailText1', '这是一个您已发出邮件的回执');
define('ReturnReceiptMailText2', '注意: 此回执仅承认邮件已经在接收者的电脑上显示，但不保证接收者已经阅读或者理解邮件内容。');
define('ReturnReceiptMailText3', '包含主题');

define('SensivityMenu', '敏感');
define('SensivityNothingMenu', '无');
define('SensivityConfidentialMenu', '机密');
define('SensivityPrivateMenu', '隐私');
define('SensivityPersonalMenu', '个人');

define('ErrorLDAPonnect', '无法连接到LDAP服务器。');

define('MessageSizeExceedsAccountQuota', '此邮件大小已超过您的邮箱配额');
define('MessageCannotSent', '无法发送此邮件。');
define('MessageCannotSaved', '无法保存此邮件。');

define('ContactFieldTitle', '字段');
define('ContactDropDownTO', '收件人');
define('ContactDropDownCC', '抄送');
define('ContactDropDownBCC', '密送');

// 4.9
define('NoMoveDelete', '邮件无法移动到垃圾箱，可能邮件已经满，要将此邮件删除吗？');

define('WarningFieldBlank', '此字段不能为空。');
define('WarningPassNotMatch', '密码不匹配，请查检。');
define('PasswordResetTitle', '取回密码 - step %d');
define('NullUserNameonReset', '用户');
define('IndexResetLink', '忘记密码？');
define('IndexRegLink', '注册新帐号');

define('RegDomainNotExist', '密码不存在。');
define('RegAnswersIncorrect', '答案错误。');
define('RegUnknownAdress', '未知邮件地址。');
define('RegUnrecoverableAccount', '此邮件无法申请取回密码。');
define('RegAccountExist', '此邮件地址已经被使用。');
define('RegRegistrationTitle', '注册新帐号');
define('RegName', '名字');
define('RegEmail', 'e-mail 地址');
define('RegEmailDesc', '例如, myname@domain.com. 此帐号用于登陆系统。');
define('RegSignMe', '记住用户名');
define('RegSignMeDesc', '下次自动在此电脑登陆。');
define('RegPass1', '密码');
define('RegPass2', '重复密码 ');
define('RegQuestionDesc', '请提供两个机密的问题和答案，万一忘记密码，你可以使用此答案找回密码。');
define('RegQuestion1', '机密问题 1');
define('RegAnswer1', '答案 1');
define('RegQuestion2', '机密问题 2');
define('RegAnswer2', '答案 2');
define('RegTimeZone', '时区');
define('RegLang', '界面语言');
define('RegCaptcha', '验证码');
define('RegSubmitButtonValue', '注册');

define('ResetEmail', '请提供您的E-mail');
define('ResetEmailDesc', '此E-mail已经注册过了。');
define('ResetCaptcha', '验证码');
define('ResetSubmitStep1', '提交');
define('ResetQuestion1', '机密问题 1');
define('ResetAnswer1', '答案');
define('ResetQuestion2', '机密问题 2');
define('ResetAnswer2', '答案');
define('ResetSubmitStep2', '提交');

define('ResetTopDesc1Step2', '邮件地址');
define('ResetTopDesc2Step2', '确认地址');

define('ResetTopDescStep3', '请输入新密码。');

define('ResetPass1', '新密码');
define('ResetPass2', '重复密码');
define('ResetSubmitStep3', '提交');
define('ResetDescStep4', '您的密码已经成功更改。');
define('ResetSubmitStep4', '返回');

define('RegReturnLink', '返回登陆窗口');
define('ResetReturnLink', '返回登陆窗口');

// Appointments
define('AppointmentAddGuests', '添加来宾');
define('AppointmentRemoveGuests', '取消会议');
define('AppointmentListEmails', '输入邮件地址用逗号隔开然后点保存。');
define('AppointmentParticipants', '参与者');
define('AppointmentRefused', '被拒绝');
define('AppointmentAwaitingResponse', '等等回应');
define('AppointmentInvalidGuestEmail', '以下来宾地址无效:');
define('AppointmentOwner', '所有者');

define('AppointmentMsgTitleInvite', '邀请参加活动');
define('AppointmentMsgTitleUpdate', '活动已修改');
define('AppointmentMsgTitleCancel', '活动被取消。');
define('AppointmentMsgTitleRefuse', '来宾 %guest% 拒绝邀请。');
define('AppointmentMoreInfo', '更多信息');
define('AppointmentOrganizer', '组织者');
define('AppointmentEventInformation', '活动信息');
define('AppointmentEventWhen', '当');
define('AppointmentEventParticipants', '参与者');
define('AppointmentEventDescription', '描述');
define('AppointmentEventWillYou', '您参与吗？');
define('AppointmentAdditionalParameters', '附加参数');
define('AppointmentHaventRespond', '无响应');
define('AppointmentRespondYes', '我将参与');
define('AppointmentRespondMaybe', '不确认');
define('AppointmentRespondNo', '不参与');
define('AppointmentGuestsChangeEvent', '来宾可以修改活动');

define('AppointmentSubjectAddStart', '您收到活动邀请 ');
define('AppointmentSubjectAddFrom', ' 发件人 ');
define('AppointmentSubjectUpdateStart', '修改活动');
define('AppointmentSubjectDeleteStart', '取消活动 ');
define('ErrorAppointmentChangeRespond', '无法修改约会响应');
define('SettingsAutoAddInvitation', '自动添加邀请进日程表');
define('ReportEventSaved', '您的活动已保存');
define('ReportAppointmentSaved', ' and notifications were sent');
define('ErrorAppointmentSend', '无法发送邀请。');
define('AppointmentEventName', '名称:');

// End appointments

define('ErrorCantUpdateFilters', '无法更新过滤器');

define('FilterPhrase', '如果 %field 邮件头 %condition %string 就 %action');
define('FiltersAdd', '添加过滤');
define('FiltersCondEqualTo', '等于');
define('FiltersCondContainSubstr', '包含');
define('FiltersCondNotContainSubstr', '不包含');
define('FiltersActionDelete', '删除邮件');
define('FiltersActionMove', '移动');
define('FiltersActionToFolder', '到 %folder ');
define('FiltersNo', '没有指定过滤');

define('ReminderEmailFriendly', '提醒');
define('ReminderEventBegin', '开始于: ');

define('FiltersLoading', '加载过滤...');
define('ConfirmMessagesPermanentlyDeleted', '此文件夹所有邮件将被删除');

define('InfoNoNewMessages', 'There are no new messages.');
define('TitleImportContacts', 'Import Contacts');
define('TitleSelectedContacts', 'Selected Contacts');
define('TitleNewContact', 'New Contact');
define('TitleViewContact', 'View Contact');
define('TitleEditContact', 'Edit Contact');
define('TitleNewGroup', 'New Group');
define('TitleViewGroup', 'View Group');

define('AttachmentComplete', 'Complete.');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'Autocheck mail every');
define('AutoCheckMailIntervalDisableName', 'Off');

define('ReportCalendarSaved', '日程已保存');

define('ContactSyncError', 'Sync failed');
define('ReportContactSyncDone', 'Sync complete');

define('MobileSyncUrlTitle', 'Mobile sync URL');
define('MobileSyncLoginTitle', 'Mobile sync login');

define('QuickReply', 'Quick Reply');
define('SwitchToFullForm', 'Open full reply form');
define('SortFieldDate', 'Date');
define('SortFieldFrom', 'From');
define('SortFieldSize', 'Size');
define('SortFieldSubject', 'Subject');
define('SortFieldFlag', 'Flag');
define('SortFieldAttachments', 'Attachments');
define('SortOrderAscending', 'Ascending');
define('SortOrderDescending', 'Descending');
define('ArrangedBy', 'Arranged by');

define('MessagePaneToRight', 'The message pane is to the right of the message list, rather than below');

define('SettingsTabMobileSync', 'Mobile');

define('MobileSyncContactDataBaseTitle', 'Mobile sync contact database');
define('MobileSyncCalendarDataBaseTitle', 'Mobile sync calendar database');
define('MobileSyncTitleText', 'If you\'d like to synchronize your SyncML-enabled handheld device with WebMail, you can use these parameters.<br />"Mobile Sync URL" specifies path to SyncML Data Synchronization server, "Mobile Sync Login" is your login on SyncML Data Synchronization Server and use your own password upon request. Also, some devices need to specify database name for contact and calendar data.<br />Use "Mobile sync contact database" and "Mobile sync calendar database" respectively.');
define('MobileSyncEnableLabel', 'Enable mobile sync');

define('SearchInputText', 'search');

define('AppointmentEmailExplanation','This message arrived to your %EMAIL% account because you was invited to the event by %ORGANAZER%');

define('Searching', 'Searching&hellip;');

define('ButtonSetupSpecialFolders', 'Setup special folders');
define('ButtonSaveChanges', 'Save changes');
define('InfoPreDefinedFolders', 'For pre-defined folders, use these IMAP mailboxes');

define('SaveMailInSentItems', 'Also save in Sent Items');

define('CouldNotSaveUploadedFile', 'Could not save uploaded file.');

define('AccountOldPassword', 'Current password');
define('AccountOldPasswordsDoNotMatch', 'Current Passwords do not match.');

define('DefEditor', 'Default editor');
define('DefEditorRichText', 'Rich Text');
define('DefEditorPlainText', 'Plain Text');

define('Layout', 'Layout');

define('TitleNewMessagesCount', '%count% new message(s)');

define('AltOpenInNewWindow', 'Open in new window');

define('SearchByFirstCharAll', 'All');

define('FolderNoUsageAssigned', 'No usage assigned');

define('InfoSetupSpecialFolders', 'To match a special folder (like Sent Items) and certain IMAP mailbox, click Setup special folders.');

define('FileUploaderClickToAttach', 'Click to attach a file');
define('FileUploaderOrDragNDrop', 'Or just drag and drop files here');

define('AutoCheckMailInterval1Minute', '1 minute');
define('AutoCheckMailInterval3Minutes', '3 minutes');
define('AutoCheckMailInterval5Minutes', '5 minutes');
define('AutoCheckMailIntervalMinutes', 'minutes');

define('ReadAboutCSVLink', 'Learn more on .CSV file fields');

define('VoiceMessageSubj', 'Voice Message');
define('VoiceMessageTranscription', 'Transcription');
define('VoiceMessageReceived', 'Received');
define('VoiceMessageDownload', 'Download');
define('VoiceMessageUpgradeFlashPlayer', 'You need to upgrade your Adobe Flash Player to play voice messages.<br />Upgrade to Flash Player 10 from <a href="http://www.adobe.com/go/getflashplayer/" target="_blank">Adobe</a>.');

define('LicenseKeyIsOutdated', 'This license key is outdated, please contact us to upgrade your license key');
define('LicenseProblem', 'Licensing problem. System administrator should go in Admin Panel to check the details.');

define('AccountOldPasswordNotCorrect', 'Current password is not correct');
define('AccountNewPasswordUpdateError', 'Can\'t save new password.');
define('AccountNewPasswordRejected', 'Can\'t save new password. Perhaps, it\'s too simple.');

define('CantCreateIdentity', 'Can\'t create identity');
define('CantUpdateIdentity', 'Can\'t update identity');
define('CantDeleteIdentity', 'Can\'t delete identity');

define('AddIdentity', 'Add Identity');
define('SettingsTabIdentities', 'Identities');
define('NoIdentities', 'No identities');
define('NoSignature', 'No signature');
define('Account', 'Account');
define('TabChangePassword', 'Password');
define('SignatureEnteringHere', 'Start entering your signature here');

define('CantConnectToMailServer', 'Can\'t connect to mail server');

define('DomainNameNotSpecified', 'Domain name not specified.');

define('Open', 'Open');
define('FolderUsedAs', 'used as');
define('ForwardTitle', 'Forward');
define('ForwardEnable', 'Enable forward');
define('ReportForwardUpdatedSuccessfuly', 'Forward has been updated successfully.');

define('DialogAttachHeaderResume', 'Attach Your Resume');
define('DialogAttachHeaderLetter', 'Attach Your Cover Letter');
define('DialogAttachName', 'Select Resume');
define('DialogAttachType', 'Choose Format');
define('DialogAttachTypePdf', 'Adobe PDF (.pdf)');
define('DialogAttachTypeHtml', 'Web Page (.html)');
define('DialogAttachTypeRtf', 'Rich Text (.rtf)');
define('DialogAttachTypeTxt', 'Plain Text (.txt)');
define('DialogAttachTypeDoc', 'MS Word (.doc)');
define('DialogAttachButton', 'Attach');
define('DialogAttachResume', 'Attach a resume');
define('DialogAttachLetter', 'Attach a cover letter');
define('DialogAttachAnother', 'Attach another file');
define('DialogAttachAddToBody', 'Add plain text version to email body (Recommended)');
define('DialogAttachTypeNo', 'No Attachment');
define('DialogAttachSelectLetter', 'Select cover letter');
define('DialogAttachTypePdfRecom', 'Adobe PDF (.pdf) (Recommended)');
define('DialogAttachTypeTextInBody', 'Plain text in email body - recommended');
define('DialogAttachTypeTxtAttach', 'Plain Text (.txt) attachment');
define('CustomTitle', 'Forwarding');
define('ForwardingNotificationsTo', 'Send email notifications to <b>%email</b>');
define('ForwardingForwardTo', 'Forward email to <b>%email</b>');
define('ForwardingNothing', 'No email notifications or forwarding');
define('ForwardingChange', 'change');

define('ConfirmSaveForward', 'The forward settings were not saved. Click OK to save.');
define('ConfirmSaveAutoresponder', 'The autoresponder settings were not saved. Click OK to save.');

define('DigDosMenuItem', 'DigDos');
define('DigDosTitle', 'Select an object');

define('LastLoginTitle', 'Last login');
define('ExportContacts', 'Export Contacts');

define('JS_LANG_Gb', 'GB');

define('ContactsTabGlobal', 'global');
define('ContactsTabPersonal', 'personal');
define('InfoLoadingContacts', 'WebMail is loading contact list');

define('TheAccessToThisAccountIsDisabled', 'The access to this account is disabled');

define('MobileSyncDavServerURL', 'DAV server URL');
define('MobileSyncPrincipalURL', 'Principal URL');
define('MobileSyncHintDesc', 'Use these settings to sync your calendars and contacts with a mobile device which supports CalDAV or CardDAV protocols.<br /><br />With iOS devices like iPhone, you\'ll usually need DAV server URL, mobile sync login, and your password. Or, you can get your iOS profile automatically if you access this webmail from your such device.<br /><br />Some software like Mozilla Thunderbird require separate URL to each calendar of yours. To get this URL, select Share and Publish option for the given calendar in Calendars Manager.');

define('MobileGetIOSSettings', 'Deliver e-mail, contacts and calendar settings on your iOS device');
define('IOSLoginHeadTitle', 'Install iOS Profile');
define('IOSLoginHelloAppleTitle', 'Hello,');
define('IOSLoginHelpDesc1', 'We can automatically deliver your e-mail, contacts and calendar settings on your iOS device.');
define('IOSLoginHelpDesc2', 'You can always get them later,');
define('IOSLoginHelpDesc3', 'in Settings/Mobile section.');
define('IOSLoginButtonYesPlease', 'Yes, please');
define('IOSLoginButtonSkip', 'Skip this and let me in');
define('IOSLoginPage2HelloAppleTitle', 'Your account is ready!');
define('IOSLoginPage2HelpDesc1', 'With the new profile, you can sync e-mail and calendars on your iOS device using its native e-mail and calendar application.');
define('IOSLoginPage2HelpDesc2', 'If you wish, you can also use webmail for that.');
define('IOSLoginPage2ButtonOpenWebMail', 'Open webmail');

define('LoginBrowserWarning', 'Sorry, this web browser is not supported.<br/>We recommend to use one of the following browsers:<br/><a href="http://www.microsoft.com/windows/internet-explorer/default.aspx">Internet Explorer 7</a>, <a href="http://www.firefox.com/">Mozilla Firefox 2</a>, <a href="http://www.apple.com/safari/download/">Safari 2</a>, <a href="http://www.opera.com/">Opera 9</a> or newer versions of these browsers.');

define('AppointmentInvitation', 'Invitation');
define('AppointmentAccepted', 'accepted');
define('AppointmentDeclined', 'declined');
define('AppointmentTentativelyAccepted', 'tentatively accepted');
define('AppointmentLocation', 'Location');
define('AppointmentCalendar', 'Calendar');
define('AppointmentWhen', 'When');
define('AppointmentDescription', 'Description');
define('AppointmentButtonAccept', 'Accept');
define('AppointmentButtonTentative', 'Tentative');
define('AppointmentButtonDecline', 'Decline');

define('ContactDisplayName', 'Display name');

define('WarningCreatingGroupRequiresContacts', 'Creating a group requires adding at least one contact to it.');
define('WarningRemovingAllContactsFromGroup', 'Removing all contacts from the group removes the group itself. Do you want to proceed?');
define('WarningSendEmailToDemoOnly', 'For security purposes, this demo account is allowed to send e-mail to demo accounts only.');

define('SettingsTabOutlookSync', 'Outlook Sync');

define('OutlookSyncServerURL', 'Server');

define('OutlookSyncHintDesc', 'To sync your Outlook calendar, specify these values in Outlook Sync plugin:');

define('WarningMailboxAlmostFull', 'Your mailbox is almost full.');
define('WarningCouldNotSaveDraftAsYourMailboxIsOverQuota', 'Could not save draft as your mailbox is over quota.');
define('WarningSentEmailNotSaved', 'The e-mail has been sent but could not save it in Sent Items as your mailbox is over quota.');

define('DavSyncHeading', 'DAV Sync via single URL (for Apple clients)');
define('DavSyncHint', 'Use the URL below to sync calendars and contacts with Apple iCal or a mobile device like iPhone or iPad (they all support syncing multiple CalDAV or CardDAV folders via a single URL). By the way, you can get your iOS profile automatically if you access this webmail from such device!');
define('DavSyncServer', 'DAV server');
define('DavSyncHeadingLogin', 'You\'ll also need the login and password:');
define('DavSyncLogin', 'Mobile sync login');
define('DavSyncPasswordTitle', 'Password');
define('DavSyncPasswordValue', 'Your account\'s password');
define('DavSyncSeparateUrlsHeading', 'DAV Sync via separate URLs');
define('DavSyncHintUrls', 'If your CalDAV or CardDAV client requires separate URLs for each calendar or address book of yours (such as Mozilla Thunderbird Lightning or Evolution), use the URLs below.');
define('DavSyncHeadingCalendar', 'CalDAV access to your calendars');
define('DavSyncHeadingContacts', 'CardDAV access to your address books');
define('DavSyncPersonalContacts', 'Personal contacts');
define('DavSyncCollectedAddresses', 'Collected addresses');
define('DavSyncGlobalAddressBook', 'Global address book');

define('ActiveSyncHeading', 'ActiveSync');
define('ActiveSyncHint', 'To sync your e-mail, contacts and calendar via EAS (Exchange ActiveSync), use the settings below:');
define('ActiveSyncServer', 'Server');
define('ActiveSyncLogin', 'Login');
define('ActiveSyncPasswordTitle', 'Password');
define('ActiveSyncPasswordValue', 'Your account\'s password');

define('SearchStop', 'Stop search');
define('ErrorDuringSearch', 'An error occured during search');
define('ErrorRetrievingMessages', 'An error occured when retrieving message list');

define('AppointmentCanceled', '%SENDER% canceled this meeting.');

define('CalendarDavUrl', 'DAV URL');
define('CalendarIcsLink', 'link to .ics');
define('CalendarIcsDownload', 'Download');

define('DavSyncDemoPasswordValue', 'demo');

define('ActiveSyncDemoPasswordValue', 'demo');

define('ConfirmUnsubscribeCalendar', 'Are you sure you want to unsubscribe from calendar');
define('CalendarUnsubscribe', 'Unsubscribe');
define('InfoUnsubscribing', 'Unsubscribing&hellip;');

define('ErrorDataTransferFailed', 'Data transfer has failed, probably due to server error. Please contact system administrator.');
define('ErrorCantReachServer', 'Can\'t reach the server.');
define('RetryGettingMessageList', 'Retry');
define('BackToMessageList', 'Back to messages list');

define('ErrorTitle', 'Error');
define('ErrorUnableToLogIntoAccount', 'Unable to log into account.');
define('ErrorUnableToLocateMessage', 'Unable to locate email message.');

define('ConfirmEditRepeatEventNotDaily', 'This way, you can set the date for a single occurrence only. To change the date for the entire series, now click Cancel and then set the date in the event properties.');

define('WarningMailboxIsFull', 'Your mailbox is full.');

define('ErrorVCardHasInvalidFormat', 'This vCard has invalid format and cannot be parsed.');

define('AddToContacts', 'Add to contacts');
define('AddToCalendar', 'Add to calendar');
define('EventAlreadyExistsInCalendar', 'Event already exists in calendar');
define('ContactAlreadyExistsInAddressBook', 'Contact already exists in address book');
define('NoMoveSpamInFullMailbox', 'Message(s) can\'t be moved to Spam. Most likely your message box is full. Should this unmoved message(s) be deleted?');
define('NoMoveInFullMailbox', 'Message(s) can\'t be moved. Most likely your message box is full.');
	
define('TopMultipleContactsSearchResults', 'Top of search results for "%SEARCHSTR%" in global and personal address books:');
define('ContactsSearchResults', 'Search results for "%SEARCHSTR%":');
define('ContactsSearchResultsInGroup', 'Search results for "%SEARCHSTR%" in group %GROUPNAME%:');
define('NoContactsFound', 'No contacts found.');
define('ContactsTypeTitle', 'Address book type:');
define('ContactsTypeMultiple', 'Personal and Global');
define('ContactsTypePersonal', 'Personal');
define('ContactsTypeGlobal', 'Global');
