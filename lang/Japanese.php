<?php
define('PROC_ERROR_ACCT_CREATE', 'アカウント作成中にエラーが発生しました');
define('PROC_WRONG_ACCT_PWD', 'アカウントパスワードが間違っています');
define('PROC_CANT_LOG_NONDEF', '既定のアカウントでないアカウントへのログインはできません');
define('PROC_CANT_INS_NEW_FILTER', '新しいフィルタの作成に失敗しました');
define('PROC_FOLDER_EXIST', '指定されたフォルダ名称は既に存在しています');
define('PROC_CANT_CREATE_FLD', 'フォルダの作成に失敗しました');
define('PROC_CANT_INS_NEW_GROUP', '新しいグループの作成に失敗しました');
define('PROC_CANT_INS_NEW_CONT', '新しい連絡先の作成に失敗しました');
define('PROC_CANT_INS_NEW_CONTS', '新しい連絡先の作成に失敗しました');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'グループへの新しい連絡先の追加に失敗しました');
define('PROC_ERROR_ACCT_UPDATE', 'アカウントの更新時にエラーが発生しました');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'アカウント設定の更新時にエラーが発生しました');
define('PROC_CANT_GET_SETTINGS', 'アカウント設定の取得に失敗しました');
define('PROC_CANT_UPDATE_ACCT', 'アカウント設定の更新に失敗しました');
define('PROC_ERROR_DEL_FLD', 'フォルダの削除時にエラーが発生しました');
define('PROC_CANT_UPDATE_CONT', '連絡先の更新に失敗しました');
define('PROC_CANT_GET_FLDS', 'フォルダ階層の取得に失敗しました');
define('PROC_CANT_GET_MSG_LIST', 'メッセージ一覧の取得に失敗しました');
define('PROC_MSG_HAS_DELETED', '選択されたメールは既にサーバから削除されています');
define('PROC_CANT_LOAD_CONT_SETTINGS', '連絡先設定の読込に失敗しました');
define('PROC_CANT_LOAD_SIGNATURE', 'アカウント署名の読込に失敗しました');
define('PROC_CANT_GET_CONT_FROM_DB', '連絡先のDB読込に失敗しました');
define('PROC_CANT_GET_CONTS_FROM_DB', '連絡先のDB読込に失敗しました');
define('PROC_CANT_DEL_ACCT_BY_ID', 'アカウントの削除に失敗しました');
define('PROC_CANT_DEL_FILTER_BY_ID', 'フィルタの削除に失敗しました');
define('PROC_CANT_DEL_CONT_GROUPS', '連絡先またはグループの作成に失敗しました');
define('PROC_WRONG_ACCT_ACCESS', '第三者によるアクセスを検知しました');
define('PROC_SESSION_ERROR', 'タイムアウトによりセッションが切断されました');

define('MailBoxIsFull', '受信トレイが一杯です');
define('WebMailException', 'サーバの内部エラーが発生しました。システム管理者に連絡して下さい。');
define('InvalidUid', 'メッセージUIDが不正です');
define('CantCreateContactGroup', '連絡先グループの作成に失敗しました');
define('CantCreateUser', 'ユーザの作成に失敗しました');
define('CantCreateAccount', 'アカウントの作成にしました');
define('SessionIsEmpty', 'セッションが無効です');
define('FileIsTooBig', 'ファイルが大きすぎます');

define('PROC_CANT_MARK_ALL_MSG_READ', '全てのメッセージを既読にする事に失敗しました');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', '全てのメッセージを未読にする事に失敗しました');
define('PROC_CANT_PURGE_MSGS', 'メッセージの消去に失敗しました');
define('PROC_CANT_DEL_MSGS', 'メッセージの削除に失敗しました');
define('PROC_CANT_UNDEL_MSGS', 'メッセージの削除取消に失敗しました');
define('PROC_CANT_MARK_MSGS_READ', 'メッセージを既読にする事に失敗しました');
define('PROC_CANT_MARK_MSGS_UNREAD', 'メッセージを既読にする事に失敗しました');
define('PROC_CANT_SET_MSG_FLAGS', 'メッセージにフラグを付ける事に失敗しました');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'メッセージのフラグ解除に失敗しました');
define('PROC_CANT_CHANGE_MSG_FLD', 'メッセージの移動に失敗しました');
define('PROC_CANT_SEND_MSG', 'メッセージの送信に失敗しました');
define('PROC_CANT_SAVE_MSG', 'メッセージの保存に失敗しました');
define('PROC_CANT_GET_ACCT_LIST', 'アカウント一覧の取得に失敗しました');
define('PROC_CANT_GET_FILTER_LIST', 'フィルタ一覧の取得に失敗しました');

define('PROC_CANT_LEAVE_BLANK', '必須項目がブランクです');

define('PROC_CANT_UPD_FLD', 'フォルダの更新に失敗しました');
define('PROC_CANT_UPD_FILTER', 'フィルタの更新に失敗しました');

define('ACCT_CANT_ADD_DEF_ACCT', 'このアカウントは他ユーザの規定のアカウントに設定されている為、アカウントの追加に失敗しました');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'このアカウント設定は初期値への変更ができません');
define('ACCT_CANT_CREATE_IMAP_ACCT', '新しいアカウントの作成に失敗しました (IMAP4 接続エラー)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', '既定のアカウントは最低でも１つ必要です');

define('LANG_LoginInfo', 'ログイン情報');
define('LANG_Email', 'メール');
define('LANG_Login', 'ログイン');
define('LANG_Password', 'パスワード');
define('LANG_IncServer', '受信サーバ');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'ポート');
define('LANG_OutServer', '送信サーバ');
define('LANG_OutPort', 'ポート');
define('LANG_UseSmtpAuth', 'SMTP-authを使用する');
define('LANG_SignMe', '次回から自動的にログインする');
define('LANG_Enter', 'ログイン');

// interface strings

define('JS_LANG_TitleLogin', 'ログイン');
define('JS_LANG_TitleMessagesListView', 'メッセージ一覧');
define('JS_LANG_TitleMessagesList', 'メッセージ一覧');
define('JS_LANG_TitleViewMessage', 'メッセージを見る');
define('JS_LANG_TitleNewMessage', 'メール作成');
define('JS_LANG_TitleSettings', '設定');
define('JS_LANG_TitleContacts', 'アドレス帳');

define('JS_LANG_StandardLogin', '通常ログイン');
define('JS_LANG_AdvancedLogin', 'オプションログイン');

define('JS_LANG_InfoWebMailLoading', 'WebMail の ロード中');
define('JS_LANG_Loading', 'ロード中');
define('JS_LANG_InfoMessagesLoad', 'メッセージ一覧のロード中');
define('JS_LANG_InfoEmptyFolder', 'フォルダが空です');
define('JS_LANG_InfoPageLoading', 'ページの読み込み中&hellip;');
define('JS_LANG_InfoSendMessage', 'メッセージ送信完了');
define('JS_LANG_InfoSaveMessage', 'メッセージ保存完了');
define('JS_LANG_InfoHaveImported', 'インポート完了');
define('JS_LANG_InfoNewContacts', '連絡先リストへ連絡先を追加しました');
define('JS_LANG_InfoToDelete', '削除 ');
define('JS_LANG_InfoDeleteContent', 'フォルダを削除するにはフォルダを空にして下さい');
define('JS_LANG_InfoDeleteNotEmptyFolders', '空でないフォルダは削除できません。フォルダを空にしてから削除して下さい。');
define('JS_LANG_InfoRequiredFields', '* 必須項目');

define('JS_LANG_ConfirmAreYouSure', 'よろしいですか？');
define('JS_LANG_ConfirmDirectModeAreYouSure', '選択されたメッセージは完全に消去されます。よろしいですか？');
define('JS_LANG_ConfirmSaveSettings', '設定は保存されていません。保存するには「OK」を押して下さい。');
define('JS_LANG_ConfirmSaveContactsSettings', '連絡先設定の変更は保存されていません。保存するには「OK」を押して下さい。');
define('JS_LANG_ConfirmSaveAcctProp', 'アカウントの変更は保存されていません。保存するには「OK」を押して下さい。');
define('JS_LANG_ConfirmSaveFilter', 'フィルタの変更は保存されていません。保存するには「OK」を押して下さい。');
define('JS_LANG_ConfirmSaveSignature', '署名の変更は保存されていません。保存するには「OK」を押して下さい。');
define('JS_LANG_ConfirmSavefolders', 'フォルダの変更は保存されていません。保存するには「OK」を押して下さい。');
define('JS_LANG_ConfirmHtmlToPlain', '警告：設定をHTMLからテキストに変更した場合、現在の書式は全て失われます。続ける場合「OK」を押して下さい。');
define('JS_LANG_ConfirmAddFolder', 'フォルダを追加/削除する場合、変更の保存が必要です。保存するには「OK」を押して下さい。');
define('JS_LANG_ConfirmEmptySubject', '題名が未設定です。よろしいですか？');

