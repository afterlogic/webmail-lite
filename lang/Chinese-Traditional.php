<?php
define('PROC_ERROR_ACCT_CREATE', '創建帳號失敗');
define('PROC_WRONG_ACCT_PWD', '帳號密碼錯誤');
define('PROC_CANT_LOG_NONDEF', '無法登陸到非默認帳號');
define('PROC_CANT_INS_NEW_FILTER', '無法添加新過濾');
define('PROC_FOLDER_EXIST', '文件夾已經存在');
define('PROC_CANT_CREATE_FLD', '無法創建新文件夾');
define('PROC_CANT_INS_NEW_GROUP', '無法創建新組');
define('PROC_CANT_INS_NEW_CONT', '無法添加新聯係人');
define('PROC_CANT_INS_NEW_CONTS', '無法添加新聯係人（批量）');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', '無法添加聯係人到組');
define('PROC_ERROR_ACCT_UPDATE', '修改帳號出現了一個錯誤');
define('PROC_CANT_UPDATE_CONT_SETTINGS', '無法更新聯係人的設置');
define('PROC_CANT_GET_SETTINGS', '無法進入設置');
define('PROC_CANT_UPDATE_ACCT', '更新帳號失敗');
define('PROC_ERROR_DEL_FLD', '刪除文件夾時出現了一個錯誤');
define('PROC_CANT_UPDATE_CONT', '無法更新聯係人');
define('PROC_CANT_GET_FLDS', '無法取得文件列表');
define('PROC_CANT_GET_MSG_LIST', '無法取得郵件列表');
define('PROC_MSG_HAS_DELETED', '此郵件從已經從郵件服務器刪除');
define('PROC_CANT_LOAD_CONT_SETTINGS', '無法加載聯係人設置');
define('PROC_CANT_LOAD_SIGNATURE', '無法加載帳號的簽名');
define('PROC_CANT_GET_CONT_FROM_DB', '無法從數據庫取得聯係人');
define('PROC_CANT_GET_CONTS_FROM_DB', '無法從數據庫取得聯係人列表');
define('PROC_CANT_DEL_ACCT_BY_ID', '刪除帳號失敗');
define('PROC_CANT_DEL_FILTER_BY_ID', '無法刪除過濾');
define('PROC_CANT_DEL_CONT_GROUPS', '刪除聯係人或組別失敗');
define('PROC_WRONG_ACCT_ACCESS', '越權訪問另一個帳號。');
define('PROC_SESSION_ERROR', '會話被終止，請檢查是否你的登陸已超時，可能服務器時間或客戶端時間不正確。');

define('MailBoxIsFull', '郵箱已滿');
define('WebMailException', '內部服務器錯誤，請聯係係統管理員。');
define('InvalidUid', '郵箱用戶名錯誤');
define('CantCreateContactGroup', '無法創建聯係人組別');
define('CantCreateUser', '創建用戶失敗');
define('CantCreateAccount', '創建帳號失敗');
define('SessionIsEmpty', '會話為空');
define('FileIsTooBig', '文件過大');

define('PROC_CANT_MARK_ALL_MSG_READ', '無法標記所有郵件為已讀');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', '無法標記所有郵件為未讀');
define('PROC_CANT_PURGE_MSGS', '無法清除郵件');
define('PROC_CANT_DEL_MSGS', '無法刪除郵件');
define('PROC_CANT_UNDEL_MSGS', '無法恢複被刪除的郵件');
define('PROC_CANT_MARK_MSGS_READ', '無法標記郵件為已讀');
define('PROC_CANT_MARK_MSGS_UNREAD', '無法標記郵件為未讀');
define('PROC_CANT_SET_MSG_FLAGS', '無法設置郵件標記');
define('PROC_CANT_REMOVE_MSG_FLAGS', '無法移除郵件標記');
define('PROC_CANT_CHANGE_MSG_FLD', '無法更變郵件文件夾');
define('PROC_CANT_SEND_MSG', '郵件發送失敗。');
define('PROC_CANT_SAVE_MSG', '郵件保存失敗。');
define('PROC_CANT_GET_ACCT_LIST', '無法取得帳戶列表');
define('PROC_CANT_GET_FILTER_LIST', '無法取得過濾列表');

define('PROC_CANT_LEAVE_BLANK', '請不能留空白字段');

define('PROC_CANT_UPD_FLD', '無法更改文件夾');
define('PROC_CANT_UPD_FILTER', '無法更新過濾');

define('ACCT_CANT_ADD_DEF_ACCT', '不能添加此帳號，因為另一個用戶已經把它作為默認帳號。');
define('ACCT_CANT_UPD_TO_DEF_ACCT', '此帳號的狀態不能更改為默認。');
define('ACCT_CANT_CREATE_IMAP_ACCT', '無法創建新帳號 (IMAP4 連接錯誤)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', '不能刪除最後一個默認帳號');

define('LANG_LoginInfo', '登陸信息');
define('LANG_Email', 'Email');
define('LANG_Login', '登陸');
define('LANG_Password', '密碼');
define('LANG_IncServer', '來信');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', '發信');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', '使用SMTP認證');
define('LANG_SignMe', '下載自動登陸');
define('LANG_Enter', '進入郵箱');

// interface strings

define('JS_LANG_TitleLogin', '登陸');
define('JS_LANG_TitleMessagesListView', '郵件列表');
define('JS_LANG_TitleMessagesList', '郵件列表');
define('JS_LANG_TitleViewMessage', '查看郵件');
define('JS_LANG_TitleNewMessage', '寫郵件');
define('JS_LANG_TitleSettings', '設置');
define('JS_LANG_TitleContacts', '聯係人');

define('JS_LANG_StandardLogin', '標準登陸模式');
define('JS_LANG_AdvancedLogin', '高級登陸模式');

define('JS_LANG_InfoWebMailLoading', '正在加載Webmail...');
define('JS_LANG_Loading', '正在加載...');
define('JS_LANG_InfoMessagesLoad', 'WebMail 正在加載郵件列表...');
define('JS_LANG_InfoEmptyFolder', '文件夾是空的');
define('JS_LANG_InfoPageLoading', '此頁麵仍然正加載...');
define('JS_LANG_InfoSendMessage', '郵件已發送');
define('JS_LANG_InfoSaveMessage', '郵件已保存');
define('JS_LANG_InfoHaveImported', '你已經導入');
define('JS_LANG_InfoNewContacts', '新聯係進入你的聯係人列表。');
define('JS_LANG_InfoToDelete', '刪除');
define('JS_LANG_InfoDeleteContent', '請先刪除文件夾裏的內容。');
define('JS_LANG_InfoDeleteNotEmptyFolders', '不允許刪除非空的文件夾，要刪除文件夾 (已經禁用複選框)，請先刪除裏麵的內容。');
define('JS_LANG_InfoRequiredFields', '* 必需字段');