define('JS_LANG_WarningEmailBlank', '「メールアドレス」が<br />未入力です');
define('JS_LANG_WarningLoginBlank', '「ログインID」が<br />未入力です');
define('JS_LANG_WarningToBlank', '「宛先」が未入力です');
define('JS_LANG_WarningServerPortBlank', '「POP3」と<br />「SMTP」サーバ名/ポートが未入力です');
define('JS_LANG_WarningEmptySearchLine', '検索ボックスが未入力です。検索したい項目を入力して下さい。');
define('JS_LANG_WarningMarkListItem', '最低でも1つのメッセージを選択して下さい');
define('JS_LANG_WarningFolderMove', '階層が異なる為、フォルダの移動に失敗しました');
define('JS_LANG_WarningContactNotComplete', 'メールアドレスまたは名前を入力して下さい');
define('JS_LANG_WarningGroupNotComplete', 'グループ名称を入力して下さい');

define('JS_LANG_WarningEmailFieldBlank', '「メールアドレス」が未入力です');
define('JS_LANG_WarningIncServerBlank', '「POP3/IMAP4 サーバ」が未入力です');
define('JS_LANG_WarningIncPortBlank', '「POP3/IMAP4 ポート」が未入力です');
define('JS_LANG_WarningIncLoginBlank', '「POP3/IMAP4 ログインID」が未入力です');
define('JS_LANG_WarningIncPortNumber', '「POP3/IMAP4 ポート」には数字を指定して下さい');
define('JS_LANG_DefaultIncPortNumber', '既定のPOP3(IMAP4)ポート番号は 110(143) です');
define('JS_LANG_WarningIncPassBlank', '「POP3/IMAP4/ パスワード」が未入力です');
define('JS_LANG_WarningOutPortBlank', '「送信サーバ ポート」が未入力です');
define('JS_LANG_WarningOutPortNumber', '「SMTP ポート」は数字を指定して下さい');
define('JS_LANG_WarningCorrectEmail', '正しいメールアドレスを指定して下さい');
define('JS_LANG_DefaultOutPortNumber', '既定のSMTPポート番号は 25 です');

define('JS_LANG_WarningCsvExtention', '拡張子は.csvを指定して下さい');
define('JS_LANG_WarningImportFileType', '連絡先をコピーしたいアプリケーションを選択して下さい');
define('JS_LANG_WarningEmptyImportFile', '「参照」ボタンを押してファイルを選択して下さい');

define('JS_LANG_WarningContactsPerPage', '1ページに表示する連絡先は正の数を指定して下さい');
define('JS_LANG_WarningMessagesPerPage', '1ページに表示するメッセージは正の数を指定して下さい');
define('JS_LANG_WarningMailsOnServerDays', 'サーバに残す日数は正の数を指定して下さい');
define('JS_LANG_WarningEmptyFilter', '文字列を指定して下さい');
define('JS_LANG_WarningEmptyFolderName', 'フォルダ名称を指定して下さい');

define('JS_LANG_ErrorConnectionFailed', '接続の確立に失敗しました');
define('JS_LANG_ErrorRequestFailed', 'データの転送は正しく完了しませんでした');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'XMLHttpRequestがありません');
define('JS_LANG_ErrorWithoutDesc', 'without descriptionエラーが発生しました');
define('JS_LANG_ErrorParsing', 'XML解析中にエラーが発生しました');
define('JS_LANG_ResponseText', '応答テキスト:');
define('JS_LANG_ErrorEmptyXmlPacket', 'XMLパケットが空です');
define('JS_LANG_ErrorImportContacts', '連絡先のインポート中にエラーが発生しました');
define('JS_LANG_ErrorNoContacts', 'インポート可能な連絡先がみつかりませんでした');
define('JS_LANG_ErrorCheckMail', 'メッセージ受信中にエラーが発生しました。全てのメッセージの受信が完了していない可能性があります。');

define('JS_LANG_LoggingToServer', 'サーバへ接続中&hellip;');
define('JS_LANG_GettingMsgsNum', 'メッセージ取得中');
define('JS_LANG_RetrievingMessage', 'メッセージ検索中');
define('JS_LANG_DeletingMessage', 'メッセージ削除中');
define('JS_LANG_DeletingMessages', 'メッセージ検索中');
define('JS_LANG_Of', 'of');
define('JS_LANG_Connection', 'コネクション');
define('JS_LANG_Charset', '文字セット');
define('JS_LANG_AutoSelect', '自動認識');

define('JS_LANG_Contacts', 'アドレス帳');
define('JS_LANG_ClassicVersion', 'クラシックバージョン');
define('JS_LANG_Logout', 'ログアウト');
define('JS_LANG_Settings', '設定');

define('JS_LANG_LookFor', '検索キーワード: ');
define('JS_LANG_SearchIn', '検索対象: ');
define('JS_LANG_QuickSearch', '「差出人」「宛先」「件名」のみ検索対象とする(速度重視)');
define('JS_LANG_SlowSearch', 'メッセージ全体を対象とする');
define('JS_LANG_AllMailFolders', '全てのメールフォルダ');
define('JS_LANG_AllGroups', '全てのグループ');

define('JS_LANG_NewMessage', 'メール作成');
define('JS_LANG_CheckMail', 'メール受信');
define('JS_LANG_EmptyTrash', 'ゴミ箱を空にする');
define('JS_LANG_MarkAsRead', '既読にする');
define('JS_LANG_MarkAsUnread', '未読にする');
define('JS_LANG_MarkFlag', 'フラッグを付ける');
define('JS_LANG_MarkUnflag', 'フラッグを解除');
define('JS_LANG_MarkAllRead', '全てを既読にする');
define('JS_LANG_MarkAllUnread', '全てを未読にする');
define('JS_LANG_Reply', '返信');
define('JS_LANG_ReplyAll', '全員に返信');
define('JS_LANG_Delete', '削除');
define('JS_LANG_Undelete', '削除取消');
define('JS_LANG_PurgeDeleted', '削除済みの消去');
define('JS_LANG_MoveToFolder', 'フォルダへ移動');
define('JS_LANG_Forward', '転送');

define('JS_LANG_HideFolders', 'フォルダを隠す');
define('JS_LANG_ShowFolders', 'フォルダを表示');
define('JS_LANG_ManageFolders', 'フォルダ管理');
define('JS_LANG_SyncFolder', 'フォルダを同期');
define('JS_LANG_NewMessages', '新着メッセージ');
define('JS_LANG_Messages', 'メッセージ');

define('JS_LANG_From', '差出人');
define('JS_LANG_To', '宛先');
define('JS_LANG_Date', '日付');
define('JS_LANG_Size', 'サイズ');
define('JS_LANG_Subject', '件名');

define('JS_LANG_FirstPage', '最初のページ');
define('JS_LANG_PreviousPage', '前のページ');
define('JS_LANG_NextPage', '次のページ');
define('JS_LANG_LastPage', '最後のページ');

define('JS_LANG_SwitchToPlain', 'テキストモードへ変更');
define('JS_LANG_SwitchToHTML', 'HTMLモードへ変更');
define('JS_LANG_AddToAddressBook', '連絡先に追加');
define('JS_LANG_ClickToDownload', 'DOWNLOAD');
define('JS_LANG_View', 'ビュー');
define('JS_LANG_ShowFullHeaders', '全てのヘッダを表示');
define('JS_LANG_HideFullHeaders', 'ヘッダを隠す');