define('JS_LANG_ConfirmAreYouSure', '確認操作？');
define('JS_LANG_ConfirmDirectModeAreYouSure', '已經選定的郵件將永久刪除! 你確定要這樣做嗎？');
define('JS_LANG_ConfirmSaveSettings', '設置未保存，請點擊 確定 保存。');
define('JS_LANG_ConfirmSaveContactsSettings', '聯係人設置未保存，請點擊 確定 保存。');
define('JS_LANG_ConfirmSaveAcctProp', '帳號屬性未保存，請點擊 確定 保存。');
define('JS_LANG_ConfirmSaveFilter', '過濾屬性未保存，請點擊 確定 保存。');
define('JS_LANG_ConfirmSaveSignature', '簽名未保存，請點擊 確定 保存。');
define('JS_LANG_ConfirmSavefolders', '文件夾未保存，請點擊 確定 保存。');
define('JS_LANG_ConfirmHtmlToPlain', '注意: 把HTML格式的郵件改為純文本後, 您將丟失當前的格式，點擊 OK 繼續。');
define('JS_LANG_ConfirmAddFolder', '要使 添加/移除 文件夾 生效，請點擊 確定 保存。');
define('JS_LANG_ConfirmEmptySubject', '郵件主題是空的，要繼續嗎？');

define('JS_LANG_WarningEmailBlank', 'Email不能為空');
define('JS_LANG_WarningLoginBlank', 'Login不能為空');
define('JS_LANG_WarningToBlank', '收件人地址不為能為空');
define('JS_LANG_WarningServerPortBlank', 'POP3、SMTP、端口不能為空。');
define('JS_LANG_WarningEmptySearchLine', '搜索關鍵字不能為空。');
define('JS_LANG_WarningMarkListItem', '至少選擇一個項目。');
define('JS_LANG_WarningFolderMove', '文件夾不能被移動，因為這是另一個級別。');
define('JS_LANG_WarningContactNotComplete', '請輸入Email或名字。');
define('JS_LANG_WarningGroupNotComplete', '請輸入組別名。');

define('JS_LANG_WarningEmailFieldBlank', 'Email不能為空。');
define('JS_LANG_WarningIncServerBlank', 'POP3(IMAP4) 服務器不能為空。');
define('JS_LANG_WarningIncPortBlank', 'POP3(IMAP4)服務器端口不能為空。');
define('JS_LANG_WarningIncLoginBlank', 'POP3(IMAP4)登陸名不能為空。');
define('JS_LANG_WarningIncPortNumber', 'POP3(IMAP4)端口應該為正數數字。');
define('JS_LANG_DefaultIncPortNumber', '默認POP3(IMAP4) 端口號是 110(143).');
define('JS_LANG_WarningIncPassBlank', 'POP3(IMAP4)密碼不能為空');
define('JS_LANG_WarningOutPortBlank', 'SMTP服務器端口不能為空。');
define('JS_LANG_WarningOutPortNumber', 'SMTP端口應該為正數數字。');
define('JS_LANG_WarningCorrectEmail', '請輸入正確的 e-mail.');
define('JS_LANG_DefaultOutPortNumber', '默認 SMTP 端口是 25.');

define('JS_LANG_WarningCsvExtention', '擴展名為 .csv');
define('JS_LANG_WarningImportFileType', '複製聯係人，請選擇相應的程序。');
define('JS_LANG_WarningEmptyImportFile', '請點“瀏覽...”選擇文件。');

define('JS_LANG_WarningContactsPerPage', '聯係人每頁數字為正數');
define('JS_LANG_WarningMessagesPerPage', '郵件列表每頁數字為正數');
define('JS_LANG_WarningMailsOnServerDays', '在服務器days字段，請指定一個正數在郵件裏。');
define('JS_LANG_WarningEmptyFilter', '請輸入字符');
define('JS_LANG_WarningEmptyFolderName', '請輸入文件夾名');

define('JS_LANG_ErrorConnectionFailed', '連接失敗');
define('JS_LANG_ErrorRequestFailed', '數據傳送完成');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'XMLHttp 缺少請求對象');
define('JS_LANG_ErrorWithoutDesc', '錯誤沒有描述');
define('JS_LANG_ErrorParsing', 'XML解析錯誤');
define('JS_LANG_ResponseText', '響應文字:');
define('JS_LANG_ErrorEmptyXmlPacket', 'XML 數據包為空');
define('JS_LANG_ErrorImportContacts', '導入聯係人出錯');
define('JS_LANG_ErrorNoContacts', '沒有導入聯係人');
define('JS_LANG_ErrorCheckMail', '接收郵件被終止，發生了一個錯誤，部分郵件可能未接收完。');

define('JS_LANG_LoggingToServer', '正在登陸服務器...');
define('JS_LANG_GettingMsgsNum', '正在獲取郵件數...');
define('JS_LANG_RetrievingMessage', '恢複郵件');
define('JS_LANG_DeletingMessage', '正在刪除郵件');
define('JS_LANG_DeletingMessages', '正在刪除郵件');
define('JS_LANG_Of', 'of');
define('JS_LANG_Connection', '連接');
define('JS_LANG_Charset', '編碼');
define('JS_LANG_AutoSelect', '自動選擇');

define('JS_LANG_Contacts', '聯係人');
define('JS_LANG_ClassicVersion', '傳統模式');
define('JS_LANG_Logout', '退出');
define('JS_LANG_Settings', '設置');

define('JS_LANG_LookFor', '查找: ');
define('JS_LANG_SearchIn', '搜索: ');
define('JS_LANG_QuickSearch', '搜索 "發件人", "收件人" 和 "郵件主題" (更快).');
define('JS_LANG_SlowSearch', '搜索全部郵件');
define('JS_LANG_AllMailFolders', '所有郵件文件夾');
define('JS_LANG_AllGroups', '所有組別');

define('JS_LANG_NewMessage', '寫郵件');
define('JS_LANG_CheckMail', '收郵件');
define('JS_LANG_EmptyTrash', '清空垃圾箱');
define('JS_LANG_MarkAsRead', '標記為已讀');
define('JS_LANG_MarkAsUnread', '標記為未讀');
define('JS_LANG_MarkFlag', '標記');
define('JS_LANG_MarkUnflag', '取消標記');
define('JS_LANG_MarkAllRead', '標記所有郵件為已讀');
define('JS_LANG_MarkAllUnread', '標記所有郵件為未讀');
define('JS_LANG_Reply', '回複');
define('JS_LANG_ReplyAll', '回複所有');
define('JS_LANG_Delete', '刪除');
define('JS_LANG_Undelete', '恢複');
define('JS_LANG_PurgeDeleted', '清空已刪除');
define('JS_LANG_MoveToFolder', '移動到');
define('JS_LANG_Forward', '轉發');

define('JS_LANG_HideFolders', '隱藏文件夾');
define('JS_LANG_ShowFolders', '顯示文件夾');
define('JS_LANG_ManageFolders', '管理文件夾');
define('JS_LANG_SyncFolder', '同步文件夾');
define('JS_LANG_NewMessages', '寫郵件');
define('JS_LANG_Messages', '郵件');

define('JS_LANG_From', '發件人');
define('JS_LANG_To', '收件人');
define('JS_LANG_Date', '日期');
define('JS_LANG_Size', '大小');
define('JS_LANG_Subject', '主題');

define('JS_LANG_FirstPage', '首頁');
define('JS_LANG_PreviousPage', '上一頁');
define('JS_LANG_NextPage', '下一頁');
define('JS_LANG_LastPage', '尾頁');

define('JS_LANG_SwitchToPlain', '轉到純文本查看');
define('JS_LANG_SwitchToHTML', '轉到HTML查看');
define('JS_LANG_AddToAddressBook', '添加聯係人');
define('JS_LANG_ClickToDownload', '點擊下載 ');
define('JS_LANG_View', 'View');
define('JS_LANG_ShowFullHeaders', '顯示完整的郵件頭');
define('JS_LANG_HideFullHeaders', '隱藏完整的郵件頭');

define('JS_LANG_MessagesInFolder', '封郵件在此文件夾');
define('JS_LANG_YouUsing', '您正在使用');
define('JS_LANG_OfYour', '屬於您的');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', '發送');
define('JS_LANG_SaveMessage', '保存');
define('JS_LANG_Print', '打印');
define('JS_LANG_PreviousMsg', '上一封');
define('JS_LANG_NextMsg', '下一封');
define('JS_LANG_AddressBook', '地址簿');
define('JS_LANG_ShowBCC', '添加密送');
define('JS_LANG_HideBCC', '刪除密送');
define('JS_LANG_CC', '抄送');
define('JS_LANG_BCC', '密送地址');
define('JS_LANG_ReplyTo', '回複');
define('JS_LANG_AttachFile', '附件');
define('JS_LANG_Attach', '附件');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', '原始郵件');
define('JS_LANG_Sent', '已發送');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', '低');
define('JS_LANG_Normal', '中');
define('JS_LANG_High', '高');
define('JS_LANG_Importance', '優先級');
define('JS_LANG_Close', '關閉');

define('JS_LANG_Common', '常規設置');
define('JS_LANG_EmailAccounts', '郵件帳號');

define('JS_LANG_MsgsPerPage', '封/每頁');
define('JS_LANG_DisableRTE', '禁用富文本編輯器');
define('JS_LANG_Skin', '皮膚');
define('JS_LANG_DefCharset', '默認編碼');
define('JS_LANG_DefCharsetInc', '默認接收編碼');
define('JS_LANG_DefCharsetOut', '默認發送編碼');
define('JS_LANG_DefTimeOffset', '默認時區');
define('JS_LANG_DefLanguage', '默認語言');
define('JS_LANG_DefDateFormat', '默認日期格式');
define('JS_LANG_ShowViewPane', '郵件預覽');
define('JS_LANG_Save', '保存');
define('JS_LANG_Cancel', '取消');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', '移除');
define('JS_LANG_AddNewAccount', '添加新帳號');
define('JS_LANG_Signature', '簽名');
define('JS_LANG_Filters', '過濾');
define('JS_LANG_Properties', '屬性');
define('JS_LANG_UseForLogin', '使用此帳號登陸');
define('JS_LANG_MailFriendlyName', '您的名字');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', '接收郵件');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', '端口');
define('JS_LANG_MailIncLogin', '登陸帳號');
define('JS_LANG_MailIncPass', '密碼');
define('JS_LANG_MailOutHost', '發送郵件');
define('JS_LANG_MailOutPort', '端口');
define('JS_LANG_MailOutLogin', 'SMTP 帳號');
define('JS_LANG_MailOutPass', 'SMTP 密碼');
define('JS_LANG_MailOutAuth1', '使用 SMTP 認證');
define('JS_LANG_MailOutAuth2', '(如果 SMTP 用戶名及密碼和 POP3/IMAP4 一樣，SMTP參數可以留空)');
define('JS_LANG_UseFriendlyNm1', '在“發件人”一欄，請使用友好的名字');
define('JS_LANG_UseFriendlyNm2', '(您的名字 <sender@mail.com>)');
define('JS_LANG_GetmailAtLogin', '獲取/同步郵件');
define('JS_LANG_MailMode0', '從服務器刪除');
define('JS_LANG_MailMode1', '從服務器保留');
define('JS_LANG_MailMode2', '從服務器保留');
define('JS_LANG_MailsOnServerDays', '天');
define('JS_LANG_MailMode3', '刪除垃圾郵件時從服務器也刪除');
define('JS_LANG_InboxSyncType', '同步收件箱類型');

define('JS_LANG_SyncTypeNo', '不同步');
define('JS_LANG_SyncTypeNewHeaders', '新郵件頭');
define('JS_LANG_SyncTypeAllHeaders', '所有郵件頭');
define('JS_LANG_SyncTypeNewMessages', '新郵件');
define('JS_LANG_SyncTypeAllMessages', '所有郵件');
define('JS_LANG_SyncTypeDirectMode', '直接模式');

define('JS_LANG_Pop3SyncTypeEntireHeaders', '僅郵件頭');
define('JS_LANG_Pop3SyncTypeEntireMessages', '完整郵件');
define('JS_LANG_Pop3SyncTypeDirectMode', '直接模式');

define('JS_LANG_DeleteFromDb', '從數據庫刪除服務器不存在的郵件');