define('JS_LANG_MessagesInFolder', '通のメッセージ');
define('JS_LANG_YouUsing', '使用中');
define('JS_LANG_OfYour', ' / ');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', '送信');
define('JS_LANG_SaveMessage', '保存');
define('JS_LANG_Print', '印刷');
define('JS_LANG_PreviousMsg', '前のメッセージ');
define('JS_LANG_NextMsg', '次のメッセージ');
define('JS_LANG_AddressBook', 'アドレス帳');
define('JS_LANG_ShowBCC', 'BCCを表示');
define('JS_LANG_HideBCC', 'BCCを非表示');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Reply&nbsp;To');
define('JS_LANG_AttachFile', '添付ファイル');
define('JS_LANG_Attach', '添付');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Original Message');
define('JS_LANG_Sent', '送信済み');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', '低');
define('JS_LANG_Normal', '標準');
define('JS_LANG_High', '高');
define('JS_LANG_Importance', '重要度');
define('JS_LANG_Close', '閉じる');

define('JS_LANG_Common', '共通');
define('JS_LANG_EmailAccounts', 'メールアカウント');

define('JS_LANG_MsgsPerPage', '1ページの表示メッセージ数');
define('JS_LANG_DisableRTE', 'rich-textエディタを使用不可にする');
define('JS_LANG_Skin', 'スキン');
define('JS_LANG_DefCharset', '文字セット');
define('JS_LANG_DefCharsetInc', '受信文字セット');
define('JS_LANG_DefCharsetOut', '送信文字セット');
define('JS_LANG_DefTimeOffset', 'タイムゾーン');
define('JS_LANG_DefLanguage', '言語');
define('JS_LANG_DefDateFormat', '日付形式');
define('JS_LANG_ShowViewPane', 'プレビューの表示');
define('JS_LANG_Save', '保存');
define('JS_LANG_Cancel', 'キャンセル');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', '削除');
define('JS_LANG_AddNewAccount', '新規アカウントの追加');
define('JS_LANG_Signature', '署名');
define('JS_LANG_Filters', 'フィルタ');
define('JS_LANG_Properties', 'プロパティ');
define('JS_LANG_UseForLogin', 'このアカウントをログイン時のアカウント（ログインID/パスワード）に指定する');
define('JS_LANG_MailFriendlyName', '名前');
define('JS_LANG_MailEmail', 'メールアドレス');
define('JS_LANG_MailIncHost', '受信サーバ');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'ポート');
define('JS_LANG_MailIncLogin', 'ログインID');
define('JS_LANG_MailIncPass', 'パスワード');
define('JS_LANG_MailOutHost', '送信サーバ');
define('JS_LANG_MailOutPort', 'ポート');
define('JS_LANG_MailOutLogin', 'SMTP ログインID');
define('JS_LANG_MailOutPass', 'SMTP パスワード');
define('JS_LANG_MailOutAuth1', 'SMTP-authを使用する');
define('JS_LANG_MailOutAuth2', 'POP3/IMAP4のログインID/パスワードと同一の場合、省略可)');
define('JS_LANG_UseFriendlyNm1', '宛先に名称を利用する');
define('JS_LANG_UseFriendlyNm2', '(名称 &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'ログイン時にメールの同期を行う');
define('JS_LANG_MailMode0', '受信したメッセージをサーバから削除する');
define('JS_LANG_MailMode1', 'サーバにメッセージを保存');
define('JS_LANG_MailMode2', 'サーバに');
define('JS_LANG_MailsOnServerDays', '日間メールを保存する');
define('JS_LANG_MailMode3', 'ゴミ箱から削除したらサーバからも削除する');
define('JS_LANG_InboxSyncType', '受信トレイの同期方法');

define('JS_LANG_SyncTypeNo', '同期しない');
define('JS_LANG_SyncTypeNewHeaders', '新しいヘッダのみ');
define('JS_LANG_SyncTypeAllHeaders', '全てのヘッダ');
define('JS_LANG_SyncTypeNewMessages', '新しいメッセージのみ');
define('JS_LANG_SyncTypeAllMessages', '全てのメッセージ');
define('JS_LANG_SyncTypeDirectMode', 'ダイレクトモード');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'ヘッダのみ取得');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'メッセージ全体を取得');
define('JS_LANG_Pop3SyncTypeDirectMode', 'ダイレクトモード');

define('JS_LANG_DeleteFromDb', 'メールサーバに存在しない場合、DBからもメッセージを消去する');

define('JS_LANG_EditFilter', 'フィルタの編集');
define('JS_LANG_NewFilter', '新規フィルタの追加');
define('JS_LANG_Field', '項目');
define('JS_LANG_Condition', '状態');
define('JS_LANG_ContainSubstring', '次の文字列を含む');
define('JS_LANG_ContainExactPhrase', '次の完全な文字列を含む');
define('JS_LANG_NotContainSubstring', '次の文字列を含まない');
define('JS_LANG_FilterDesc_At', 'at');
define('JS_LANG_FilterDesc_Field', '項目');
define('JS_LANG_Action', 'アクション');
define('JS_LANG_DoNothing', '何もしない');
define('JS_LANG_DeleteFromServer', 'すぐにサーバから消去');
define('JS_LANG_MarkGrey', '網掛け表示する');
define('JS_LANG_Add', '追加');
define('JS_LANG_OtherFilterSettings', '他のフィルタ設定');
define('JS_LANG_ConsiderXSpam', 'X-Spamヘッダを考慮する');
define('JS_LANG_Apply', '適用');

define('JS_LANG_InsertLink', 'リンクを挿入');
define('JS_LANG_RemoveLink', 'リンクを削除');
define('JS_LANG_Numbering', '段落番号');
define('JS_LANG_Bullets', '箇条書き');
define('JS_LANG_HorizontalLine', '水平線');
define('JS_LANG_Bold', '太字');
define('JS_LANG_Italic', 'イタリック');
define('JS_LANG_Underline', '下線');
define('JS_LANG_AlignLeft', '左寄せ');
define('JS_LANG_Center', '中央寄せ');
define('JS_LANG_AlignRight', '右寄せ');
define('JS_LANG_Justify', '均等割り');
define('JS_LANG_FontColor', '文字色');
define('JS_LANG_Background', '背景色');
define('JS_LANG_SwitchToPlainMode', 'テキストモードへ変更');
define('JS_LANG_SwitchToHTMLMode', 'HTMLモードへ変更');

define('JS_LANG_Folder', 'フォルダ');
define('JS_LANG_Msgs', 'メッセージ数');
define('JS_LANG_Synchronize', '同期');
define('JS_LANG_ShowThisFolder', 'このフォルダを表示');
define('JS_LANG_Total', '計');
define('JS_LANG_DeleteSelected', '選択フォルダを削除');
define('JS_LANG_AddNewFolder', '新規フォルダを追加');
define('JS_LANG_NewFolder', '新規フォルダ');
define('JS_LANG_ParentFolder', '上位のフォルダ');
define('JS_LANG_NoParent', '-- 最上位に追加 --');
define('JS_LANG_FolderName', 'フォルダ名');

define('JS_LANG_ContactsPerPage', '1ページの表示連絡先数');
define('JS_LANG_WhiteList', 'ホワイトリスト');

define('JS_LANG_CharsetDefault', 'Default');
define('JS_LANG_CharsetArabicAlphabetISO', 'Arabic Alphabet (ISO)');
define('JS_LANG_CharsetArabicAlphabet', 'Arabic Alphabet (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', 'Baltic Alphabet (ISO)');
define('JS_LANG_CharsetBalticAlphabet', 'Baltic Alphabet (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', 'Central European Alphabet (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', 'Central European Alphabet (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', 'Chinese Simplified (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', 'Chinese Simplified (GB2312)');
define('JS_LANG_CharsetChineseTraditional', 'Chinese Traditional (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', 'Cyrillic Alphabet (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', 'Cyrillic Alphabet (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', 'Cyrillic Alphabet (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', 'Greek Alphabet (ISO)');
define('JS_LANG_CharsetGreekAlphabet', 'Greek Alphabet (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', 'Hebrew Alphabet (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', 'Hebrew Alphabet (Windows)');
define('JS_LANG_CharsetJapanese', 'Japanese');
define('JS_LANG_CharsetJapaneseShiftJIS', 'Japanese (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', 'Korean (EUC)');
define('JS_LANG_CharsetKoreanISO', 'Korean (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', 'Latin 3 Alphabet (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', 'Turkish Alphabet');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Universal Alphabet (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Universal Alphabet (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', 'Vietnamese Alphabet (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', 'Western Alphabet (ISO)');
define('JS_LANG_CharsetWesternAlphabet', 'Western Alphabet (Windows)');

define('JS_LANG_TimeDefault', 'Default');
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
define('JS_LANG_TimeBeijing', 'Beijing, Chongqing, Hong Kong SAR, Urumqi');
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
define('JS_LANG_DateAdvanced', 'Advanced');

define('JS_LANG_NewContact', '連絡先の追加');
define('JS_LANG_NewGroup', 'グループの追加');
define('JS_LANG_AddContactsTo', '以下へ連絡先を追加');
define('JS_LANG_ImportContacts', '連絡先のインポート');

define('JS_LANG_Name', '名前');
define('JS_LANG_Email', 'メールアドレス');
define('JS_LANG_DefaultEmail', 'メールアドレス');
define('JS_LANG_NotSpecifiedYet', '未分類');
define('JS_LANG_ContactName', '名前');
define('JS_LANG_Birthday', '誕生日');
define('JS_LANG_Month', '月');
define('JS_LANG_January', '1月');
define('JS_LANG_February', '2月');
define('JS_LANG_March', '3月');
define('JS_LANG_April', '4月');
define('JS_LANG_May', '5月');
define('JS_LANG_June', '6月');
define('JS_LANG_July', '7月');
define('JS_LANG_August', '8月');
define('JS_LANG_September', '9月');
define('JS_LANG_October', '10月');
define('JS_LANG_November', '11月');
define('JS_LANG_December', '12月');
define('JS_LANG_Day', '日');
define('JS_LANG_Year', '年');
define('JS_LANG_UseFriendlyName1', '宛先に名称を利用する');
define('JS_LANG_UseFriendlyName2', '(ex, 山田太郎 &lt;yamada@mail.com&gt;)');
define('JS_LANG_Personal', 'Personal');
define('JS_LANG_PersonalEmail', '個人メールアドレス');
define('JS_LANG_StreetAddress', '番地');
define('JS_LANG_City', '市区町村');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', '都道府県');
define('JS_LANG_Phone', 'TEL');
define('JS_LANG_ZipCode', '郵便番号');
define('JS_LANG_Mobile', '携帯番号');
define('JS_LANG_CountryRegion', '国/地域');
define('JS_LANG_WebPage', 'ホームページ');
define('JS_LANG_Go', 'Go');
define('JS_LANG_Home', '個人');
define('JS_LANG_Business', '会社');
define('JS_LANG_BusinessEmail', '会社メールアドレス');
define('JS_LANG_Company', '会社名');
define('JS_LANG_JobTitle', '役職');
define('JS_LANG_Department', '部署');
define('JS_LANG_Office', 'オフィス');
define('JS_LANG_Pager', 'Pager');
define('JS_LANG_Other', 'その他');
define('JS_LANG_OtherEmail', 'その他のメールアドレス');
define('JS_LANG_Notes', 'メモ');
define('JS_LANG_Groups', 'グループ');
define('JS_LANG_ShowAddFields', 'オプション項目を表示する');
define('JS_LANG_HideAddFields', 'オプション項目を隠す');
define('JS_LANG_EditContact', '連絡先情報を編集');
define('JS_LANG_GroupName', 'グループ名');
define('JS_LANG_AddContacts', '連絡先を追加');
define('JS_LANG_CommentAddContacts', '(複数のメールアドレスを指定する場合、カンマで区切って入力して下さい)');
define('JS_LANG_CreateGroup', 'グループの追加');
define('JS_LANG_Rename', '名前の変更');
define('JS_LANG_MailGroup', 'メールグループ');
define('JS_LANG_RemoveFromGroup', 'このグループから削除');
define('JS_LANG_UseImportTo', 'Microsoft Outlook, Microsoft Outlook Express のアドレス帳を WebMailの連絡先として インポート');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'インポートしたいファイル (.CSV 形式) を選択して下さい');
define('JS_LANG_Import', 'インポート');
define('JS_LANG_ContactsMessage', '連絡先のページです!!!');
define('JS_LANG_ContactsCount', '連絡先');
define('JS_LANG_GroupsCount', 'グループ');

// webmail 4.1 constants
define('PicturesBlocked', '安全性確保の為、このメッセージに含まれている画像は無効にされました');
define('ShowPictures', '画像を表示する');
define('ShowPicturesFromSender', 'この差出人からのメッセージは常に画像を表示する');
define('AlwaysShowPictures', '常に画像を表示する');

define('TreatAsOrganization', '会社として扱う');

define('WarningGroupAlreadyExist', '同名のグループが既に存在します。他の名称を指定して下さい。');
define('WarningCorrectFolderName', '正しいフォルダ名称を指定して下さい');
define('WarningLoginFieldBlank', '「ログインID」が未入力です');
define('WarningCorrectLogin', '正しいログインIDを指定して下さい');
define('WarningPassBlank', '「パスワード」が未入力です');
define('WarningCorrectIncServer', '正しい POP3(IMAP) サーバ名を指定して下さい');
define('WarningCorrectSMTPServer', '正しい送信メールアドレスを指定して下さい');
define('WarningFromBlank', '「差出人」が未入力です');
define('WarningAdvancedDateFormat', '正しい日付形式を指定して下さい');

define('AdvancedDateHelpTitle', 'カスタム日付');
define('AdvancedDateHelpIntro', '「カスタム日付」が選択された場合、日付形式をテキストボックスに指定して下さい。日付形式には \':\' か \'/\' が利用可能です。');
define('AdvancedDateHelpConclusion', '例えば、テキストボックスに &quot;mm/dd/yyyy&quot; を指定した場合、日付は month/day/year の形式 (i.e. 11/23/2005) で表示されます。');
define('AdvancedDateHelpDayOfMonth', '日 (1 から 31)');
define('AdvancedDateHelpNumericMonth', '月 (1 から 12)');
define('AdvancedDateHelpTextualMonth', '月 (Jan から Dec)');
define('AdvancedDateHelpYear2', '年 2桁表示');
define('AdvancedDateHelpYear4', '年 4桁表示');
define('AdvancedDateHelpDayOfYear', '1年の中の日 (1 から 366)');
define('AdvancedDateHelpQuarter', '四半期');
define('AdvancedDateHelpDayOfWeek', '曜日 (月 から 日n)');
define('AdvancedDateHelpWeekOfYear', '1年の中の週 (1 から 53)');

define('InfoNoMessagesFound', 'メッセージが見つかりません');
define('ErrorSMTPConnect', 'SMTPサーバへ接続できません。SMTPサーバ設定をご確認下さい。');
define('ErrorSMTPAuth', 'ユーザID か パスワード が間違っています。認証に失敗しました。');
define('ReportMessageSent', 'メッセージが送信されました');
define('ReportMessageSaved', 'メッセージが保存されました');
define('ErrorPOP3Connect', 'POP3サーバへ接続できません。POP3サーバ設定をご確認下さい。');
define('ErrorIMAP4Connect', 'IMAP4サーバへ接続できません。IMAP4サーバ設定をご確認下さい。');
define('ErrorPOP3IMAP4Auth', 'メールアドレス か ユーザID か パスワード が間違っています。認証に失敗しました。');
define('ErrorGetMailLimit', 'メールボックスの容量制限を超過しました');

define('ReportSettingsUpdatedSuccessfuly', '設定が更新されました');
define('ReportAccountCreatedSuccessfuly', 'アカウントが作成されました');
define('ReportAccountUpdatedSuccessfuly', 'アカウントが更新されました');
define('ConfirmDeleteAccount', 'このアカウントを削除してよろしいですか？');
define('ReportFiltersUpdatedSuccessfuly', 'フィルタが更新されました');
define('ReportSignatureUpdatedSuccessfuly', '署名が更新されました');
define('ReportFoldersUpdatedSuccessfuly', 'フォルダが更新されました');
define('ReportContactsSettingsUpdatedSuccessfuly', '連絡先設定が更新されました');

define('ErrorInvalidCSV', '指定されたCSVファイルのファイル形式が不正です');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'グループ');
define('ReportGroupSuccessfulyAdded2', 'が追加されました');
define('ReportGroupUpdatedSuccessfuly', 'グループが更新されました');
define('ReportContactSuccessfulyAdded', '連絡先が追加されました');
define('ReportContactUpdatedSuccessfuly', '連絡先が更新されました');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', '連絡先がグループに登録されました');
define('AlertNoContactsGroupsSelected', '連絡先またはグループが選択されていません');

define('InfoListNotContainAddress', 'お探しのメールアドレスが見つからない場合、1文字目からメールアドレスを打ち込んで下さい。');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'ダイレクトモード WebMail はサーバのメールボックスに直接アクセスします');