define('JS_LANG_EditFilter', '編輯過濾');
define('JS_LANG_NewFilter', '添加過濾');
define('JS_LANG_Field', '字段');
define('JS_LANG_Condition', '條件');
define('JS_LANG_ContainSubstring', '包含字符');
define('JS_LANG_ContainExactPhrase', '包含短語');
define('JS_LANG_NotContainSubstring', '不含有字符');
define('JS_LANG_FilterDesc_At', '在');
define('JS_LANG_FilterDesc_Field', '字段');
define('JS_LANG_Action', '操作');
define('JS_LANG_DoNothing', '什麼也不做');
define('JS_LANG_DeleteFromServer', '直接從服務器刪除');
define('JS_LANG_MarkGrey', '標記為灰色');
define('JS_LANG_Add', '添加');
define('JS_LANG_OtherFilterSettings', '其他過濾設置');
define('JS_LANG_ConsiderXSpam', '考慮 X-Spam 郵件頭');
define('JS_LANG_Apply', '應用');

define('JS_LANG_InsertLink', '插入鏈接');
define('JS_LANG_RemoveLink', '移除鏈接');
define('JS_LANG_Numbering', '編號');
define('JS_LANG_Bullets', '小圓球');
define('JS_LANG_HorizontalLine', '水平線');
define('JS_LANG_Bold', '粗體');
define('JS_LANG_Italic', '斜體');
define('JS_LANG_Underline', '下劃線');
define('JS_LANG_AlignLeft', '左對齊');
define('JS_LANG_Center', '居中');
define('JS_LANG_AlignRight', '右對齊');
define('JS_LANG_Justify', '對齊');
define('JS_LANG_FontColor', '字體顏色');
define('JS_LANG_Background', '背景');
define('JS_LANG_SwitchToPlainMode', '轉到文本模式');
define('JS_LANG_SwitchToHTMLMode', '轉到HTML模式');

define('JS_LANG_Folder', '文件夾');
define('JS_LANG_Msgs', '郵件數');
define('JS_LANG_Synchronize', '同步');
define('JS_LANG_ShowThisFolder', '顯示');
define('JS_LANG_Total', '總計');
define('JS_LANG_DeleteSelected', '刪除已選');
define('JS_LANG_AddNewFolder', '新建文件夾');
define('JS_LANG_NewFolder', '新文件夾');
define('JS_LANG_ParentFolder', '父文件夾');
define('JS_LANG_NoParent', '沒有父文件夾');
define('JS_LANG_FolderName', '文件夾名');

define('JS_LANG_ContactsPerPage', '每頁顯示聯係人');
define('JS_LANG_WhiteList', '白名單地址');