define('FolderInbox', '受信トレイ');
define('FolderSentItems', '送信済み');
define('FolderDrafts', '下書き');
define('FolderTrash', 'ゴミ箱');

define('FileLargerAttachment', '添付ファイルの容量が制限を越えました');
define('FilePartiallyUploaded', '不明なエラーの為、一部のファイルのみアップロードされました');
define('NoFileUploaded', 'ファイルのアップロードに失敗しました');
define('MissingTempFolder', '一時フォルダが不明です');
define('MissingTempFile', '一時ファイルが不明です');
define('UnknownUploadError', 'ファイルアップロード時に不明なエラーが発生しました');
define('FileLargerThan', 'ファイルのアップロードに失敗しました。ファイル容量が以下のサイズを越えている可能性があります : ');
define('PROC_CANT_LOAD_DB', 'DBへの接続に失敗しました');
define('PROC_CANT_LOAD_LANG', '言語設定ファイルが見つかりません');
define('PROC_CANT_LOAD_ACCT', 'アカウントが見つかりません。アカウントが削除された可能性があります。');

define('DomainDosntExist', 'メールサーバに該当のドメインがみつかりません');
define('ServerIsDisable', 'メールサーバへの接続が管理者により制限されています');

define('PROC_ACCOUNT_EXISTS', '既に該当のアカウントが存在している為、アカウントの作成に失敗しました');
define('PROC_CANT_GET_MESSAGES_COUNT', 'フォルダ内のメッセージ数の取得に失敗しました');
define('PROC_CANT_MAIL_SIZE', 'メール保存容量の取得に失敗しました');

define('Organization', '会社情報');
define('WarningOutServerBlank', '「送信メールサーバ」は入力必須です');

define('JS_LANG_Refresh', '更新');
define('JS_LANG_MessagesInInbox', '通 の新着メッセージ');
define('JS_LANG_InfoEmptyInbox', '受信トレイにメッセージがありません');

// webmail 4.2 constants
define('BackToList', '一覧に戻る');
define('InfoNoContactsGroups', '連絡先、またはグループがありません');
define('InfoNewContactsGroups', '新規連絡先 / グループの追加　や　MS Outlook形式の連絡先のインポート が可能です');
define('DefTimeFormat', '既定の時間表示形式');
define('SpellNoSuggestions', 'No suggestions');
define('SpellWait', 'しばらくお待ち下さい&hellip;');

define('InfoNoMessageSelected', 'メッセージが選択されていません');
define('InfoSingleDoubleClick', 'メッセージを シングルクリックでプレビューを表示　ダブルクリックで全体表示します');

// calendar
define('TitleDay', '日');
define('TitleWeek', '週');
define('TitleMonth', '月');

define('ErrorNotSupportBrowser', 'カレンダーはご使用のブラウザをサポートしていません。 以下のブラウザをご使用下さい  : FireFox 2.0以上, Opera 9.0以上, Internet Explorer 6.0以上, Safari 3.0.2以上');
define('ErrorTurnedOffActiveX', 'ActiveXコントロールが無効になっています. <br/>ActiceXコントロールを有効にして下さい。');

define('Calendar', 'カレンダー');

define('TabDay', '日');
define('TabWeek', '週');
define('TabMonth', '月');

define('ToolNewEvent', 'イベントの追加');
define('ToolBack', '戻る');
define('ToolToday', '今日');
define('AltNewEvent', 'イベントの追加');
define('AltBack', '戻る');
define('AltToday', '今日');
define('CalendarHeader', 'カレンダー');
define('CalendarsManager', 'カレンダーの管理');

define('CalendarActionNew', '新規カレンダーの追加');
define('EventHeaderNew', '新規イベントの追加');
define('CalendarHeaderNew', '新規カレンダーの追加');

define('EventSubject', '件名');
define('EventCalendar', 'カレンダー');
define('EventFrom', '開始日時');
define('EventTill', '終了日時');
define('CalendarDescription', '詳細');
define('CalendarColor', '背景色');
define('CalendarName', 'カレンダー名');
define('CalendarDefaultName', 'My カレンダー');

define('ButtonSave', '保存');
define('ButtonCancel', 'キャンセル');
define('ButtonDelete', '削除');

define('AltPrevMonth', '前月');
define('AltNextMonth', '翌月');

define('CalendarHeaderEdit', 'カレンダーの編集');
define('CalendarActionEdit', 'カレンダーの編集');
define('ConfirmDeleteCalendar', 'カレンダーを削除してよろしいですか？');
define('InfoDeleting', '削除中&hellip;');
define('WarningCalendarNameBlank', '「カレンダー名は」必ず指定して下さい。');
define('ErrorCalendarNotCreated', 'カレンダーは作成されませんでした');
define('WarningSubjectBlank', '「件名」は必ず指定して下さい');
define('WarningIncorrectTime', '時間の指定に不正な文字が入力されました');
define('WarningIncorrectFromTime', '「開始日時」が不正です');
define('WarningIncorrectTillTime', '「終了日時」が不正です');
define('WarningStartEndDate', '終了日時は開始日時と同じかそれ以降に設定して下さい');
define('WarningStartEndTime', '終了時間は開始時間以降に設定して下さい');
define('WarningIncorrectDate', '日付が不正です');
define('InfoLoading', 'ロード中&hellip;');
define('EventCreate', '新規イベントの作成');
define('CalendarHideOther', '他のカレンダーを隠す');
define('CalendarShowOther', '他のカレンダーを表示');
define('CalendarRemove', 'カレンダーの削除');
define('EventHeaderEdit', 'イベントの編集');

define('InfoSaving', '保存中&hellip;');
define('SettingsDisplayName', '表示名称');
define('SettingsTimeFormat', '時間表示形式');
define('SettingsDateFormat', '日付表示形式');
define('SettingsShowWeekends', '土日も表示する');
define('SettingsWorkdayStarts', '勤務日の 開始時刻');
define('SettingsWorkdayEnds', '終了時刻');
define('SettingsShowWorkday', '勤務日を表示する');
define('SettingsWeekStartsOn', '週の開始曜日');
define('SettingsDefaultTab', '初期表示タブ');
define('SettingsCountry', '国');
define('SettingsTimeZone', 'タイムゾーン');
define('SettingsAllTimeZones', '全てのタイムゾーン');

define('WarningWorkdayStartsEnds', '勤務日の終了時刻は開始時刻以降に設定して下さい');
define('ReportSettingsUpdated', '設定の更新が終了しました');

define('SettingsTabCalendar', 'カレンダー');

define('FullMonthJanuary', '1月');
define('FullMonthFebruary', '2月');
define('FullMonthMarch', '3月');
define('FullMonthApril', '4月');
define('FullMonthMay', '5月');
define('FullMonthJune', '6月');
define('FullMonthJuly', '7月');
define('FullMonthAugust', '8月');
define('FullMonthSeptember', '9月');
define('FullMonthOctober', '10月');
define('FullMonthNovember', '11月');
define('FullMonthDecember', '12月');

define('ShortMonthJanuary', '1月');
define('ShortMonthFebruary', '2月');
define('ShortMonthMarch', '3月');
define('ShortMonthApril', '4月');
define('ShortMonthMay', '5月');
define('ShortMonthJune', '6月');
define('ShortMonthJuly', '7月');
define('ShortMonthAugust', '8月');
define('ShortMonthSeptember', '9月');
define('ShortMonthOctober', '10月');
define('ShortMonthNovember', '11月');
define('ShortMonthDecember', '12月');

define('FullDayMonday', '月曜日');
define('FullDayTuesday', '火曜日');
define('FullDayWednesday', '水曜日');
define('FullDayThursday', '木曜日');
define('FullDayFriday', '金曜日');
define('FullDaySaturday', '土曜日');
define('FullDaySunday', '日曜日');

define('DayToolMonday', '月曜');
define('DayToolTuesday', '火曜');
define('DayToolWednesday', '水曜');
define('DayToolThursday', '木曜');
define('DayToolFriday', '金曜');
define('DayToolSaturday', '土曜');
define('DayToolSunday', '日曜');

define('CalendarTableDayMonday', '月');
define('CalendarTableDayTuesday', '火');
define('CalendarTableDayWednesday', '水');
define('CalendarTableDayThursday', '木');
define('CalendarTableDayFriday', '金');
define('CalendarTableDaySaturday', '土');
define('CalendarTableDaySunday', '日');

define('ErrorParseJSON', 'The JSON response returned by the server cannot be parsed.');

define('ErrorLoadCalendar', 'カレンダーのロードに失敗しました');
define('ErrorLoadEvents', 'イベントのロードに失敗しました');
define('ErrorUpdateEvent', 'イベントの保存に失敗しました');
define('ErrorDeleteEvent', 'イベントの削除に失敗しました');
define('ErrorUpdateCalendar', 'カレンダーの保存に失敗しました');
define('ErrorDeleteCalendar', 'カレンダーの削除に失敗しました');
define('ErrorGeneral', 'サーバでエラーが発生しました。 しばらくたってから再度アクセスして下さい。');

// webmail 4.3 constants
define('SharedTitleEmail', 'メールアドレス');
define('ShareHeaderEdit', '共有カレンダー');
define('ShareActionEdit', '共有カレンダー');
define('CalendarPublicate', 'このカレンダーにWebからアクセスする');
define('CalendarPublicationLink', 'リンクURL');
define('ShareCalendar', 'このカレンダーを共有する');
define('SharePermission1', 'イベントの更新と共有の管理を許可');
define('SharePermission2', 'イベントの更新を許可');
define('SharePermission3', '全てのイベント詳細の参照を許可');
define('SharePermission4', 'イベント有無のみ参照を許可（詳細非表示）');
define('ButtonClose', '閉じる');
define('WarningEmailFieldFilling', '「メールアドレス」を入力して下さい');
define('EventHeaderView', 'イベント詳細');
define('ErrorUpdateSharing', '共有データの保存に失敗しました');
define('ErrorUpdateSharing1', '%s が存在しない為、共有に失敗しました');
define('ErrorUpdateSharing2', '%s への共有に失敗しました');
define('ErrorUpdateSharing3', 'このカレンダーは既に %s へ共有されています');
define('Title_MyCalendars', 'Myカレンダー');
define('Title_SharedCalendars', '共有カレンダー');
define('ErrorGetPublicationHash', '共有リンクの作成に失敗しました');
define('ErrorGetSharing', '共有の追加に失敗しました');
define('CalendarPublishedTitle', 'このカレンダーは共有されました');
define('RefreshSharedCalendars', '共有カレンダーの更新');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'メンバー');

define('ReportMessagePartDisplayed', 'メッセージの一部のみ表示しています');
define('ReportViewEntireMessage', 'メッセージ全体を表示するには,');
define('ReportClickHere', 'ここをクリック');
define('ErrorContactExists', 'この連絡先は既に登録されています');

define('Attachments', '添付');

define('InfoGroupsOfContact', '連絡先が所属するグループがチェックされました');
define('AlertNoContactsSelected', '連絡先が選択されていません');
define('MailSelected', '選択された連絡先へメール');
define('CaptionSubscribed', '確定');

define('OperationSpam', '迷惑メール');
define('OperationNotSpam', '非迷惑メール');
define('FolderSpam', '迷惑メール');

// webmail 4.4 contacts
define('ContactMail', 'この連絡先へメール');
define('ContactViewAllMails', 'この連絡先とのメールを全て表示');
define('ContactsMailThem', 'メールする');
define('DateToday', '今日');
define('DateYesterday', '昨日');
define('MessageShowDetails', '詳細を表示');
define('MessageHideDetails', '詳細を隠す');
define('MessageNoSubject', 'No subject');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'to');
define('SearchClear', '検索条件をクリア');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', '条件: "#s" の フォルダ: #f の 検索結果:');
define('SearchResultsInAllFolders', '条件: "#s" の全てのフォルダ の 検索結果:');
define('AutoresponderTitle', '自動応答');
define('AutoresponderEnable', '自動応答を有効にする');
define('AutoresponderSubject', '件名');
define('AutoresponderMessage', '内容');
define('ReportAutoresponderUpdatedSuccessfuly', '自動応答が更新されました');
define('FolderQuarantine', '隔離');

//calendar
define('EventRepeats', '繰返し');
define('NoRepeats', '単独の予定');
define('DailyRepeats', '毎日');
define('WorkdayRepeats', '毎平日 (月 - 火)');
define('OddDayRepeats', '毎週 月, 水, 金');
define('EvenDayRepeats', '毎週 火, 木');
define('WeeklyRepeats', '毎週');
define('MonthlyRepeats', '毎月');
define('YearlyRepeats', '毎年');
define('RepeatsEvery', '繰返し');
define('ThisInstance', 'このイベントのみ');
define('AllEvents', '全てのイベント');
define('AllFollowing', '以降全てのイベント');
define('ConfirmEditRepeatEvent', '繰返しのイベントを変更しています。関連するエントリを変更しますか?');
define('RepeatEventHeaderEdit', '繰返しの編集');
define('First', '第1');
define('Second', '第2');
define('Third', '第3');
define('Fourth', '第4');
define('Last', '最終');
define('Every', '毎');
define('SetRepeatEventEnd', '終了を設定');
define('NoEndRepeatEvent', '無限に繰返す');
define('EndRepeatEventAfter', 'この日以降');
define('Occurrences', '回繰返し');
define('EndRepeatEventBy', '指定した日まで');
define('EventCommonDataTab', '繰返しの詳細');
define('EventRepeatDataTab', '個別の詳細');
define('RepeatEventNotPartOfASeries', 'このイベントは変更され、繰返しのイベントではなくなりました');
define('UndoRepeatExclusion', '繰返しへの変更を取消');

define('MonthMoreLink', '%d more...');
define('NoNewSharedCalendars', '新しいカレンダーはありません');
define('NNewSharedCalendars', '%d 個の 新しいカレンダーを見つけました');
define('OneNewSharedCalendars', '1 個の 新しいカレンダーを見つけました');
define('ConfirmUndoOneRepeat', 'このエントリを繰返しの一部として復旧しますか?');

define('RepeatEveryDayInfin', '毎日');
define('RepeatEveryDayTimes', '毎日, %TIMES% 日間');
define('RepeatEveryDayUntil', '毎日, %UNTIL% まで');
define('RepeatDaysInfin', '%PERIOD% 日毎');
define('RepeatDaysTimes', '%PERIOD% 日毎, %TIMES% 回 繰返し');
define('RepeatDaysUntil', '%PERIOD% 日毎, %UNTIL% まで');

define('RepeatEveryWeekWeekdaysInfin', '毎平日');
define('RepeatEveryWeekWeekdaysTimes', '毎平日, %TIMES% 回 繰返し');
define('RepeatEveryWeekWeekdaysUntil', '毎平日, %UNTIL% まで');
define('RepeatWeeksWeekdaysInfin', '毎平日, %PERIOD% 週間');
define('RepeatWeeksWeekdaysTimes', '毎平日, %PERIOD% 週間, %TIMES% 回 繰返し');
define('RepeatWeeksWeekdaysUntil', '毎平日, %PERIOD% 週間, %UNTIL% まで');

define('RepeatEveryWeekInfin', '毎週 %DAYS%');
define('RepeatEveryWeekTimes', '毎週 %DAYS%, %TIMES% 回 繰返し');
define('RepeatEveryWeekUntil', '毎週 %DAYS%, %UNTIL% まで');
define('RepeatWeeksInfin', '%PERIOD% 週毎, %DAYS%');
define('RepeatWeeksTimes', '%PERIOD% 週毎, %DAYS%, %TIMES% 回 繰返し');
define('RepeatWeeksUntil', '%PERIOD% 週毎, %DAYS%, %UNTIL% まで');

define('RepeatEveryMonthDateInfin', '毎月 %DATE% 日');
define('RepeatEveryMonthDateTimes', '毎月 %DATE% 日, %TIMES% 回 繰返し');
define('RepeatEveryMonthDateUntil', '毎月 %DATE% 日, %UNTIL% まで');
define('RepeatMonthsDateInfin', '%PERIOD% 月毎, %DATE% 日');
define('RepeatMonthsDateTimes', '%PERIOD% 月毎, %DATE% 日, %TIMES% 回 繰返し');
define('RepeatMonthsDateUntil', '%PERIOD% 月毎, %DATE% 日, %UNTIL% まで');