define('JS_LANG_CharsetDefault', '默認');
define('JS_LANG_CharsetArabicAlphabetISO', '阿拉伯文 (ISO)');
define('JS_LANG_CharsetArabicAlphabet', '阿拉伯文 (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', '波羅的海文 (ISO)');
define('JS_LANG_CharsetBalticAlphabet', '波羅的海文 (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', '中歐 (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', '中歐 (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', '簡體中文 (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', '簡體中文 (GB2312)');
define('JS_LANG_CharsetChineseTraditional', '繁體中文 (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', '西裏爾文 (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', '西裏爾文 (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', '西裏爾文 (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', '希臘文 (ISO)');
define('JS_LANG_CharsetGreekAlphabet', '希臘文 (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', '希伯來文 (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', '希伯來文 (Windows)');
define('JS_LANG_CharsetJapanese', '日本');
define('JS_LANG_CharsetJapaneseShiftJIS', '日本 (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', '韓文 (EUC)');
define('JS_LANG_CharsetKoreanISO', '韓文 (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', '拉丁文 (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', '土耳其文');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Unicode (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Unicode (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', '越南文 (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', '西歐 (ISO)');
define('JS_LANG_CharsetWesternAlphabet', '西歐 (Windows)');

define('JS_LANG_TimeDefault', '默認');
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
define('JS_LANG_DateAdvanced', '高級選項');

define('JS_LANG_NewContact', '新建聯係人');
define('JS_LANG_NewGroup', '新建組');
define('JS_LANG_AddContactsTo', '新建聯係人在');
define('JS_LANG_ImportContacts', '導入聯係人');

define('JS_LANG_Name', '名字');
define('JS_LANG_Email', 'Email');
define('JS_LANG_DefaultEmail', '默認 Email');
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
define('JS_LANG_UseFriendlyName2', '(如, 阿呆 <johndoe@mail.com>)');
define('JS_LANG_Personal', '個人');
define('JS_LANG_PersonalEmail', '個人 E-mail');
define('JS_LANG_StreetAddress', '街道');
define('JS_LANG_City', '城市');
define('JS_LANG_Fax', '傳真');
define('JS_LANG_StateProvince', '省/自治區');
define('JS_LANG_Phone', '電話');
define('JS_LANG_ZipCode', '郵編');
define('JS_LANG_Mobile', '手機');
define('JS_LANG_CountryRegion', '國家/地區');
define('JS_LANG_WebPage', '網址');
define('JS_LANG_Go', 'Go');
define('JS_LANG_Home', '個人信息');
define('JS_LANG_Business', '業務聯係');
define('JS_LANG_BusinessEmail', '業務 E-mail');
define('JS_LANG_Company', '公司');
define('JS_LANG_JobTitle', '職位');
define('JS_LANG_Department', '部門');
define('JS_LANG_Office', '辦公室');
define('JS_LANG_Pager', '呼機');
define('JS_LANG_Other', '其他');
define('JS_LANG_OtherEmail', '其他郵件');
define('JS_LANG_Notes', '備注');
define('JS_LANG_Groups', '組別');
define('JS_LANG_ShowAddFields', '顯示附加信息');
define('JS_LANG_HideAddFields', '隱藏附加信息');
define('JS_LANG_EditContact', '編輯聯係信息');
define('JS_LANG_GroupName', '組名');
define('JS_LANG_AddContacts', '添加聯係人');
define('JS_LANG_CommentAddContacts', '(多個地址用逗號,分開)');
define('JS_LANG_CreateGroup', '創建分組');
define('JS_LANG_Rename', '重新命名');
define('JS_LANG_MailGroup', '郵件組');
define('JS_LANG_RemoveFromGroup', '從分組中移除');
define('JS_LANG_UseImportTo', '從Outlook導入聯係人。');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', '選擇文件 (.CSV 格式)');
define('JS_LANG_Import', '導入');
define('JS_LANG_ContactsMessage', '這是聯係人頁麵!!!');
define('JS_LANG_ContactsCount', '聯係人');
define('JS_LANG_GroupsCount', '組別');

// webmail 4.1 constants
define('PicturesBlocked', '為了安全，此郵件裏的圖片已經被阻止');
define('ShowPictures', '顯示圖片');
define('ShowPicturesFromSender', '總是顯示該發件人的郵件中的圖片');
define('AlwaysShowPictures', '總是顯示郵件中的圖片');

define('TreatAsOrganization', '作為一個團體');

define('WarningGroupAlreadyExist', '這個組名已經存在，請指定另一個名字。');
define('WarningCorrectFolderName', '請指定一個正確的文件夾名字。');
define('WarningLoginFieldBlank', '登陸名不能為空。');
define('WarningCorrectLogin', '請指定一個正確的登陸名');
define('WarningPassBlank', '密碼不能為空。');
define('WarningCorrectIncServer', '請指定一個 POP3(IMAP) 帳號。');
define('WarningCorrectSMTPServer', '請指定一個正確的發郵件帳號。');
define('WarningFromBlank', '發件人不能為空。');
define('WarningAdvancedDateFormat', '請指定一個日期/時間格式');
define('AdvancedDateHelpTitle', '日期（高級）');

define('AdvancedDateHelpIntro', '選擇“高級選項”時, 您可以使用文本框設置您的日期格式, 建議不要在此使用. 格式 \':\' or \'/\' 定界符 char:');
define('AdvancedDateHelpConclusion', '例如, 如果為 "mm/dd/yyyy"（高級選項）, 顯示為： month/day/year (i.e. 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', '月中此曰 (1 - 31)');
define('AdvancedDateHelpNumericMonth', '月 (1 - 12)');
define('AdvancedDateHelpTextualMonth', '月 (Jan - Dec)');
define('AdvancedDateHelpYear2', '年, 2 兩位');
define('AdvancedDateHelpYear4', '年, 4 四位');
define('AdvancedDateHelpDayOfYear', '年中此日 (1 - 366)');
define('AdvancedDateHelpQuarter', '季度');
define('AdvancedDateHelpDayOfWeek', '一周內每天 (Mon - Sun)');
define('AdvancedDateHelpWeekOfYear', '全年的第幾周 (1 - 53)');

define('InfoNoMessagesFound', '沒有找到郵件。');
define('ErrorSMTPConnect', '無法連接到SMTP服務器，請檢查SMTP設置。');
define('ErrorSMTPAuth', '驗證失敗，用戶名或密碼錯誤。');
define('ReportMessageSent', '郵件已經發送。');
define('ReportMessageSaved', '郵件已經保存。');
define('ErrorPOP3Connect', '無法連接POP3服務器，請檢查POP3設置。');
define('ErrorIMAP4Connect', '無法連接 IMAP4 服務器, 請檢查 IMAP4 設置。');
define('ErrorPOP3IMAP4Auth', '驗證失敗，錯誤的登陸名或密碼。');
define('ErrorGetMailLimit', '對不起，您的郵箱大小超出限製。');

define('ReportSettingsUpdatedSuccessfuly', '設置更新成功。');
define('ReportAccountCreatedSuccessfuly', '帳號創建成功。');
define('ReportAccountUpdatedSuccessfuly', '帳號更新成功。');
define('ConfirmDeleteAccount', '確認要刪除此帳號嗎？');
define('ReportFiltersUpdatedSuccessfuly', '過濾器更新成功。');
define('ReportSignatureUpdatedSuccessfuly', '簽名更新成功。');
define('ReportFoldersUpdatedSuccessfuly', '過濾器更新成功');
define('ReportContactsSettingsUpdatedSuccessfuly', '聯係人設置成功。');

define('ErrorInvalidCSV', '您選擇的CSV格式文件無效。');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', '組別');
define('ReportGroupSuccessfulyAdded2', '添加成功。');
define('ReportGroupUpdatedSuccessfuly', '級別更新成功。');
define('ReportContactSuccessfulyAdded', '聯係人添加成功。');
define('ReportContactUpdatedSuccessfuly', '聯係人更新成功。');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', '聯係人已經添加到組。');
define('AlertNoContactsGroupsSelected', '沒有選擇聯係人或組。');

define('InfoListNotContainAddress', '如果在列表中找不到包含的地址，請嚐試輸入第一個字符。');

define('DirectAccess', 'D');
define('DirectAccessTitle', '直接模式， WebMail 直接訪問郵件服務器。');

define('FolderInbox', '收件箱');
define('FolderSentItems', '已發送');
define('FolderDrafts', '草稿箱');
define('FolderTrash', '已刪除');

define('FileLargerAttachment', '文件大小已超過附件大小限製。');
define('FilePartiallyUploaded', '由於一個未知錯誤，文件隻上傳了一部分。');
define('NoFileUploaded', '文件沒有上傳。');
define('MissingTempFolder', '缺少臨時文件夾。');
define('MissingTempFile', '缺少臨時文件夾。');
define('UnknownUploadError', '發生未知文件上傳錯誤。');
define('FileLargerThan', '文件上傳時發生錯誤，可能是您上傳的文件過大。');
define('PROC_CANT_LOAD_DB', '無法連接到數據庫。');
define('PROC_CANT_LOAD_LANG', '沒有找到必需的語言配置文件。');
define('PROC_CANT_LOAD_ACCT', '此帳號不存在，可能是被刪除了。');

define('DomainDosntExist', '服務器上不存在此域名。');
define('ServerIsDisable', '被管理員禁止使用域名服務器。');

define('PROC_ACCOUNT_EXISTS', '帳號創建失敗，此帳號已有人使用。');
define('PROC_CANT_GET_MESSAGES_COUNT', '無法獲取郵件數量。');
define('PROC_CANT_MAIL_SIZE', '無法獲取郵件大小。');

define('Organization', '團體機構');
define('WarningOutServerBlank', '發郵件字段不能為空');

define('JS_LANG_Refresh', '刷新');
define('JS_LANG_MessagesInInbox', '郵件收件箱');
define('JS_LANG_InfoEmptyInbox', '收件箱為空');

// webmail 4.2 constants
define('BackToList', '返回');
define('InfoNoContactsGroups', '沒有聯係人或組。');
define('InfoNewContactsGroups', '您可在新建聯係人/組或者從Outlook導入.CSV 格式文件');
define('DefTimeFormat', '默認時間格式');
define('SpellNoSuggestions', '沒有建議');
define('SpellWait', '請稍等...');

define('InfoNoMessageSelected', '沒有選擇郵件。');
define('InfoSingleDoubleClick', '你可以在列表中單擊郵件進行預覽，或者雙擊查看完整的內容。');
// calendar
define('TitleDay', '查看一天');
define('TitleWeek', '查看一周');
define('TitleMonth', '查看一個月');

define('ErrorNotSupportBrowser', 'Webmail日期不支持您的瀏覽器，請使用FireFox 2.0 或更高版本, Opera 9.0 或更高版本, Internet Explorer 6.0 或更高版本, Safari 3.0.2 或更高版本。');
define('ErrorTurnedOffActiveX', 'ActiveX 被關閉 . <br />請轉到相應的程序。');

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

define('EventSubject', '主題');
define('EventCalendar', '日程');
define('EventFrom', '發件人');
define('EventTill', '抽屜');
define('CalendarDescription', '描述');
define('CalendarColor', '顏色');
define('CalendarName', '日程名稱');
define('CalendarDefaultName', '我的日程');

define('ButtonSave', '保存');
define('ButtonCancel', '取消');
define('ButtonDelete', '刪除');

define('AltPrevMonth', '上一月');
define('AltNextMonth', '下一月');

define('CalendarHeaderEdit', '編輯日程');
define('CalendarActionEdit', '編輯日程');
define('ConfirmDeleteCalendar', '確定要刪除此日程嗎？');
define('InfoDeleting', '正在刪除...');
define('WarningCalendarNameBlank', '日程名稱不能為空');
define('ErrorCalendarNotCreated', '日程沒有創建');
define('WarningSubjectBlank', '主題不能為空。');
define('WarningIncorrectTime', '時間不能包含非法字符。');
define('WarningIncorrectFromTime', '錯誤的發件人時間');
define('WarningIncorrectTillTime', '抽屜時間錯誤');
define('WarningStartEndDate', '結束時間必須大於開始時間。');
define('WarningStartEndTime', '結束時間必須大於開始時間。');
define('WarningIncorrectDate', '請填寫正確的日期。');
define('InfoLoading', '正在加載...');
define('EventCreate', '新建事件');
define('CalendarHideOther', '隱藏其他日程');
define('CalendarShowOther', '顯示其他日程');
define('CalendarRemove', '移除日程');
define('EventHeaderEdit', '編輯事件');

define('InfoSaving', '正在保存...');
define('SettingsDisplayName', '顯示名稱');
define('SettingsTimeFormat', '時間格式');
define('SettingsDateFormat', '日期格式');
define('SettingsShowWeekends', '顯示周末');
define('SettingsWorkdayStarts', '工作日開始');
define('SettingsWorkdayEnds', '結束');
define('SettingsShowWorkday', '顯示工作日');
define('SettingsWeekStartsOn', '每星期啟動');
define('SettingsDefaultTab', '默認標簽');
define('SettingsCountry', '國家');
define('SettingsTimeZone', '時區');
define('SettingsAllTimeZones', '所有時區');

define('WarningWorkdayStartsEnds', ' \'工作日結束\' 必須大於 \'節假日開始\' 時間');
define('ReportSettingsUpdated', '設置更新成功。');

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

define('ErrorParseJSON', '服務器無法解析JSON響應。');

define('ErrorLoadCalendar', '無法加載日程表');
define('ErrorLoadEvents', '無法加載事件表');
define('ErrorUpdateEvent', '無法保存事件');
define('ErrorDeleteEvent', '無法刪除事件');
define('ErrorUpdateCalendar', '無法保存日程');
define('ErrorDeleteCalendar', '無法刪除日程');
define('ErrorGeneral', '服務器發生錯誤，請一會再試。');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', '分享日程表');
define('ShareActionEdit', '分享日程表');
define('CalendarPublicate', '所有人可以看到此日程表');
define('CalendarPublicationLink', '連接');
define('ShareCalendar', '分享此日程表');
define('SharePermission1', '可以更改和管理分享');
define('SharePermission2', '可以更改事件');
define('SharePermission3', '能看所有詳細事件');
define('SharePermission4', '隻可以看 free/busy (隱藏詳細)');
define('ButtonClose', '關閉');
define('WarningEmailFieldFilling', '請先輸入 e-mail ');
define('EventHeaderView', '查看事件');
define('ErrorUpdateSharing', '無法保存分享和發表數據');
define('ErrorUpdateSharing1', '不能分享給 %s 用戶，那是無效的。');
define('ErrorUpdateSharing2', '不可能分享此日程表給用戶 %s');
define('ErrorUpdateSharing3', '此日程表已經分享給用戶 %s');
define('Title_MyCalendars', '我的日程表');
define('Title_SharedCalendars', '分享日程表');
define('ErrorGetPublicationHash', '無法建立發布連接');
define('ErrorGetSharing', '無法添加分享');
define('CalendarPublishedTitle', '此日程表已經發布');
define('RefreshSharedCalendars', '刷新分享日程表');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', '成員');

define('ReportMessagePartDisplayed', '注意：這隻是顯示郵件的一部分。');
define('ReportViewEntireMessage', '查看完整的郵件');
define('ReportClickHere', '點擊這裏');
define('ErrorContactExists', '此聯係人的 e-mail 已經存在。');

define('Attachments', '附件');

define('InfoGroupsOfContact', '選中標記的聯係人已經被標記為這個組的成員。');
define('AlertNoContactsSelected', '沒有選擇聯係人。');
define('MailSelected', '郵箱已選擇地址');
define('CaptionSubscribed', '訂閱');

define('OperationSpam', '垃圾郵件');
define('OperationNotSpam', '不是垃圾郵件');
define('FolderSpam', '垃圾郵件');

// webmail 4.4 contacts
define('ContactMail', '郵件聯係人');
define('ContactViewAllMails', '查看此聯係人的所有郵件');
define('ContactsMailThem', '給他們發郵件');
define('DateToday', '今天');
define('DateYesterday', '昨天');
define('MessageShowDetails', '查看詳細');
define('MessageHideDetails', '隱藏詳細');
define('MessageNoSubject', '沒有主題');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', '發給');
define('SearchClear', '清空搜索');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', ' "#s" 在文件夾 #f 的搜索結果:');
define('SearchResultsInAllFolders', ' "#s" 在所有郵箱文件夾的搜索結果:');
define('AutoresponderTitle', '自動回複');
define('AutoresponderEnable', '啟用自動回複');
define('AutoresponderSubject', '主題');
define('AutoresponderMessage', '郵件');
define('ReportAutoresponderUpdatedSuccessfuly', '自動回複設置更新成功');
define('FolderQuarantine', '隔離');

//calendar
define('EventRepeats', '重複');
define('NoRepeats', '不重複');
define('DailyRepeats', '每天');
define('WorkdayRepeats', '每個工作日 (Mon. - Fri.)');
define('OddDayRepeats', '每個 Mon., Wed. and Fri.');
define('EvenDayRepeats', '每個 Tues. and Thurs.');
define('WeeklyRepeats', '每周');
define('MonthlyRepeats', '每月');
define('YearlyRepeats', '每年');
define('RepeatsEvery', '重複每個');
define('ThisInstance', '僅一次');
define('AllEvents', '所有事件係列');
define('AllFollowing', '所有下列的');
define('ConfirmEditRepeatEvent', '你想更改這一係列事件嗎？');
define('RepeatEventHeaderEdit', '編輯循環事件');
define('First', '第一個');
define('Second', '第二個');
define('Third', '第三個');
define('Fourth', '第四個');
define('Last', '最後一個');
define('Every', '每個');
define('SetRepeatEventEnd', '設置終止日期');
define('NoEndRepeatEvent', '沒有終止日期');
define('EndRepeatEventAfter', '終結');
define('Occurrences', '事件');
define('EndRepeatEventBy', '結束');
define('EventCommonDataTab', '主參數');
define('EventRepeatDataTab', '循環參數');
define('RepeatEventNotPartOfASeries', '此事件已經改變且不再是係列中的一部分');
define('UndoRepeatExclusion', '取消改變為包含在此係列');

define('MonthMoreLink', '%d 更多...');
define('NoNewSharedCalendars', '沒有新日程');
define('NNewSharedCalendars', '找到 %d 個新日程');
define('OneNewSharedCalendars', '找到 1 個新日程');
define('ConfirmUndoOneRepeat', '你想恢複這個事件嗎？');

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
define('WarningUntilDateBlank', '請指定結束循環日期');
define('WarningWrongUntilDate', '結束循環日期必須大於開始循環日期');

define('OnDays', '那一天');
define('CancelRecurrence', '取消循環');
define('RepeatEvent', '重複此事件');

define('Spellcheck', '檢查拚寫');
define('LoginLanguage', '語言');
define('LanguageDefault', '默認');

// webmail 4.5.x new
define('EmptySpam', '清空垃圾郵件');
define('Saving', '正在保存...');
define('Sending', '正在發送...');
define('LoggingOffFromServer', '正從服務器退出...');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', '不能標記為垃圾郵件');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', '不能標記為非垃圾郵件');
define('ExportToICalendar', '導出日程');
define('ErrorMaximumUsersLicenseIsExceeded', '您的用戶數已超出限定範圍。');
define('RepliedMessageTitle', '已回複');
define('ForwardedMessageTitle', '已轉發');
define('RepliedForwardedMessageTitle', '已回複和轉發');
define('ErrorDomainExist', '創建失敗，用戶相應的域名還沒有添加，請先添加域名。');

// webmail 4.7
define('RequestReadConfirmation', '已讀回執');
define('FolderTypeDefault', '默認');
define('ShowFoldersMapping', '使用另一個和係統文件夾一樣的文件夾 (e.g. use MyFolder as Sent Items)');
define('ShowFoldersMappingNote', '例如, 更新“已發送”為我的“新文件夾”, "已發送"指定到“我的文件夾”裏麵。');
define('FolderTypeMapTo', '用於');

define('ReminderEmailExplanation', '此郵件涉及到您的帳號 %EMAIL% ,因您已經安排好事件通知在你的日程： %CALENDAR_NAME%');
define('ReminderOpenCalendar', '打開日程表');

define('AddReminder', '提供');
define('AddReminderBefore', '在此事件之前提醒我 % ');
define('AddReminderAnd', '且 % 之前');
define('AddReminderAlso', '且在 % 之前');
define('AddMoreReminder', '更多提醒');
define('RemoveAllReminders', '移除所有提醒');
define('ReminderNone', '無');
define('ReminderMinutes', '分');
define('ReminderHour', '時');
define('ReminderHours', '時');
define('ReminderDay', '天');
define('ReminderDays', '天');
define('ReminderWeek', '周');
define('ReminderWeeks', '周');
define('Allday', '全天');

define('Folders', '文件夾');
define('NoSubject', '沒有主題');
define('SearchResultsFor', '搜索結果');

define('Back', '返回');
define('Next', '下一個');
define('Prev', '前一個');

define('MsgList', '郵件');
define('Use24HTimeFormat', '使用24小時格式');
define('UseCalendars', '使用日程表');
define('Event', '事件');
define('CalendarSettingsNullLine', '沒有日程');
define('CalendarEventNullLine', '沒有事件');
define('ChangeAccount', '更改帳號');

define('TitleCalendar', '日程表');
define('TitleEvent', '事件');
define('TitleFolders', '文件夾');
define('TitleConfirmation', '確認');

define('Yes', '是');
define('No', '否');

define('EditMessage', '新郵件');

define('AccountNewPassword', '新密碼');
define('AccountConfirmNewPassword', '確認新密碼');
define('AccountPasswordsDoNotMatch', '兩次密碼不匹配。');

define('ContactTitle', '標題');
define('ContactFirstName', '名');
define('ContactSurName', '姓');

define('ContactNickName', '昵稱');

define('CaptchaTitle', '驗證碼');
define('CaptchaReloadLink', '刷新');
define('CaptchaError', '驗證碼不正確。');

define('WarningInputCorrectEmails', '請指定正確的郵件地址。');
define('WrongEmails', '錯誤的郵件地址:');

define('ConfirmBodySize1', '輸入框已經達到最大');
define('ConfirmBodySize2', '您輸入的字符已超過限定長度，要重新編輯請點擊“取消”');
define('BodySizeCounter', '計算器');
define('InsertImage', '插入圖片');
define('ImagePath', '圖片路徑');
define('ImageUpload', '插入');
define('WarningImageUpload', '附上的不是圖片文件，請另選擇一個圖片文件。');

define('ConfirmExitFromNewMessage', '更改沒有保存，確定要離開此頁麵嗎？點“取消”留在本頁麵。');

define('SensivityConfidential', '請把郵件視為機密。');
define('SensivityPrivate', '請把郵件視為不公開的');
define('SensivityPersonal', '請把郵件視為親啟');

define('ReturnReceiptTopText', '當您收到此郵件的時候，發件人也收到了通知。');
define('ReturnReceiptTopLink', '單擊這裏通知發件人');
define('ReturnReceiptSubject', '回執 (已顯示)');
define('ReturnReceiptMailText1', '這是一個您已發出郵件的回執');
define('ReturnReceiptMailText2', '注意: 此回執僅承認郵件已經在接收者的電腦上顯示，但不保證接收者已經閱讀或者理解郵件內容。');
define('ReturnReceiptMailText3', '包含主題');

define('SensivityMenu', '敏感');
define('SensivityNothingMenu', '無');
define('SensivityConfidentialMenu', '機密');
define('SensivityPrivateMenu', '隱私');
define('SensivityPersonalMenu', '個人');

define('ErrorLDAPonnect', '無法連接到LDAP服務器。');

define('MessageSizeExceedsAccountQuota', '此郵件大小已超過您的郵箱配額');
define('MessageCannotSent', '無法發送此郵件。');
define('MessageCannotSaved', '無法保存此郵件。');

define('ContactFieldTitle', '字段');
define('ContactDropDownTO', '收件人');
define('ContactDropDownCC', '抄送');
define('ContactDropDownBCC', '密送');

// 4.9
define('NoMoveDelete', '郵件無法移動到垃圾箱，可能郵件已經滿，要將此郵件刪除嗎？');

define('WarningFieldBlank', '此字段不能為空。');
define('WarningPassNotMatch', '密碼不匹配，請查檢。');
define('PasswordResetTitle', '取回密碼 - step %d');
define('NullUserNameonReset', '用戶');
define('IndexResetLink', '忘記密碼？');
define('IndexRegLink', '注冊新帳號');

define('RegDomainNotExist', '密碼不存在。');
define('RegAnswersIncorrect', '答案錯誤。');
define('RegUnknownAdress', '未知郵件地址。');
define('RegUnrecoverableAccount', '此郵件無法申請取回密碼。');
define('RegAccountExist', '此郵件地址已經被使用。');
define('RegRegistrationTitle', '注冊新帳號');
define('RegName', '名字');
define('RegEmail', 'e-mail 地址');
define('RegEmailDesc', '例如, myname@domain.com. 此帳號用於登陸係統。');
define('RegSignMe', '記住用戶名');
define('RegSignMeDesc', '下次自動在此電腦登陸。');
define('RegPass1', '密碼');
define('RegPass2', '重複密碼 ');
define('RegQuestionDesc', '請提供兩個機密的問題和答案，萬一忘記密碼，你可以使用此答案找回密碼。');
define('RegQuestion1', '機密問題 1');
define('RegAnswer1', '答案 1');
define('RegQuestion2', '機密問題 2');
define('RegAnswer2', '答案 2');
define('RegTimeZone', '時區');
define('RegLang', '界麵語言');
define('RegCaptcha', '驗證碼');
define('RegSubmitButtonValue', '注冊');

define('ResetEmail', '請提供您的E-mail');
define('ResetEmailDesc', '此E-mail已經注冊過了。');
define('ResetCaptcha', '驗證碼');
define('ResetSubmitStep1', '提交');
define('ResetQuestion1', '機密問題 1');
define('ResetAnswer1', '答案');
define('ResetQuestion2', '機密問題 2');
define('ResetAnswer2', '答案');
define('ResetSubmitStep2', '提交');

define('ResetTopDesc1Step2', '郵件地址');
define('ResetTopDesc2Step2', '確認地址');

define('ResetTopDescStep3', '請輸入新密碼。');

define('ResetPass1', '新密碼');
define('ResetPass2', '重複密碼');
define('ResetSubmitStep3', '提交');
define('ResetDescStep4', '您的密碼已經成功更改。');
define('ResetSubmitStep4', '返回');

define('RegReturnLink', '返回登陸窗口');
define('ResetReturnLink', '返回登陸窗口');

// Appointments
define('AppointmentAddGuests', '添加來賓');
define('AppointmentRemoveGuests', '取消會議');
define('AppointmentListEmails', '輸入郵件地址用逗號隔開然後點保存。');
define('AppointmentParticipants', '參與者');
define('AppointmentRefused', '被拒絕');
define('AppointmentAwaitingResponse', '等等回應');
define('AppointmentInvalidGuestEmail', '以下來賓地址無效:');
define('AppointmentOwner', '所有者');

define('AppointmentMsgTitleInvite', '邀請參加活動');
define('AppointmentMsgTitleUpdate', '活動已修改');
define('AppointmentMsgTitleCancel', '活動被取消。');
define('AppointmentMsgTitleRefuse', '來賓 %guest% 拒絕邀請。');
define('AppointmentMoreInfo', '更多信息');
define('AppointmentOrganizer', '組織者');
define('AppointmentEventInformation', '活動信息');
define('AppointmentEventWhen', '當');
define('AppointmentEventParticipants', '參與者');
define('AppointmentEventDescription', '描述');
define('AppointmentEventWillYou', '您參與嗎？');
define('AppointmentAdditionalParameters', '附加參數');
define('AppointmentHaventRespond', '無響應');
define('AppointmentRespondYes', '我將參與');
define('AppointmentRespondMaybe', '不確認');
define('AppointmentRespondNo', '不參與');
define('AppointmentGuestsChangeEvent', '來賓可以修改活動');

define('AppointmentSubjectAddStart', '您收到活動邀請 ');
define('AppointmentSubjectAddFrom', ' 發件人 ');
define('AppointmentSubjectUpdateStart', '修改活動');
define('AppointmentSubjectDeleteStart', '取消活動 ');
define('ErrorAppointmentChangeRespond', '無法修改約會響應');
define('SettingsAutoAddInvitation', '自動添加邀請進日程表');
define('ReportEventSaved', '您的活動已保存');
define('ReportAppointmentSaved', ' and notifications were sent');
define('ErrorAppointmentSend', '無法發送邀請。');
define('AppointmentEventName', '名稱:');

// End appointments

define('ErrorCantUpdateFilters', '無法更新過濾器');

define('FilterPhrase', '如果 %field 郵件頭 %condition %string 就 %action');
define('FiltersAdd', '添加過濾');
define('FiltersCondEqualTo', '等於');
define('FiltersCondContainSubstr', '包含');
define('FiltersCondNotContainSubstr', '不包含');
define('FiltersActionDelete', '刪除郵件');
define('FiltersActionMove', '移動');
define('FiltersActionToFolder', '到 %folder ');
define('FiltersNo', '沒有指定過濾');

define('ReminderEmailFriendly', '提醒');
define('ReminderEventBegin', '開始於: ');

define('FiltersLoading', '加載過濾...');
define('ConfirmMessagesPermanentlyDeleted', '此文件夾所有郵件將被刪除');

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