define('RepeatEveryMonthWDInfin', '毎月 %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', '毎月 %NUMBER% %DAY%, %TIMES% 回 繰返し');
define('RepeatEveryMonthWDUntil', '毎月 %NUMBER% %DAY%, %UNTIL% まで');
define('RepeatMonthsWDInfin', '%PERIOD% 月毎, %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', '%PERIOD% 月毎, %NUMBER% %DAY%, %TIMES% 回 繰返し');
define('RepeatMonthsWDUntil', '%PERIOD% 月毎, %NUMBER% %DAY%, %UNTIL% まで');

define('RepeatEveryYearDateInfin', '毎年 %DATE%');
define('RepeatEveryYearDateTimes', '毎年 %DATE%, %TIMES% 回 繰返し');
define('RepeatEveryYearDateUntil', '毎年 %DATE%, %UNTIL% まで');
define('RepeatYearsDateInfin', '%PERIOD% 年毎 %DATE%');
define('RepeatYearsDateTimes', '%PERIOD% 年毎 %DATE%, %TIMES% 回 繰返し');
define('RepeatYearsDateUntil', '%PERIOD% 年毎 %DATE%, %UNTIL% まで');

define('RepeatEveryYearWDInfin', '毎年 %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', '毎年 %NUMBER% %DAY%, %TIMES% 回 繰返し');
define('RepeatEveryYearWDUntil', '毎年 %NUMBER% %DAY%, %UNTIL% まで');
define('RepeatYearsWDInfin', '%PERIOD% 年毎 %NUMBER% %DAY%');
define('RepeatYearsWDTimes', '%PERIOD% 年毎 %NUMBER% %DAY%, %TIMES% 回 繰返し');
define('RepeatYearsWDUntil', '%PERIOD% 年毎 %NUMBER% %DAY%, %UNTIL% まで');

define('RepeatDescDay', '日');
define('RepeatDescWeek', '週');
define('RepeatDescMonth', '月');
define('RepeatDescYear', '年');

// webmail 4.5 contacts
define('WarningUntilDateBlank', '終了日を指定して下さい');
define('WarningWrongUntilDate', '終了日は開始日以降を指定して下さい');

define('OnDays', 'On days');
define('CancelRecurrence', '繰返しのキャンセル');
define('RepeatEvent', '繰返しの予定');

define('Spellcheck', 'スペルチェック');
define('LoginLanguage', '言語');
define('LanguageDefault', 'デフォルト');

// webmail 4.5.x new
define('EmptySpam', '迷惑メールを空にする');
define('Saving', '保存中&hellip;');
define('Sending', '送信中&hellip;');
define('LoggingOffFromServer', 'サーバからログアウト中&hellip;');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', '迷惑メールとしてマークできません');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', '非迷惑メールとしてマークできません');
define('ExportToICalendar', 'iCalendar へエキスポート');
define('ErrorMaximumUsersLicenseIsExceeded', 'ライセンス数の上限に達した為、新たなユーザの作成ができません');
define('RepliedMessageTitle', '返信メッセージ');
define('ForwardedMessageTitle', '転送メッセージ');
define('RepliedForwardedMessageTitle', '応答/返信メッセージ');
define('ErrorDomainExist', '対応するドメインが存在しない為、新たなユーザの作成に失敗しました。まずドメインを設定をして下さい');

// webmail 4.7
define('RequestReadConfirmation', '開封確認を送る');
define('FolderTypeDefault', 'デフォルト');
define('ShowFoldersMapping', '任意のフォルダをシステムフォルダとして使用する (例： Myフォルダ を 送信済み として使用)');
define('ShowFoldersMappingNote', 'Myフォルダ を 送信済みフォルダ に設定する場合、, 「送信済み」を 「MyFolder」 として使用 と設定します');
define('FolderTypeMapTo', 'として');

define('ReminderEmailExplanation', 'カレンダー: %CALENDAR_NAME% でアラート設定されている為、このメッセージは %EMAIL% へ送信されました。');
define('ReminderOpenCalendar', 'カレンダーを開く');

define('AddReminder', 'リマインダーを送信する');
define('AddReminderBefore', 'このイベント実施前に % リマインダーを送信');
define('AddReminderAnd', '% 実施前');
define('AddReminderAlso', ' % 実施前');
define('AddMoreReminder', '詳細');
define('RemoveAllReminders', '全てのリマインダーを削除する');
define('ReminderNone', '無し');
define('ReminderMinutes', '分');
define('ReminderHour', '時');
define('ReminderHours', '時間');
define('ReminderDay', '日');
define('ReminderDays', '日間');
define('ReminderWeek', '週');
define('ReminderWeeks', '週間');
define('Allday', '終日');

define('Folders', 'フォルダ');
define('NoSubject', '件名なしt');
define('SearchResultsFor', '検索結果:');

define('Back', '戻る');
define('Next', '次');
define('Prev', '前');

define('MsgList', 'メッセージ');
define('Use24HTimeFormat', '24時間表示');
define('UseCalendars', 'カレンダーを利用する');
define('Event', 'イベント');
define('CalendarSettingsNullLine', 'カレンダーがありません');
define('CalendarEventNullLine', 'イベントがありません');
define('ChangeAccount', 'アカウントの変更');

define('TitleCalendar', 'カレンダー');
define('TitleEvent', 'イベント');
define('TitleFolders', 'フォルダ');
define('TitleConfirmation', '確認');

define('Yes', 'Yes');
define('No', 'No');

define('EditMessage', 'メッセージ編集');

define('AccountNewPassword', '新しいパスワード');
define('AccountConfirmNewPassword', '新しいパスワードの再入力');
define('AccountPasswordsDoNotMatch', 'パスワードが一致していません.');

define('ContactTitle', 'Title');
define('ContactFirstName', '名');
define('ContactSurName', '姓');
define('ContactNickName', 'ニックネーム');

define('CaptchaTitle', '文字認証');
define('CaptchaReloadLink', '再読込');
define('CaptchaError', 'テキストを正しく入力して下さい');

define('WarningInputCorrectEmails', '正しいメールアドレスを入力して下さい');
define('WrongEmails', 'メールアドレスエラー:');

define('ConfirmBodySize1', 'テキストメッセージは上限に達しました');
define('ConfirmBodySize2', '文字が長すぎます。上限を越える文字は削除されます。メッセージを編集し直す場合 「キャンセル」をクリックして下さい。');
define('BodySizeCounter', 'カウンタ');
define('InsertImage', '画像の挿入');
define('ImagePath', '場増の場所');
define('ImageUpload', '挿入');
define('WarningImageUpload', '選択されたファイルは画像ではありません。画像ファイルを選択して下さい。');

define('ConfirmExitFromNewMessage', 'ページを移動する場合、変更は失われます。このメッセージを下書きとして保存しますか？');

define('SensivityConfidential', 'このメッセージを「機密メッセージ」として扱ってください。');
define('SensivityPrivate', 'このメッセージを「プライベートメッセージ」として扱ってください。');
define('SensivityPersonal', 'このメッセージを「パーソナルメッセージ」として扱ってください。');

define('ReturnReceiptTopText', 'このメッセージの送信者が開封確認を求めています。');
define('ReturnReceiptTopLink', '開封確認を送付する');
define('ReturnReceiptSubject', '開封確認の送付 (表示)');
define('ReturnReceiptMailText1', 'これはあなたの送付したメールへの開封確認です。');
define('ReturnReceiptMailText2', '注意: この開封確認は、メッセージが受信者のPC上に表示された事を示すだけで、受信者がメッセージ内容を読んだことや理解したことを保証するものではありません。');
define('ReturnReceiptMailText3', 'with subject');

define('SensivityMenu', '感受度');
define('SensivityNothingMenu', '標準');
define('SensivityConfidentialMenu', '機密');
define('SensivityPrivateMenu', 'プライベート');
define('SensivityPersonalMenu', 'パーソナル');

define('ErrorLDAPonnect', 'LDAPサーバに接続できません');

define('MessageSizeExceedsAccountQuota', 'このメッセージのサイズがあなたのアカウントの許容サイズを超えました');
define('MessageCannotSent', 'メッセージは送信できませんでした');
define('MessageCannotSaved', 'メッセージは保存できませんでした');

define('ContactFieldTitle', '項目');
define('ContactDropDownTO', 'TO');
define('ContactDropDownCC', 'CC');
define('ContactDropDownBCC', 'BCC');

// 4.9
define('NoMoveDelete', '選択されたメッセージはゴミ箱に移動できませんでした。受信トレイが一杯の可能性があります。選択されたメッセージを削除しますか？');

define('WarningFieldBlank', 'この項目は必須です');
define('WarningPassNotMatch', 'パスワードが一致しません。');
define('PasswordResetTitle', 'パスワードリセット - step %d');
define('NullUserNameonReset', 'ユーザ');
define('IndexResetLink', 'パスワードを忘れましたか？');
define('IndexRegLink', 'アカウント登録');

define('RegDomainNotExist', 'ドメインが存在しません');
define('RegAnswersIncorrect', '答えが間違っています');
define('RegUnknownAdress', 'メールアドレスが不正です');
define('RegUnrecoverableAccount', 'このメールアドレスに対するパスワードリセットは実行できません');
define('RegAccountExist', 'このメールアドレスは使用済みです');
define('RegRegistrationTitle', '登録');
define('RegName', '名称');
define('RegEmail', 'メールアドレス');
define('RegEmailDesc', '例） myname@domain.com この情報は、システムにログインするために使用されます');
define('RegSignMe', '記憶する');
define('RegSignMeDesc', '次回からID/パスワードの入力を省略');
define('RegPass1', 'パスワード');
define('RegPass2', 'パスワードの再入力 ');
define('RegQuestionDesc', 'あなただけの知っている２つび秘密の質問と答えを設定して下さい。 これらの質問は、パスワードリセット時に使用されます。');
define('RegQuestion1', '秘密の質問 1');
define('RegAnswer1', '答え 1');
define('RegQuestion2', '秘密の質問 2');
define('RegAnswer2', '答え 2');
define('RegTimeZone', 'タイムゾーン');
define('RegLang', '使用言語');
define('RegCaptcha', '文字認証');
define('RegSubmitButtonValue', '登録');

define('ResetEmail', 'メールアドレスを入力して下さい。');
define('ResetEmailDesc', '登録時に設定したメールアドレスを入力して下さい。');
define('ResetCaptcha', '文字認証');
define('ResetSubmitStep1', '送信');
define('ResetQuestion1', '秘密の質問 1');
define('ResetAnswer1', '答え');
define('ResetQuestion2', '秘密の質問 2');
define('ResetAnswer2', '答え');
define('ResetSubmitStep2', '送信');

define('ResetTopDesc1Step2', 'メールアドレスの入力');
define('ResetTopDesc2Step2', '正当性の確認');

define('ResetTopDescStep3', 'please specify below new password for your email.');

define('ResetPass1', '新しいパスワード');
define('ResetPass2', 'パスワードの再入力');
define('ResetSubmitStep3', '送信');
define('ResetDescStep4', 'パスワードが変更されました');
define('ResetSubmitStep4', '戻る');

define('RegReturnLink', 'ログイン画面へ戻る');
define('ResetReturnLink', 'ログイン画面へ戻る');

// Appointments
define('AppointmentAddGuests', 'ゲストの追加');
define('AppointmentRemoveGuests', '会議の取消');
define('AppointmentListEmails', 'メールアドレスをカンマ区切りで入力し、保存ボタンをクリック');
define('AppointmentParticipants', '参加者');
define('AppointmentRefused', '拒否');
define('AppointmentAwaitingResponse', '返答待ち');
define('AppointmentInvalidGuestEmail', '次のゲストのメールアドレスが無効です');
define('AppointmentOwner', 'オーナー');

define('AppointmentMsgTitleInvite', 'イベント参加依頼');
define('AppointmentMsgTitleUpdate', 'イベントは修正されました');
define('AppointmentMsgTitleCancel', 'イベントはキャンセルされました');
define('AppointmentMsgTitleRefuse', 'ゲスト %guest% は参加を拒否しました');
define('AppointmentMoreInfo', '詳細');
define('AppointmentOrganizer', '主催者');
define('AppointmentEventInformation', 'イベント情報');
define('AppointmentEventWhen', '開催日');
define('AppointmentEventParticipants', '参加者');
define('AppointmentEventDescription', '内容');
define('AppointmentEventWillYou', 'このイベントに参加しますか');
define('AppointmentAdditionalParameters', '追加パラメータ');
define('AppointmentHaventRespond', 'まだ返答しない');
define('AppointmentRespondYes', '参加予定');
define('AppointmentRespondMaybe', '参加未定');
define('AppointmentRespondNo', '不参加');
define('AppointmentGuestsChangeEvent', 'ゲストによる内容変更を許可する');

define('AppointmentSubjectAddStart', 'イベントへの参加依頼: ');
define('AppointmentSubjectAddFrom', ' 送信者:  ');
define('AppointmentSubjectUpdateStart', 'イベントの更新 ');
define('AppointmentSubjectDeleteStart', 'イベントのキャンセル ');
define('ErrorAppointmentChangeRespond', '予定の変更はできません');
define('SettingsAutoAddInvitation', '招待された予定をカレンダーに自動で追加する');
define('ReportEventSaved', 'イベントが保存されました');
define('ReportAppointmentSaved', ' 招待メールが送信されました');
define('ErrorAppointmentSend', '招待メールが送信できません');
define('AppointmentEventName', '名前:');

// End appointments

define('ErrorCantUpdateFilters', 'フィルタの更新ができません');

define('FilterPhrase', '%field が %condition %string 場合、 %action');
define('FiltersAdd', 'フィルタの追加');
define('FiltersCondEqualTo', '次の文字に等しい');
define('FiltersCondContainSubstr', '次の文字を含む');
define('FiltersCondNotContainSubstr', '次の文字を含まない');
define('FiltersActionDelete', 'メッセージを削除する');
define('FiltersActionMove', '次のフォルダに移動する : ');
define('FiltersActionToFolder', '%folder フォルダへ移動');
define('FiltersNo', 'フィルタが設定されていません');

define('ReminderEmailFriendly', 'リマインダ');
define('ReminderEventBegin', '開始: ');

define('FiltersLoading', 'フィルタの呼び出し中...');
define('ConfirmMessagesPermanentlyDeleted', 'このフォルダの全てのメッセージを完全に削除する。');

define('InfoNoNewMessages', '新しいメッセージはありません');
define('TitleImportContacts', '連絡先のインポート');
define('TitleSelectedContacts', '選択された連絡先');
define('TitleNewContact', '新規連絡先');
define('TitleViewContact', '連絡先の参照');
define('TitleEditContact', '連絡先の編集');
define('TitleNewGroup', '新規グループ');
define('TitleViewGroup', 'グループの参照');

define('AttachmentComplete', '完了');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'メール自動受信間隔');
define('AutoCheckMailIntervalDisableName', '使用しない');
define('ReportCalendarSaved', 'カレンダーは保存されました');

define('ContactSyncError', '同期失敗');
define('ReportContactSyncDone', '同期完了');

define('MobileSyncUrlTitle', 'Mobile sync URL');
define('MobileSyncLoginTitle', 'Mobile sync ログイン');

define('QuickReply', 'クイック返信');
define('SwitchToFullForm', '全画面表示');
define('SortFieldDate', '日付');
define('SortFieldFrom', '差出人');
define('SortFieldSize', 'サイズ');
define('SortFieldSubject', 'タイトル');
define('SortFieldFlag', 'フラグ');
define('SortFieldAttachments', '添付');
define('SortOrderAscending', '昇順');
define('SortOrderDescending', '降順');
define('ArrangedBy', '並び替え');

define('MessagePaneToRight', 'プレビューウィンドウをメッセージリストの右側に表示する');

define('SettingsTabMobileSync', 'モバイル同期');

define('MobileSyncContactDataBaseTitle', 'Mobile sync アドレス帳 データベース');
define('MobileSyncCalendarDataBaseTitle', 'Mobile sync カレンダー データベース');
define('MobileSyncTitleText', 'SyncMLが利用可能な携帯デバイスにWebMailを同期したい場合、これらのパラメータが利用できます。<br />"Mobile Sync URL" は、SyncMLデータ同期サーバへの接続パスを設定します。"Mobile Sync ログイン" は、SyncMLデータ同期サーバへのログインに使用されます。 デバイスにより、アドレス帳やカレンダーの同期の為にデータベースを指定する必要があります。<br />それぞれ "Mobile sync アドレス帳 データベース" と "Mobile sync カレンダー データベース" を使用して下さい。');
define('MobileSyncEnableLabel', 'モバイル同期を有効にする');

define('SearchInputText', '検索');

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
