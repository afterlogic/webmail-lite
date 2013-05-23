<?php
define('PROC_ERROR_ACCT_CREATE', '계정 생성 도중 오류가 발생하였습니다.');
define('PROC_WRONG_ACCT_PWD', '비밀번호 오류');
define('PROC_CANT_LOG_NONDEF', '입력한 계정으로 로그인 할 수 없습니다.');
define('PROC_CANT_INS_NEW_FILTER', '새 규칙을 추가할 수 없습니다.');
define('PROC_FOLDER_EXIST', '동일한 이름의 메일함이 이미 존재합니다.');
define('PROC_CANT_CREATE_FLD', '새 메일함을 만들 수 없습니다.');
define('PROC_CANT_INS_NEW_GROUP', '새 그룹을 만들 수 없습니다.');
define('PROC_CANT_INS_NEW_CONT', '새 연락처를 만들 수 없습니다.');
define('PROC_CANT_INS_NEW_CONTS', '새 연락처를 만들 수 없습니다.');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', '연락처를 그룹에 추가할 수 없습니다.');
define('PROC_ERROR_ACCT_UPDATE', '계정 수정 도중 오류가 발생하였습니다.');
define('PROC_CANT_UPDATE_CONT_SETTINGS', '연락처 설정을 수정할 수 없습니다.');
define('PROC_CANT_GET_SETTINGS', '설정 정보를 가져올 수 없습니다.');
define('PROC_CANT_UPDATE_ACCT', '계정을 수정할 수 없습니다.');
define('PROC_ERROR_DEL_FLD', '메일함 삭제 도중 오류가 발생하였습니다.');
define('PROC_CANT_UPDATE_CONT', '연락처를 수정할 수 없습니다.');
define('PROC_CANT_GET_FLDS', '메일함 구성 정보를 가져올 수 없습니다.');
define('PROC_CANT_GET_MSG_LIST', '메일 목록을 가져올 수 없습니다.');
define('PROC_MSG_HAS_DELETED', '이 메일은 서버에서 이미 삭제되었습니다.');
define('PROC_CANT_LOAD_CONT_SETTINGS', '연락처 설정 정보를 가져올 수 없습니다.');
define('PROC_CANT_LOAD_SIGNATURE', '계정 서명 정보를 가져올 수 없습니다.');
define('PROC_CANT_GET_CONT_FROM_DB', 'DB로부터 연락처를 가져올 수 없습니다.');
define('PROC_CANT_GET_CONTS_FROM_DB', 'DB로부터 연락처를 가져올 수 없습니다.');
define('PROC_CANT_DEL_ACCT_BY_ID', '계정을 삭제할 수 없습니다.');
define('PROC_CANT_DEL_FILTER_BY_ID', '규칙을 삭제할 수 없습니다.');
define('PROC_CANT_DEL_CONT_GROUPS', '연락처 또는 그룹을 삭제할 수 없습니다.');
define('PROC_WRONG_ACCT_ACCESS', '사용자 계정으로 허용되지 않은 접근 시도가 탐지되었습니다.');
define('PROC_SESSION_ERROR', '시간 초과로 이전 세션이 종료되었습니다.');

define('MailBoxIsFull', '메일함 용량을 초과하였습니다.');
define('WebMailException', '서버 오류가 발생하였습니다. 시스템 담당자에게 연락하여 주시기 바랍니다.');
define('InvalidUid', '유효하지 않은 이메일 UID');
define('CantCreateContactGroup', '새 연락처 그룹을 만들 수 없습니다.');
define('CantCreateUser', '새 사용자를 만들 수 없습니다.');
define('CantCreateAccount', '새 계정을 만들 수 없습니다.');
define('SessionIsEmpty', '세션 정보가 없습니다.');
define('FileIsTooBig', '파일 크기 초과');

define('PROC_CANT_MARK_ALL_MSG_READ', '모두 읽음 표시를 적용할 수 없습니다.');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', '모두 읽지 않음 표시를 적용할 수 없습니다.');
define('PROC_CANT_PURGE_MSGS', '메일함을 비울 수 없습니다.');
define('PROC_CANT_DEL_MSGS', '메일을 삭제할 수 없습니다.');
define('PROC_CANT_UNDEL_MSGS', '메일을 복구할 수 없습니다.');
define('PROC_CANT_MARK_MSGS_READ', '읽음 표시를 적용할 수 없습니다.');
define('PROC_CANT_MARK_MSGS_UNREAD', '읽지 않음 표시를 적용할 수 없습니다.');
define('PROC_CANT_SET_MSG_FLAGS', '메일에 플래그를 설정할 수 없습니다.');
define('PROC_CANT_REMOVE_MSG_FLAGS', '플래그를 해제할 수 없습니다.');
define('PROC_CANT_CHANGE_MSG_FLD', '메일함을 변경할 수 없습니다.');
define('PROC_CANT_SEND_MSG', '메일을 보낼 수 없습니다.');
define('PROC_CANT_SAVE_MSG', '메일을 저장할 수 없습니다.');
define('PROC_CANT_GET_ACCT_LIST', '계정 목록을 가져올 수 없습니다.');
define('PROC_CANT_GET_FILTER_LIST', '규칙 목록을 가져올 수 없습니다.');

define('PROC_CANT_LEAVE_BLANK', '*표시된 항목은 필수 입력 항목입니다.');

define('PROC_CANT_UPD_FLD', '메일함을 업데이트 할 수 없습니다.');
define('PROC_CANT_UPD_FILTER', '규칙을 업데이트 할 수 없습니다.');

define('ACCT_CANT_ADD_DEF_ACCT', '동일한 이름의 계정이 이미 등록되어 있습니다. 다른 이름을 사용하시기 바랍니다.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', '사용자 계정이 기본 상태로 변경될 수 없습니다.');
define('ACCT_CANT_CREATE_IMAP_ACCT', '새 계정을 만들 수 없습니다. (IMAP4 연결 오류)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', '이전 기본 계정을 삭제할 수 없습니다.');

define('LANG_LoginInfo', '로그인 정보');
define('LANG_Email', '이메일');
define('LANG_Login', '아이디');
define('LANG_Password', '비밀번호');
define('LANG_IncServer', '받는&nbsp;&nbsp;&nbsp;<br/>메일서버');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', '포트');
define('LANG_OutServer', '보내는&nbsp;&nbsp;<br/>메일서버');
define('LANG_OutPort', '포트');
define('LANG_UseSmtpAuth', '보내는 메일서버(SMTP) 인증');
define('LANG_SignMe', '자동 로그인');
define('LANG_Enter', '로그인');

// interface strings

define('JS_LANG_TitleLogin', '로그인');
define('JS_LANG_TitleMessagesListView', '메일목록');
define('JS_LANG_TitleMessagesList', '메일목록');
define('JS_LANG_TitleViewMessage', '메일보기');
define('JS_LANG_TitleNewMessage', '메일쓰기');
define('JS_LANG_TitleSettings', '설정');
define('JS_LANG_TitleContacts', '연락처');

define('JS_LANG_StandardLogin', '일반&nbsp;로그인');
define('JS_LANG_AdvancedLogin', '고급&nbsp;로그인');

define('JS_LANG_InfoWebMailLoading', '메일 확인 중&hellip;');
define('JS_LANG_Loading', '로딩 중&hellip;');
define('JS_LANG_InfoMessagesLoad', '메일 목록 확인 중');
define('JS_LANG_InfoEmptyFolder', '메일함에 메일이 없습니다.');
define('JS_LANG_InfoPageLoading', '페이지 불러오는 중&hellip;');
define('JS_LANG_InfoSendMessage', '메일이 발송되었습니다.');
define('JS_LANG_InfoSaveMessage', '메일이 저장되었습니다.');
define('JS_LANG_InfoHaveImported', '사용자 정보를 가져왔습니다.');
define('JS_LANG_InfoNewContacts', '등록된 목록에 새 연락처 추가');
define('JS_LANG_InfoToDelete', '삭제하기 ');
define('JS_LANG_InfoDeleteContent', '먼저 모든메일을 삭제하여야 하는 메일함');
define('JS_LANG_InfoDeleteNotEmptyFolders', '빈 메일함이 아닙니다. 먼저 메일함의 모든 메일을 삭제하시기 바랍니다.');
define('JS_LANG_InfoRequiredFields', '* 필수 입력 항목');

define('JS_LANG_ConfirmAreYouSure', '삭제하시겠습니까?');
define('JS_LANG_ConfirmDirectModeAreYouSure', '선택된 메일은 완전히 삭제됩니다. 삭제하시겠습니까?');
define('JS_LANG_ConfirmSaveSettings', '설정값이 아직 저장되지 않았습니다. 저장하시겠습니까?');
define('JS_LANG_ConfirmSaveContactsSettings', '연락처가 아직 저장되지 않았습니다. 저장하시겠습니까?');
define('JS_LANG_ConfirmSaveAcctProp', '계정 정보가 아직 저장되지 않았습니다. 저장하시겠습니까?');
define('JS_LANG_ConfirmSaveFilter', '규칙 정보가 아직 저장되지 않았습니다. 저장하시겠습니까?');
define('JS_LANG_ConfirmSaveSignature', '서명이 아직 저장되지 않았습니다. 저장하시겠습니까?');
define('JS_LANG_ConfirmSavefolders', '아직 메일함이 저장되지 않았습니다. 저장하시겠습니까?');
define('JS_LANG_ConfirmHtmlToPlain', '경고: HTML에서 Plan Text로 모드를 전환하면 현재 작성중인 메일의 일부 내용을 잃을 수 있습니다. 전환하시겠습니까?');
define('JS_LANG_ConfirmAddFolder', '메일함 추가/삭제 전에 변경 내용을 적용할 필요가 있습니다. 변경 내용을 적용하시겠습니까?');
define('JS_LANG_ConfirmEmptySubject', '제목을 입력하지 않았습니다. 계속하시겠습니까?');

define('JS_LANG_WarningEmailBlank', '이메일을 입력하세요.');
define('JS_LANG_WarningLoginBlank', '아이디를 입력하세요.');
define('JS_LANG_WarningToBlank', '받는사람 메일 주소를 입력하시기 바랍니다.');
define('JS_LANG_WarningServerPortBlank', 'POP/SMTP/포트 정보는 필수 입력 항목입니다.');
define('JS_LANG_WarningEmptySearchLine', '검색 단어를 입력하지 않았습니다. 검색할 단어를 입력하여 주시기 바랍니다.');
define('JS_LANG_WarningMarkListItem', '선택된 메일이 없습니다.');
define('JS_LANG_WarningFolderMove', '권한이 없어 메일함을 이동할 수 없습니다.');
define('JS_LANG_WarningContactNotComplete', '이메일 또는 사용자명을 입력하여 주세요.');
define('JS_LANG_WarningGroupNotComplete', '그룹명을 입력하여 주세요.');

define('JS_LANG_WarningEmailFieldBlank', '"이메일" 정보는 필수 입력 항목입니다.');
define('JS_LANG_WarningIncServerBlank', 'POP3(IMAP4) 서버 정보는 필수 입력 항목입니다.');
define('JS_LANG_WarningIncPortBlank', 'POP3(IMAP4) 포트 번호는 필수 입력 항목입니다.');
define('JS_LANG_WarningIncLoginBlank', 'POP3(IMAP4) 아이디를 입력하여 주세요.');
define('JS_LANG_WarningIncPortNumber', '정수로 된 POP3(IMAP4)의 포트 번호를 입력하여 주세요.');
define('JS_LANG_DefaultIncPortNumber', 'POP3(IMAP4)의 기본 포트 번호는 110(143)번 입니다.');
define('JS_LANG_WarningIncPassBlank', 'POP3(IMAP4) 비밀번호는 필수 입력 항목입니다.');
define('JS_LANG_WarningOutPortBlank', 'SMTP 서버의 포트 번호는 필수 입력 항목입니다.');
define('JS_LANG_WarningOutPortNumber', '정수로 된 SMTP의 포트 번호를 입력하여 주세요.');
define('JS_LANG_WarningCorrectEmail', '정확한 이메일 주소를 입력하여 주세요.');
define('JS_LANG_DefaultOutPortNumber', 'SMTP의 기본 포트 번호는 25번 입니다.');

define('JS_LANG_WarningCsvExtention', '확장자가 .csv인 파일만 가져올 수 있습니다.');
define('JS_LANG_WarningImportFileType', '연락처를 정보를 가져올 어플리케이션을 선택하여 주세요.');
define('JS_LANG_WarningEmptyImportFile', '파일찾기 버튼을 눌러 파알을 선택하여 주세요.');

define('JS_LANG_WarningContactsPerPage', '페이지 당 연락처 개수는 정수로 입력되어야 합니다.');
define('JS_LANG_WarningMessagesPerPage', '페이지 당 메일 개수는 정수로 입력되어야 합니다.');
define('JS_LANG_WarningMailsOnServerDays', '서버 메일의 기간 입력은 정수로 입력되어야 합니다.');
define('JS_LANG_WarningEmptyFilter', '하위 문자를 입력하여 주세요.');
define('JS_LANG_WarningEmptyFolderName', '메일함 이름을 입력하여 주세요.');

define('JS_LANG_ErrorConnectionFailed', '연결 실패');
define('JS_LANG_ErrorRequestFailed', '데이터 전송에 실패하였습니다.');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'XMLHttpRequest 오브젝트가 없습니다.');
define('JS_LANG_ErrorWithoutDesc', '알수없는 오류가 발생하였습니다.');
define('JS_LANG_ErrorParsing', 'XML 파싱 중 오류가 발생하였습니다.');
define('JS_LANG_ResponseText', '결과 값:');
define('JS_LANG_ErrorEmptyXmlPacket', 'XML 패킷 비우기');
define('JS_LANG_ErrorImportContacts', '연락처 가져오기 도중 오류가 발생하였습니다.');
define('JS_LANG_ErrorNoContacts', '가져온 연락처가 없습니다.');
define('JS_LANG_ErrorCheckMail', '서버에서 메일 수신이 완료되지 않아 메일 확인이 중단되었습니다.');

define('JS_LANG_LoggingToServer', '서버 로그 기록 중&hellip;');
define('JS_LANG_GettingMsgsNum', '메일 개수 확인 중');
define('JS_LANG_RetrievingMessage', '메일 검색 중');
define('JS_LANG_DeletingMessage', '메일 삭제 중');
define('JS_LANG_DeletingMessages', '메일 삭제 중');
define('JS_LANG_Of', 'of');
define('JS_LANG_Connection', '연결');
define('JS_LANG_Charset', '인코딩');
define('JS_LANG_AutoSelect', '자동선택');

define('JS_LANG_Contacts', '연락처');
define('JS_LANG_ClassicVersion', '클래식 버전');
define('JS_LANG_Logout', '로그아웃');
define('JS_LANG_Settings', '설정');

define('JS_LANG_LookFor', '검색어 : ');
define('JS_LANG_SearchIn', '검색대상 : ');
define('JS_LANG_QuickSearch', '"보낸사람", "받은사람" 및 "제목" 항목만 검색');
define('JS_LANG_SlowSearch', '모든 항목 검색');
define('JS_LANG_AllMailFolders', '전체메일함');
define('JS_LANG_AllGroups', '전체그룹');

define('JS_LANG_NewMessage', '메일쓰기');
define('JS_LANG_CheckMail', '메일확인');
define('JS_LANG_EmptyTrash', '휴지통 비우기');
define('JS_LANG_MarkAsRead', '읽음표시');
define('JS_LANG_MarkAsUnread', '읽지않음 표시');
define('JS_LANG_MarkFlag', '플래그 삽입');
define('JS_LANG_MarkUnflag', '플래그 해제');
define('JS_LANG_MarkAllRead', '모두 읽음 표시');
define('JS_LANG_MarkAllUnread', '모두 읽지 않음 표시');
define('JS_LANG_Reply', '회신');
define('JS_LANG_ReplyAll', '전체회신');
define('JS_LANG_Delete', '삭제');
define('JS_LANG_Undelete', '복구');
define('JS_LANG_PurgeDeleted', '지운 메일함 비우기');
define('JS_LANG_MoveToFolder', '메일이동');
define('JS_LANG_Forward', '전달');

define('JS_LANG_HideFolders', '메일함 숨기기');
define('JS_LANG_ShowFolders', '메일함 보이기');
define('JS_LANG_ManageFolders', '메일함 관리');
define('JS_LANG_SyncFolder', '동기화된 메일함');
define('JS_LANG_NewMessages', '새 이메일');
define('JS_LANG_Messages', '이메일');

define('JS_LANG_From', '보낸사람');
define('JS_LANG_To', '받는사람');
define('JS_LANG_Date', '날짜');
define('JS_LANG_Size', '크기');
define('JS_LANG_Subject', '제목');

define('JS_LANG_FirstPage', '처음 페이지');
define('JS_LANG_PreviousPage', '이전 페이지');
define('JS_LANG_NextPage', '다음 페이지');
define('JS_LANG_LastPage', '마지막 페이지');

define('JS_LANG_SwitchToPlain', 'Plain Text 보기');
define('JS_LANG_SwitchToHTML', 'HTML 보기');
define('JS_LANG_AddToAddressBook', '연락처 추가');
define('JS_LANG_ClickToDownload', '다운로드 ');
define('JS_LANG_View', '보기');
define('JS_LANG_ShowFullHeaders', '헤더 보이기');
define('JS_LANG_HideFullHeaders', '헤더 감추기');

define('JS_LANG_MessagesInFolder', '메일함');
define('JS_LANG_YouUsing', '현재 사용 중:');
define('JS_LANG_OfYour', '해당');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', '보내기');
define('JS_LANG_SaveMessage', '저장');
define('JS_LANG_Print', '인쇄');
define('JS_LANG_PreviousMsg', '이전 메일');
define('JS_LANG_NextMsg', '다음 메일');
define('JS_LANG_AddressBook', '주소록');
define('JS_LANG_ShowBCC', '비밀참조');
define('JS_LANG_HideBCC', '비밀참조 숨기기');
define('JS_LANG_CC', '참조');
define('JS_LANG_BCC', '비밀참조');
define('JS_LANG_ReplyTo', '회신');
define('JS_LANG_AttachFile', '파일 첨부');
define('JS_LANG_Attach', '첨부파일');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', '원본 메시지');
define('JS_LANG_Sent', 'Sent');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', '낮음');
define('JS_LANG_Normal', '보통');
define('JS_LANG_High', '높음');
define('JS_LANG_Importance', '중요도');
define('JS_LANG_Close', '닫기');

define('JS_LANG_Common', '일반 설정');
define('JS_LANG_EmailAccounts', '이메일 설정');

define('JS_LANG_MsgsPerPage', '페이지 당 메일 개수');
define('JS_LANG_DisableRTE', '텍스트 에디터 사용 안하기');
define('JS_LANG_Skin', '스킨');
define('JS_LANG_DefCharset', '기본 인코딩');
define('JS_LANG_DefCharsetInc', '기본 수신 메일 인코딩');
define('JS_LANG_DefCharsetOut', '기본 발신 메일 인코딩');
define('JS_LANG_DefTimeOffset', '기본 시간대');
define('JS_LANG_DefLanguage', '기본 언어');
define('JS_LANG_DefDateFormat', '기본 데이터 포맷');
define('JS_LANG_ShowViewPane', '미리보기');
define('JS_LANG_Save', '저장');
define('JS_LANG_Cancel', '취소');
define('JS_LANG_OK', '확인');

define('JS_LANG_Remove', '삭제');
define('JS_LANG_AddNewAccount', '새 계정 추가');
define('JS_LANG_Signature', '서명');
define('JS_LANG_Filters', '규칙');
define('JS_LANG_Properties', '설정값');
define('JS_LANG_UseForLogin', '현재 계정 설정 값(사용자 및 비밀번호)을 로그인 정보로 사용하기');
define('JS_LANG_MailFriendlyName', '사용자 이름');
define('JS_LANG_MailEmail', '이메일');
define('JS_LANG_MailIncHost', '수신메일');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', '포트');
define('JS_LANG_MailIncLogin', '로그인');
define('JS_LANG_MailIncPass', '비밀번호');
define('JS_LANG_MailOutHost', '발신메일');
define('JS_LANG_MailOutPort', '포트');
define('JS_LANG_MailOutLogin', 'SMTP 로그인');
define('JS_LANG_MailOutPass', 'SMTP 비밀번호');
define('JS_LANG_MailOutAuth1', 'SMTP 인증 사용');
define('JS_LANG_MailOutAuth2', '(SMTP 로그인/비밀번호 정보가 POP/IMAP4 로그인/비밀번호 정보와 일치하면 입력하지 않아도 됩니다.)');
define('JS_LANG_UseFriendlyNm1', '보낸사람 항목에 사용자 이름 표시하기');
define('JS_LANG_UseFriendlyNm2', '(예: 홍길동 &lt;sender@codicare.co.kr&gt;)');
define('JS_LANG_GetmailAtLogin', '로그인 시 메일 동기화');
define('JS_LANG_MailMode0', '수신된 메일 서버에서 삭제');
define('JS_LANG_MailMode1', '서버에 메일 보관');
define('JS_LANG_MailMode2', '서버에 메일 복사본 보관');
define('JS_LANG_MailsOnServerDays', '일');
define('JS_LANG_MailMode3', '휴지통 비울때 서버에서 메일 삭제');
define('JS_LANG_InboxSyncType', '메일함 동기화 형태');

define('JS_LANG_SyncTypeNo', '동기화 안함');
define('JS_LANG_SyncTypeNewHeaders', '새 헤더');
define('JS_LANG_SyncTypeAllHeaders', '모든 헤더');
define('JS_LANG_SyncTypeNewMessages', '새 메일');
define('JS_LANG_SyncTypeAllMessages', '모든 메일');
define('JS_LANG_SyncTypeDirectMode', 'Direct 모드');

define('JS_LANG_Pop3SyncTypeEntireHeaders', '헤더만');
define('JS_LANG_Pop3SyncTypeEntireMessages', '모든 메일');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Direct 모드');

define('JS_LANG_DeleteFromDb', '메일 서버에 더 이상 유효하지 않은 메일 DB에서 삭제');

define('JS_LANG_EditFilter', '규칙&nbsp;수정');
define('JS_LANG_NewFilter', '새 규칙 추가');
define('JS_LANG_Field', '항목');
define('JS_LANG_Condition', '조건');
define('JS_LANG_ContainSubstring', '문자열 포함');
define('JS_LANG_ContainExactPhrase', '구체적인 구문 포함');
define('JS_LANG_NotContainSubstring', '문자열 포함하지 않기');
define('JS_LANG_FilterDesc_At', 'at');
define('JS_LANG_FilterDesc_Field', '항목');
define('JS_LANG_Action', '동작');
define('JS_LANG_DoNothing', '아무것도 하지 않기');
define('JS_LANG_DeleteFromServer', '서버에서 즉시 삭제');
define('JS_LANG_MarkGrey', 'Grey로 설정');
define('JS_LANG_Add', '추가');
define('JS_LANG_OtherFilterSettings', '기타 규칙 설정');
define('JS_LANG_ConsiderXSpam', 'X-Spam 헤더 고려');
define('JS_LANG_Apply', '적용');

define('JS_LANG_InsertLink', '링크 삽입');
define('JS_LANG_RemoveLink', '링크 제거');
define('JS_LANG_Numbering', '번호 매기기');
define('JS_LANG_Bullets', '글머리 기호');
define('JS_LANG_HorizontalLine', '구분선 넣기');
define('JS_LANG_Bold', '굵게');
define('JS_LANG_Italic', '기울임꼴');
define('JS_LANG_Underline', '밑줄');
define('JS_LANG_AlignLeft', '왼쪽 맞춤');
define('JS_LANG_Center', '가운데 맞춤');
define('JS_LANG_AlignRight', '오른쪽 맞춤');
define('JS_LANG_Justify', '양쪽 맞춤');
define('JS_LANG_FontColor', '글꼴색');
define('JS_LANG_Background', '배경');
define('JS_LANG_SwitchToPlainMode', 'Plain Text 모드');
define('JS_LANG_SwitchToHTMLMode', 'HTML 모드');

define('JS_LANG_Folder', '메일함');
define('JS_LANG_Msgs', '메일');
define('JS_LANG_Synchronize', '동기화');
define('JS_LANG_ShowThisFolder', '메일함 보이기');
define('JS_LANG_Total', '합계');
define('JS_LANG_DeleteSelected', '선택항목 삭제');
define('JS_LANG_AddNewFolder', '새 메일함 추가');
define('JS_LANG_NewFolder', '새 메일함');
define('JS_LANG_ParentFolder', '상위 메일함');
define('JS_LANG_NoParent', '상위 메일함 없음');
define('JS_LANG_FolderName', '메일함 이름');

define('JS_LANG_ContactsPerPage', '페이지 당 연락처 개수');
define('JS_LANG_WhiteList', '수신 허용자 주소록');

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

define('JS_LANG_DateDefault', '기본');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Month (01 Jan)');
define('JS_LANG_DateAdvanced', '고급');

define('JS_LANG_NewContact', '새 연락처');
define('JS_LANG_NewGroup', '새 그룹');
define('JS_LANG_AddContactsTo', '그룹구성');
define('JS_LANG_ImportContacts', '연락처 가져오기');

define('JS_LANG_Name', '이름');
define('JS_LANG_Email', '이메일');
define('JS_LANG_DefaultEmail', '이메일');
define('JS_LANG_NotSpecifiedYet', '미설정');
define('JS_LANG_ContactName', '이름');
define('JS_LANG_Birthday', '생년월일');
define('JS_LANG_Month', '월');
define('JS_LANG_January', '1월');
define('JS_LANG_February', '2월');
define('JS_LANG_March', '3월');
define('JS_LANG_April', '4월');
define('JS_LANG_May', '5월');
define('JS_LANG_June', '6월');
define('JS_LANG_July', '7월');
define('JS_LANG_August', '8월');
define('JS_LANG_September', '9월');
define('JS_LANG_October', '10월');
define('JS_LANG_November', '11월');
define('JS_LANG_December', '12월');
define('JS_LANG_Day', '일');
define('JS_LANG_Year', '년');
define('JS_LANG_UseFriendlyName1', '이름 사용');
define('JS_LANG_UseFriendlyName2', '(예: 홍길동 &lt;sender@codicare.co.kr&gt;)');
define('JS_LANG_Personal', '기본');
define('JS_LANG_PersonalEmail', '이메일');
define('JS_LANG_StreetAddress', '주소');
define('JS_LANG_City', '시/군/구');
define('JS_LANG_Fax', '팩스');
define('JS_LANG_StateProvince', '시/도');
define('JS_LANG_Phone', '전화번호');
define('JS_LANG_ZipCode', '우편번호');
define('JS_LANG_Mobile', '휴대폰번호');
define('JS_LANG_CountryRegion', '국가');
define('JS_LANG_WebPage', '홈페이지');
define('JS_LANG_Go', '열기');
define('JS_LANG_Home', '집');
define('JS_LANG_Business', '근무처');
define('JS_LANG_BusinessEmail', '이메일');
define('JS_LANG_Company', '회사');
define('JS_LANG_JobTitle', '직위');
define('JS_LANG_Department', '부서');
define('JS_LANG_Office', '사무실');
define('JS_LANG_Pager', '호출기');
define('JS_LANG_Other', '기타');
define('JS_LANG_OtherEmail', '기타 이메일');
define('JS_LANG_Notes', '메모');
define('JS_LANG_Groups', '그룹');
define('JS_LANG_ShowAddFields', '세부 항목 보이기');
define('JS_LANG_HideAddFields', '세부 항목 감추기');
define('JS_LANG_EditContact', '연락처 정보 수정');
define('JS_LANG_GroupName', '그룹명');
define('JS_LANG_AddContacts', '연락처 추가');
define('JS_LANG_CommentAddContacts', '(하나 이상의 연락처를 추가할 경우 콤마(,)를 사용하세요.)');
define('JS_LANG_CreateGroup', '그룹 생성');
define('JS_LANG_Rename', '이름 변경');
define('JS_LANG_MailGroup', '그룹 메일 보내기');
define('JS_LANG_RemoveFromGroup', '그룹에서 삭제');
define('JS_LANG_UseImportTo', '가져오기를 이용하여 Microsoft Outlook 또는 Outlook Express의 연락처를 가져올수 있습니다.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', '가져오기를 실행할 연락처 파일을 선택하세요. (.CSV 포맷)');
define('JS_LANG_Import', '가져오기');
define('JS_LANG_ContactsMessage', '연락처 페이지 입니다.!!!');
define('JS_LANG_ContactsCount', '연락처');
define('JS_LANG_GroupsCount', '그룹');

// webmail 4.1 constants
define('PicturesBlocked', '개인 정보를 보호하기 위하여 이미지 파일은 인터넷에서 자동으로 다운로드 되지 않습니다.');
define('ShowPictures', '이미지 다운로드');
define('ShowPicturesFromSender', '이 사람이 보낸 메일은 항상 이미지를 다운로드하고 보이기');
define('AlwaysShowPictures', '항상 이미지를 다운로드하고 보이기');

define('TreatAsOrganization', '그룹에 세부 정보 추가하기');

define('WarningGroupAlreadyExist', '동일한 이름의 그룹이 있습니다. 다른 이름을 사용하여 주세요.');
define('WarningCorrectFolderName', '정확한 메일함 이름을 입력하여 주세요.');
define('WarningLoginFieldBlank', '아이디를 입력하여 주세요.');
define('WarningCorrectLogin', '정확한 아이디를 입력하여 주세요.');
define('WarningPassBlank', '비빌번호를 입력하여 주세요.');
define('WarningCorrectIncServer', '정확한 POP3(IMAP) 서버 주소를 입력하여 주세요.');
define('WarningCorrectSMTPServer', '정확한 발송 메일 주소를 입력하여 주세요.');
define('WarningFromBlank', '보내는 사람을 입력하여 주세요.');
define('WarningAdvancedDateFormat', '날짜 형식을 선택하여 주세요.');

define('AdvancedDateHelpTitle', '날짜 형식 세부설정');
define('AdvancedDateHelpIntro', '&quot;날짜 형식 세부설정&quot;을 통하여 개별적인 날짜 표시 형식을 설정할 수 있습니다. 다음의 텍스트 입력 창에 구분 기호 \':\' 또는 \'/\'를 사용하여 각각의 표시 형식을 직접 입력하시기 바랍니다. ');
define('AdvancedDateHelpConclusion', '예를 들어, 세부설정의 텍스트 입력 창에 &quot;mm/dd/yyyy&quot;를 입력하면, 날짜는 월/일/년(예, 11/23/2010) 형태로 표시됩니다.');
define('AdvancedDateHelpDayOfMonth', '월중 해당 일 (1 ~ 31)');
define('AdvancedDateHelpNumericMonth', '해당 월 (1 ~ 12)');
define('AdvancedDateHelpTextualMonth', '해당 월 (1월 ~ 12월)');
define('AdvancedDateHelpYear2', '년도, 2자리');
define('AdvancedDateHelpYear4', '년도, 4자리');
define('AdvancedDateHelpDayOfYear', '년중 해당 일 (1 ~ 366)');
define('AdvancedDateHelpQuarter', '분기');
define('AdvancedDateHelpDayOfWeek', '주중 해당 요일 (월 ~ 일)');
define('AdvancedDateHelpWeekOfYear', '년중 해당 주 (1 ~ 53)');

define('InfoNoMessagesFound', '메일이 없습니다.');
define('ErrorSMTPConnect', 'SMTP 서버에 접속할 수 없습니다. SMTP 서버 설정을 확인하여 주세요.');
define('ErrorSMTPAuth', '아이디 또는 비밀번호가 일치하지 않습니다. 사용자 인증에 실패하였습니다.');
define('ReportMessageSent', '메일이 발송되었습니다.');
define('ReportMessageSaved', '메일이 저장되었습니다.');
define('ErrorPOP3Connect', 'POP3 서버에 접속할 수 없습니다. POP3 서버 설정을 확인하여 주세요.');
define('ErrorIMAP4Connect', 'IMAP4 서버에 접속할 수 없습니다. IMAP4 서버 설정을 확인하여 주세요.');
define('ErrorPOP3IMAP4Auth', '아이디 또는 비밀번호가 일치하지 않습니다. 사용자 인증에 실패하였습니다.');
define('ErrorGetMailLimit', '할당된 메일함의 최대 용량을 초과하였습니다.');

define('ReportSettingsUpdatedSuccessfuly', '설정이 성공적으로 업데이트 되었습니다.');
define('ReportAccountCreatedSuccessfuly', '계정이 성공적으로 생성되었습니다.');
define('ReportAccountUpdatedSuccessfuly', '계정이 성공적으로 업데이트 되었습니다.');
define('ConfirmDeleteAccount', '정말 계정을 삭제하시겠습니까?');
define('ReportFiltersUpdatedSuccessfuly', '규칙이 성공적으로 업데이트 되었습니다.');
define('ReportSignatureUpdatedSuccessfuly', '서명이 성공적으로 업데이트 되었습니다.');
define('ReportFoldersUpdatedSuccessfuly', '폴더가 성공적으로 업데이트 되었습니다.');
define('ReportContactsSettingsUpdatedSuccessfuly', '연락처 설정이 성공적으로 업데이트 되었습니다.');

define('ErrorInvalidCSV', '선택한 파일은 올바른 CSV 형식의 파일이 아닙니다.');
//그룹 "친구"가 성공적으로 추가되었습니다.
define('ReportGroupSuccessfulyAdded1', '그룹');
define('ReportGroupSuccessfulyAdded2', '이(가) 성공적으로 추가되었습니다.');
define('ReportGroupUpdatedSuccessfuly', '그룹이 성공적으로 업데이트 되었습니다.');
define('ReportContactSuccessfulyAdded', '연락처가 성공적으로 추가되었습니다.');
define('ReportContactUpdatedSuccessfuly', '연락처가 성공적으로 업데이트 되었습니다.');
//연락처가 그룹 "친구"에 추가되었습니다.
define('ReportContactAddedToGroup', '연락처가 그룹에 추가되었습니다.');
define('AlertNoContactsGroupsSelected', '연락처 또는 그룹이 선택되지 않았습니다.');

define('InfoListNotContainAddress', '목록에 찾는 주소가 없으면 항목의 첫 글자만 입력해 보세요.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Direct 모드. 웹메일 서비스를 거치지 않고 메일 서버로 직접 연결.');

define('FolderInbox', '받은메일함');
define('FolderSentItems', '보낸메일함');
define('FolderDrafts', '임시저장함');
define('FolderTrash', '휴지통');

define('FileLargerAttachment', '선택된 첨부 파일의 크기가 허용된 첨부 파일 크기 제한을 초과합니다.');
define('FilePartiallyUploaded', '알수 없는 오류로 일부 파일만 업로드 되었습니다.');
define('NoFileUploaded', '파일을 업로드 하지 못하였습니다.');
define('MissingTempFolder', '임시저장함을 찾을 수 없습니다.');
define('MissingTempFile', '임시 저장된 파일을 찾을 수 없습니다.');
define('UnknownUploadError', '파일 업로드 중 알수 없는 오류가 발생하였습니다.');
define('FileLargerThan', '파일 업로드 오류.  최대 업로드 허용 파일 크기 - ');
define('PROC_CANT_LOAD_DB', '데이터베이스에 연결할 수 없습니다.');
define('PROC_CANT_LOAD_LANG', '필요한 언어 파일을 찾을 수 없습니다.');
define('PROC_CANT_LOAD_ACCT', '계정이 존재하지 않습니다. 최근 삭제된 계정일 수 있습니다.');

define('DomainDosntExist', '메일 서버에 해당 도메인이 존재하지 않습니다.');
define('ServerIsDisable', '관리자에 의해 메일 서버 접근이 금지되었습니다.');

define('PROC_ACCOUNT_EXISTS', '동일한 계정이 이미 등록되어 있습니다.');
define('PROC_CANT_GET_MESSAGES_COUNT', '해당 메일함의 메일 개수를 확인할 수 없습니다.');
define('PROC_CANT_MAIL_SIZE', '메일 저장 용량을 확인 할 수 없습니다.');

define('Organization', '조직');
define('WarningOutServerBlank', '발신 메일 서버 정보를 입력하여 주세요.');

define('JS_LANG_Refresh', '새로고침');
define('JS_LANG_MessagesInInbox', '수신된 메일');
define('JS_LANG_InfoEmptyInbox', '받은메일함이 비어 있습니다.');

// webmail 4.2 constants
define('BackToList', '메일목록');
define('InfoNoContactsGroups', '그룹 또는 연락처가 없습니다.');
define('InfoNewContactsGroups', '새로 연락처/그룹을 추가하거나 MS Outlook의 .CSV 파일을<br/> 통하여 연락처 정보를 가져올 수 있습니다.');
define('DefTimeFormat', '시간 표시 형식');
define('SpellNoSuggestions', '추천 단어 없음');
define('SpellWait', '잠시만 기다려 주세요&hellip;');

define('InfoNoMessageSelected', '선택된 메일이 없습니다.');
define('InfoSingleDoubleClick', '목록에서 메일을 선택하여 미리보기 하거나 선택 메일을 더블 클릭하여<br/>상세보기로 메일 내용을 확인하시기 바랍니다.');

// calendar
define('TitleDay', '일간보기');
define('TitleWeek', '주간보기');
define('TitleMonth', '월간보기');

define('ErrorNotSupportBrowser', '현재의 브라우저로 캘린더를 볼 수 없습니다. Internet Explorer 6.0 이상, FireFox 2.0 이상, Opera 9.0 이상, Safari 3.0.2 이상 버전으로 업데이트 하시기 바랍니다.');
define('ErrorTurnedOffActiveX', '이 어플리케이션을 이용하려면 ActiveX를 사용할 수 있도록 설정을 변경하셔야 합니다.');

define('Calendar', '캘린더');

define('TabDay', '일간');
define('TabWeek', '주간');
define('TabMonth', '월간');

define('ToolNewEvent', '새&nbsp;일정');
define('ToolBack', '뒤로');
define('ToolToday', '오늘');
define('AltNewEvent', '새 일정');
define('AltBack', '뒤로');
define('AltToday', '오늘');
define('CalendarHeader', '캘린더');
define('CalendarsManager', '캘린더 관리');

define('CalendarActionNew', '새 캘린더');
define('EventHeaderNew', '새 일정');
define('CalendarHeaderNew', '새 캘린더');

define('EventSubject', '제목');
define('EventCalendar', '일정');
define('EventFrom', '시작');
define('EventTill', '종료');
define('CalendarDescription', '설명');
define('CalendarColor', '적용색상');
define('CalendarName', '이름');
define('CalendarDefaultName', '내 일정');

define('ButtonSave', '저장');
define('ButtonCancel', '취소');
define('ButtonDelete', '삭제');

define('AltPrevMonth', '지난 달');
define('AltNextMonth', '다음 달');

define('CalendarHeaderEdit', '일정 수정');
define('CalendarActionEdit', '일정 수정');
define('ConfirmDeleteCalendar', '일정을 삭제하시겠습니까?');
define('InfoDeleting', '삭제 중&hellip;');
define('WarningCalendarNameBlank', '캘린더 이름을 입력하여 주세요.');
define('ErrorCalendarNotCreated', '일정이 추가되지 않았습니다.');
define('WarningSubjectBlank', '제목을 입력하여 주세요.');
define('WarningIncorrectTime', '입력한 시간 형식에 잘못된 문자가 포함되어 있습니다.');
define('WarningIncorrectFromTime', '시작시간이 잘못되었습니다.');
define('WarningIncorrectTillTime', '종료시간이 잘못되었습니다.');
define('WarningStartEndDate', '종료일이 시작일보다 이전일 수 없습니다.');
define('WarningStartEndTime', '종료시간이 시작시간보다 이전일 수 없습니다.');
define('WarningIncorrectDate', '정확한 날자를 입력하여 주세요.');
define('InfoLoading', '로딩 중&hellip;');
define('EventCreate', '일정 추가');
define('CalendarHideOther', '다른 캘린더 감추기');
define('CalendarShowOther', '다른 캘린더 보기');
define('CalendarRemove', '캘린더 삭제');
define('EventHeaderEdit', '일정 수정');

define('InfoSaving', '저장 중&hellip;');
define('SettingsDisplayName', '이름 표시');
define('SettingsTimeFormat', '시간 형식');
define('SettingsDateFormat', '날짜 형식');
define('SettingsShowWeekends', '주말 보이기');
define('SettingsWorkdayStarts', '업무 시작');
define('SettingsWorkdayEnds', '업무 종료');
define('SettingsShowWorkday', '주간 보이기');
define('SettingsWeekStartsOn', '주간 시작');
define('SettingsDefaultTab', '기본 보기');
define('SettingsCountry', '국가');
define('SettingsTimeZone', '시간대');
define('SettingsAllTimeZones', '모든 시간대 표시');

define('WarningWorkdayStartsEnds', '주간 종료 시간이 주간 시작 시간보다 이전일 수 없습니다.');
define('ReportSettingsUpdated', '설정 값이 성공적으로 업데이트 되었습니다.');

define('SettingsTabCalendar', '캘린더');

define('FullMonthJanuary', '1월');
define('FullMonthFebruary', '2월');
define('FullMonthMarch', '3월');
define('FullMonthApril', '4월');
define('FullMonthMay', '5월');
define('FullMonthJune', '6월');
define('FullMonthJuly', '7월');
define('FullMonthAugust', '8월');
define('FullMonthSeptember', '9월');
define('FullMonthOctober', '10월');
define('FullMonthNovember', '11월');
define('FullMonthDecember', '12월');

define('ShortMonthJanuary', '1월');
define('ShortMonthFebruary', '2월');
define('ShortMonthMarch', '3월');
define('ShortMonthApril', '4월');
define('ShortMonthMay', '5월');
define('ShortMonthJune', '6월');
define('ShortMonthJuly', '7월');
define('ShortMonthAugust', '8월');
define('ShortMonthSeptember', '9월');
define('ShortMonthOctober', '10월');
define('ShortMonthNovember', '11월');
define('ShortMonthDecember', '12월');

define('FullDayMonday', '월요일');
define('FullDayTuesday', '화요일');
define('FullDayWednesday', '수요일');
define('FullDayThursday', '목요일');
define('FullDayFriday', '금요일');
define('FullDaySaturday', '토요일');
define('FullDaySunday', '일요일');

define('DayToolMonday', '월');
define('DayToolTuesday', '화');
define('DayToolWednesday', '수');
define('DayToolThursday', '목');
define('DayToolFriday', '금');
define('DayToolSaturday', '토');
define('DayToolSunday', '일');

define('CalendarTableDayMonday', '월');
define('CalendarTableDayTuesday', '화');
define('CalendarTableDayWednesday', '수');
define('CalendarTableDayThursday', '목');
define('CalendarTableDayFriday', '금');
define('CalendarTableDaySaturday', '토');
define('CalendarTableDaySunday', '일');

define('ErrorParseJSON', '서버로부터 전송된 JSON을 파싱할 수 없습니다.');

define('ErrorLoadCalendar', '캘린더를 불러올 수 없습니다.');
define('ErrorLoadEvents', '일정을 불러올 수 없습니다.');
define('ErrorUpdateEvent', '일정을 저장할 수 없습니다.');
define('ErrorDeleteEvent', '일정을 삭제할 수 없습니다.');
define('ErrorUpdateCalendar', '캘린더를 저장할 수가 없습니다.');
define('ErrorDeleteCalendar', '캘린더를 삭제할 수가 없습니다.');
define('ErrorGeneral', '서버에서 오류가 발생하였습니다. 나중에 다시 시도하시기 바랍니다.');

// webmail 4.3 constants
define('SharedTitleEmail', '이메일');
define('ShareHeaderEdit', '캘린더 공유');
define('ShareActionEdit', '캘린더 공유');
define('CalendarPublicate', '캘린더 웹에 공개하기');
define('CalendarPublicationLink', '링크');
define('ShareCalendar', '캘린더 공유');
define('SharePermission1', '변경 및 공유 관리 가능');
define('SharePermission2', '일정 변경 가능');
define('SharePermission3', '모든 일정 상세보기 가능');
define('SharePermission4', '해당 시간 다른 일정 유무만 확인 가능');
define('ButtonClose', '닫기');
define('WarningEmailFieldFilling', '이메일을 먼저 입력하여야 합니다.');
define('EventHeaderView', '일정보기');
define('ErrorUpdateSharing', '데이터를 공유하고 게시할 수 없습니다.');
define('ErrorUpdateSharing1', '사용자 %s(이)가 존재하지 않아 일정을 공유할 수 없습니다.');
define('ErrorUpdateSharing2', '사용자 %s의 캘린더에 현재 일정을 공유할 수가 없습니다.');
define('ErrorUpdateSharing3', '현재 일정은 이미 사용자 %s의 캘린더와 공유되어 있습니다.');
define('Title_MyCalendars', '내 캘린더');
define('Title_SharedCalendars', '공유 캘린더');
define('ErrorGetPublicationHash', '웹 게시 링크를 만들 수 없습니다.');
define('ErrorGetSharing', '공유를 추가할 수 없습니다.');
define('CalendarPublishedTitle', '캘리더가 웹에 게시되었습니다.');
define('RefreshSharedCalendars', '공유된 캘린더 새로고침');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', '구성원');

define('ReportMessagePartDisplayed', '메일의 일부 내용만 게시된 상태입니다.');
define('ReportViewEntireMessage', '메일의 모든 내용을 보려면,');
define('ReportClickHere', '여기를 클릭하세요.');
define('ErrorContactExists', '동일 이름과 이메일 주소를 가진 연락처가 이미 등록되어 있습니다..');

define('Attachments', '첨부 파일');

define('InfoGroupsOfContact', '해당 연락처가 속한 그룹은 체크되어 있습니다.');
define('AlertNoContactsSelected', '선택된 연락처가 없습니다.');
define('MailSelected', '선택된 주소로 메일 보내기');
define('CaptionSubscribed', '구독');

define('OperationSpam', '스팸지정');
define('OperationNotSpam', '스팸해제');
define('FolderSpam', '스팸메일함');

// webmail 4.4 contacts
define('ContactMail', '메일 보내기');
define('ContactViewAllMails', '이 이메일 주소와 관련된 모든 메일 보기');
define('ContactsMailThem', '메일 보내기');
define('DateToday', '오늘');
define('DateYesterday', '어제');
define('MessageShowDetails', '상세보기');
define('MessageHideDetails', '간략보기');
define('MessageNoSubject', '제목없음');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', '(으)로부터 메일 수신 / 수신 메일 계정 - ');
define('SearchClear', '검색 결과창 닫기');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', '#f에서 "#s"에 대한 검색 결과 :');
define('SearchResultsInAllFolders', '전체 메일함에서 "#s"에 대한 검색 결과 :');
define('AutoresponderTitle', '자동 응답');
define('AutoresponderEnable', '자동 응답 설정하기');
define('AutoresponderSubject', '제목');
define('AutoresponderMessage', '내용');
define('ReportAutoresponderUpdatedSuccessfuly', '자동 응답 설정이 성공적으로 업데이트 되었습니다.');
define('FolderQuarantine', '격리 저장소');

//calendar
define('EventRepeats', '반복주기');
define('NoRepeats', '반복하지 않음');
define('DailyRepeats', '매일');
define('WorkdayRepeats', '매주 주중 (월 - 금)');
define('OddDayRepeats', '매주 월, 수, 금');
define('EvenDayRepeats', '매주 화, 목');
define('WeeklyRepeats', '매주');
define('MonthlyRepeats', '매월');
define('YearlyRepeats', '매년');
define('RepeatsEvery', '반복 기간');
define('ThisInstance', '이번만');
define('AllEvents', '반복 설정된 모든 일정');
define('AllFollowing', '다음의 모든 일정');
define('ConfirmEditRepeatEvent', '현재의 반복 설정을 이번 일정만, 일정 모두 또는 이번 이후 모든 일정으로 구분하여 적용하시겠습니까?');
define('RepeatEventHeaderEdit', '반복 이벤트 수정');
define('First', '첫번째');
define('Second', '두번째');
define('Third', '세번째');
define('Fourth', '네번째');
define('Last', '마지막');
define('Every', '매번');
define('SetRepeatEventEnd', '반복 설정');
define('NoEndRepeatEvent', '무한 반복');
define('EndRepeatEventAfter', '반복 횟수 : ');
define('Occurrences', '번');
define('EndRepeatEventBy', '종료일');
define('EventCommonDataTab', '기본 입력 내용');
define('EventRepeatDataTab', '반복 입력 내용');
define('RepeatEventNotPartOfASeries', '일정이 변경되었습니다. 이 일정은 더 이상 반복 일정에 포함되지 않습니다.');
define('UndoRepeatExclusion', '반복 일정에 포함 취소');

define('MonthMoreLink', '%d개 이상...');
define('NoNewSharedCalendars', '새 공유 캘린더가 없습니다.');
define('NNewSharedCalendars', '%d개의 새 공유 캘린더를 찾았습니다.');
define('OneNewSharedCalendars', '1개의 새 공유 캘리더를 찾았습니다.');
define('ConfirmUndoOneRepeat', '반복 일정으로 이 일정을 복원하시겠습니까?');

define('RepeatEveryDayInfin', '매일');
define('RepeatEveryDayTimes', '매일, %TIMES% 번');
define('RepeatEveryDayUntil', '매일, %UNTIL% 까지');
define('RepeatDaysInfin', '매 %PERIOD% 일');
define('RepeatDaysTimes', '매 %PERIOD% 일, %TIMES% 번');
define('RepeatDaysUntil', '매 %PERIOD% 일, %UNTIL% 까지');

define('RepeatEveryWeekWeekdaysInfin', '매주 주중');
define('RepeatEveryWeekWeekdaysTimes', '매주 주중, %TIMES% 번');
define('RepeatEveryWeekWeekdaysUntil', '매주 주중, %UNTIL% 까지');
define('RepeatWeeksWeekdaysInfin', '매 %PERIOD% 주 간격 주중');
define('RepeatWeeksWeekdaysTimes', '매 %PERIOD% 주 간격 주중, %TIMES% 번');
define('RepeatWeeksWeekdaysUntil', '매 %PERIOD% 주 간격 주중, %UNTIL% 까지');

define('RepeatEveryWeekInfin', '매주 %DAYS%');
define('RepeatEveryWeekTimes', '매주 %DAYS%, %TIMES% 번');
define('RepeatEveryWeekUntil', '매주 %DAYS%, %UNTIL% 까지');
define('RepeatWeeksInfin', '매 %PERIOD% 주 간격 %DAYS%');
define('RepeatWeeksTimes', '매 %PERIOD% 주 간격 %DAYS%, %TIMES% 번');
define('RepeatWeeksUntil', '매 %PERIOD% 주 간격 %DAYS%, %UNTIL% 까지');

define('RepeatEveryMonthDateInfin', '매월 %DATE% 일');
define('RepeatEveryMonthDateTimes', '매월 %DATE% 일, %TIMES% 번');
define('RepeatEveryMonthDateUntil', '매월 %DATE% 일, %UNTIL% 까지');
define('RepeatMonthsDateInfin', '매 %PERIOD% 개월 간격 %DATE% 일');
define('RepeatMonthsDateTimes', '매 %PERIOD% 개월 간격 %DATE% 일, %TIMES% 번');
define('RepeatMonthsDateUntil', '매 %PERIOD% 개월 간격 %DATE% 일, %UNTIL% 까지');

define('RepeatEveryMonthWDInfin', '매월 %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', '매월 %NUMBER% %DAY%, %TIMES% 번');
define('RepeatEveryMonthWDUntil', '매월 %NUMBER% %DAY%, %UNTIL% 까지');
define('RepeatMonthsWDInfin', '매 %PERIOD% 개월 간격 %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', '매 %PERIOD% 개월 간격 %NUMBER% %DAY%, %TIMES% 번');
define('RepeatMonthsWDUntil', '매 %PERIOD% 개월 간격 %NUMBER% %DAY%, %UNTIL% 까지');

define('RepeatEveryYearDateInfin', '매년 %DATE% 일');
define('RepeatEveryYearDateTimes', '매년 %DATE% 일, %TIMES% 번');
define('RepeatEveryYearDateUntil', '매년 %DATE% 일, %UNTIL% 까지');
define('RepeatYearsDateInfin', '매 %PERIOD% 년 간격 %DATE% 일');
define('RepeatYearsDateTimes', '매 %PERIOD% 년 간격 %DATE% 일, %TIMES% 번');
define('RepeatYearsDateUntil', '매 %PERIOD% 년 간격 %DATE% 일, %UNTIL% 까지');

define('RepeatEveryYearWDInfin', '매년 %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', '매년 %NUMBER% %DAY%, %TIMES% 번');
define('RepeatEveryYearWDUntil', '매년 %NUMBER% %DAY%, %UNTIL% 까지');
define('RepeatYearsWDInfin', '매 %PERIOD% 년 간격 %NUMBER% %DAY%');
define('RepeatYearsWDTimes', '매 %PERIOD% 년 간격 %NUMBER% %DAY%, %TIMES% 번');
define('RepeatYearsWDUntil', '매 %PERIOD% 년 간격 %NUMBER% %DAY%, %UNTIL% 까지');

define('RepeatDescDay', '일');
define('RepeatDescWeek', '주');
define('RepeatDescMonth', '월');
define('RepeatDescYear', '년');

// webmail 4.5 contacts
define('WarningUntilDateBlank', '일정 반복 종료 날짜를 정확하게 입력하여 주세요.');
define('WarningWrongUntilDate', '일정 반복 종료 날짜가 시작 날짜보다 이전일 수 없습니다.');

define('OnDays', '요일');
define('CancelRecurrence', '반복 취소');
define('RepeatEvent', '일정 반복 설정');

define('Spellcheck', '맞춤법 확인');
define('LoginLanguage', '언어');
define('LanguageDefault', '기본');

// webmail 4.5.x new
define('EmptySpam', '스팸함 비우기');
define('Saving', '저장 중&hellip;');
define('Sending', '발송 중&hellip;');
define('LoggingOffFromServer', '서버에서 로그오프 중&hellip;');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', '선택한 메일을 스팸으로 지정할 수 없습니다.');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', '선택한 메일을 스팸 지정에서 해제할 수 없습니다.');
define('ExportToICalendar', 'iCalendar로 내보내기');
define('ErrorMaximumUsersLicenseIsExceeded', '계약된 사용자 라이센스 수량을 초과하여 더 이상 사용자를 추가할 수 없습니다.');
define('RepliedMessageTitle', '회신된 메일');
define('ForwardedMessageTitle', '전달된 메일');
define('RepliedForwardedMessageTitle', '회신 및 전달된 메일');
define('ErrorDomainExist', '등록된 도메인이 없어 사용자를 추가할 수 없습니다. 먼저 도메인을 등록하여 주세요.');

// webmail 4.7
define('RequestReadConfirmation', '읽음 확인 요청');
define('FolderTypeDefault', '기본');
define('ShowFoldersMapping', '기본 메일함으로 사용 (예, 내메일함을 보낸메일함으로 사용)');
define('ShowFoldersMappingNote', '예) 새로 만든 내메일함을 보낸메일함으로 사용하려면, 내메일함의 드롭다운 메뉴에서 대체할 메일함을 보낸메일함으로 선택하면 됩니다.');
define('FolderTypeMapTo', '대체 메일함');

define('ReminderEmailExplanation', '본 메일은 캘린더 &quot;%CALENDAR_NAME%&quot;에서 설정한 일정 알림 기능에 의하여 %EMAIL% 계정으로 수신된 메일입니다.');
define('ReminderOpenCalendar', '캘린더 열기');

define('AddReminder', '일정 알림 설정');
define('AddReminderBefore', '% 전에 이 일정 알림');
define('AddReminderAnd', '그리고 % 전에');
define('AddReminderAlso', '또한 % 전에');
define('AddMoreReminder', '추가 알림');
define('RemoveAllReminders', '모든 알림 제거');
define('ReminderNone', '없음');
define('ReminderMinutes', '분');
define('ReminderHour', '시간');
define('ReminderHours', '시간');
define('ReminderDay', '일');
define('ReminderDays', '일');
define('ReminderWeek', '주');
define('ReminderWeeks', '주');
define('Allday', '종일');

define('Folders', '메일함');
define('NoSubject', '제목 없음');
define('SearchResultsFor', '검색 결과 : ');

define('Back', '뒤로');
define('Next', '다음');
define('Prev', '이전');

define('MsgList', '메일목록');
define('Use24HTimeFormat', '24시간 표시 형식 사용');
define('UseCalendars', '캘린더 사용');
define('Event', '일정');
define('CalendarSettingsNullLine', '캘린더가 없습니다.');
define('CalendarEventNullLine', '일정이 없습니다.');
define('ChangeAccount', '계정 변경');

define('TitleCalendar', '캘린더');
define('TitleEvent', '일정');
define('TitleFolders', '메일함');
define('TitleConfirmation', '확인');

define('Yes', '네');
define('No', '아니오');

define('EditMessage', '메일 수정');

define('AccountNewPassword', '새 비밀번호');
define('AccountConfirmNewPassword', '비밀번호 확인');
define('AccountPasswordsDoNotMatch', '입력된 비밀번호가 서로 맞지 않습니다.');

define('ContactTitle', '표시이름');
define('ContactFirstName', '이름');
define('ContactSurName', '성');

define('ContactNickName', '별명');

define('CaptchaTitle', '자동 등록방지');
define('CaptchaReloadLink', '새로 고침');
define('CaptchaError', '입력 문자가 일치하지 않습니다.');

define('WarningInputCorrectEmails', '정확한 이메일 주소를 입력하여 주세요.');
define('WrongEmails', '잘못된 이메일 주소:');

define('ConfirmBodySize1', '입력 글자 수가 최대 허용치를 초과하였습니다.');
define('ConfirmBodySize2', '최대 허용치가 초과된 글자는 모두 잘려서 송신됩니다. 내용을 수정하고 싶으시면 "취소"를 선택하시기 바랍니다.');
define('BodySizeCounter', '카운터');
define('InsertImage', '이미지 삽입');
define('ImagePath', '이미지 경로');
define('ImageUpload', '삽입');
define('WarningImageUpload', '첨부된 파일은 이미지 파일이 아닙니다. 이미지 파일을 선택하시기 바랍니다.');

define('ConfirmExitFromNewMessage', '저장하지 않고 현재 페이지를 벗어나면, 최근 저장 이후의 모든 변경 내용은 삭제됩니다. 현재 페이지에서 계속 작업하려면 "취소"를 선택하시기 바랍니다.');
define('SensivityConfidential', '현재 메일을 비밀 메일로 취급하시기 바랍니다.');
define('SensivityPrivate', '현재 메일을 비공개 메일로 취급하시기 바랍니다.');
define('SensivityPersonal', '현재 메일을 개인 메일로 취급하시기 바랍니다.');

define('ReturnReceiptTopText', '이 메일을 보낸사람이 메일 수신 확인을 요청하였습니다.');
define('ReturnReceiptTopLink', '보낸사람에게 메일 수신 확인을 보내려면 여기를 클릭하세요.');
define('ReturnReceiptSubject', '수신 확인');
define('ReturnReceiptMailText1', '본 메일은 수신 확인 메일입니다.');
define('ReturnReceiptMailText2', 'Note: 본 수신 확인 메일은 받는사람이 단지 메일을 수신하였음을 알리는 메일입니다. 이 메일을 통하여 받는사람이 메일을 읽거나 이해하였는지 확인할 수는 없습니다.');
define('ReturnReceiptMailText3', '제목 포함');

define('SensivityMenu', '보안등급');
define('SensivityNothingMenu', '없음');
define('SensivityConfidentialMenu', '비밀');
define('SensivityPrivateMenu', '비공개');
define('SensivityPersonalMenu', '개인');

define('ErrorLDAPonnect', 'LDAP 서버에 접속할 수 없습니다.');

define('MessageSizeExceedsAccountQuota', '메일의 크기가 사용자 계정에 할당된 용량을 초과하였습니다.');
define('MessageCannotSent', '메일을 보낼 수가 없습니다.');
define('MessageCannotSaved', '메일을 저장할 수 없습니다.');

define('ContactFieldTitle', '항목');
define('ContactDropDownTO', '받는사람');
define('ContactDropDownCC', '참조');
define('ContactDropDownBCC', '비밀참조');

// 4.9
define('NoMoveDelete', '휴지통에 여유 공간이 없어 메일을 휴지통에 버릴 수 없습니다. 선택 메일을 바로 삭제할까요? ');
define('WarningFieldBlank', '선택 항목은 반드시 입력되어야 합니다.');
define('WarningPassNotMatch', '비밀번호가 일치하지 않습니다. 다시 확인하시기 바랍니다.');
define('PasswordResetTitle', '비밀번호 찾기 - %d 단계');
define('NullUserNameonReset', '사용자');
define('IndexResetLink', '비밀번호 찾기');
define('IndexRegLink', '계정 등록');

define('RegDomainNotExist', '도메인이 존재하지 않습니다.');
define('RegAnswersIncorrect', '답변이 일치하지 않습니다.');
define('RegUnknownAdress', '등록되지 않은 이메일 주소입니다.');
define('RegUnrecoverableAccount', '입력된 이메일 주소는 비밀번호 찾기를 사용할 수 없습니다.');
define('RegAccountExist', '입렫된 이메일 주소는 이미 사용되고 있습니다.');
define('RegRegistrationTitle', '등록');
define('RegName', '이름');
define('RegEmail', '이메일 주소');
define('RegEmailDesc', '이메일 주소(예: myname@domain.com)가 서비스 로그인에 사용됩니다.');
define('RegSignMe', '로그인 정보 저장');
define('RegSignMeDesc', '현재 PC에서 서비스 로그인 시 아이디와 비밀번호를 다시 물어보지 않습니다.');
define('RegPass1', '비밀번호');
define('RegPass2', '비밀번호 확인 ');
define('RegQuestionDesc', '비밀번호 분실 시 사용자를 확인하기 위한 확인 질문과 그에 맞는 답변 두 개를 입력하시기 바랍니다.');
define('RegQuestion1', '확인 질문 1');
define('RegAnswer1', '답변 1');
define('RegQuestion2', '확인 질문 2');
define('RegAnswer2', '답변 2');
define('RegTimeZone', '시간대');
define('RegLang', '설정 언어');
define('RegCaptcha', '자동 등록 방지');
define('RegSubmitButtonValue', '등록');

define('ResetEmail', '이메일 주소를 입력하여 주세요.');
define('ResetEmailDesc', '등록에 사용한 이메일 주소를 입력하여 주세요.');
define('ResetCaptcha', '자동 등록 방지');
define('ResetSubmitStep1', '확인');
define('ResetQuestion1', '확인 질문 1');
define('ResetAnswer1', '답변');
define('ResetQuestion2', '확인 질문 2');
define('ResetAnswer2', '답변');
define('ResetSubmitStep2', '확인');

define('ResetTopDesc1Step2', '이메일 주소를 입력하여 주세요.');
define('ResetTopDesc2Step2', '정확한 정보를 확인하여 주세요.');

define('ResetTopDescStep3', '새로운 비밀번호를 입력하시기 바랍니다.');

define('ResetPass1', '새 비밀번호');
define('ResetPass2', '비밀번호 확인');
define('ResetSubmitStep3', '확인');
define('ResetDescStep4', '비밀번호가 변경되었습니다.');
define('ResetSubmitStep4', '취소');

define('RegReturnLink', '로그인 화면으로 돌아가기');
define('ResetReturnLink', '로그인 화면으로 돌아가기');

// Appointments
define('AppointmentAddGuests', '미팅 약속');
define('AppointmentRemoveGuests', '미팅 취소');
define('AppointmentListEmails', '이메일 주소를 입력하고 저장을<br/>눌러주시기 바랍니다.(여러명일 경우 \',\' 사용) ');
define('AppointmentParticipants', '참석자');
define('AppointmentRefused', '취소 미팅');
define('AppointmentAwaitingResponse', '회신 대기 미팅');
define('AppointmentInvalidGuestEmail', '다음 참석자의 이메일 주소를 확인할 수 없습니다:');
define('AppointmentOwner', '미팅 주관자');

define('AppointmentMsgTitleInvite', '미팅 초대');
define('AppointmentMsgTitleUpdate', '미팅 정보가 수정되었습니다.');
define('AppointmentMsgTitleCancel', '미팅이 취소되었습니다.');
define('AppointmentMsgTitleRefuse', '참석자 %guest%이(가) 미팅 참석을 거절하였습니다.');
define('AppointmentMoreInfo', '상세 정보');
define('AppointmentOrganizer', '게시자');
define('AppointmentEventInformation', '미팅 정보');
define('AppointmentEventWhen', '날짜');
define('AppointmentEventParticipants', '참석자');
define('AppointmentEventDescription', '내용');
define('AppointmentEventWillYou', '참석하시겠습니까?');
define('AppointmentAdditionalParameters', '추가 항목');
define('AppointmentHaventRespond', '아직 미응답');
define('AppointmentRespondYes', '참석합니다.');
define('AppointmentRespondMaybe', '아직 확신할 수 없습니다.');
define('AppointmentRespondNo', '참석하지 않습니다.');
define('AppointmentGuestsChangeEvent', '참석자가 미팅 수정 가능');

define('AppointmentSubjectAddStart', '미팅에 초대되었습니다. ');
define('AppointmentSubjectAddFrom', '초대자 ');
define('AppointmentSubjectUpdateStart', '미팅 수정 ');
define('AppointmentSubjectDeleteStart', '미팅 취소 ');
define('ErrorAppointmentChangeRespond', '참석 여부를 변경할 수 없음');
define('SettingsAutoAddInvitation', '미팅 초대를 캘린더에 자동으로 추가');
define('ReportEventSaved', '정보가 저장되었습니다.');
define('ReportAppointmentSaved', '그리고 초대장이 발송되었습니다.');
define('ErrorAppointmentSend', '초대장을 발송할 수 없습니다.');
define('AppointmentEventName', '제목:');

// End appointments

define('ErrorCantUpdateFilters', '규칙을 업데이트 할 수 없습니다.');

define('FilterPhrase', '수신된 메일의 %field 항목에 %string 이(가) %condition 인 경우, %action');
define('FiltersAdd', '규칙 추가');
define('FiltersCondEqualTo', '일치');
define('FiltersCondContainSubstr', '포함');
define('FiltersCondNotContainSubstr', '포함하지 않음');
define('FiltersActionDelete', '삭제');
define('FiltersActionMove', '이동');
define('FiltersActionToFolder', '- 메일함 지정 %folder');
define('FiltersNo', '지정된 규칙이 없습니다.');

define('ReminderEmailFriendly', '메일 알림');
define('ReminderEventBegin', '시작 : ');

define('FiltersLoading', '규칙 로딩 중...');
define('ConfirmMessagesPermanentlyDeleted', '메일함 내 모든 메일이 영구적으로 삭제됩니다.');

define('InfoNoNewMessages', '새로 수신된 메일이 없습니다.');
define('TitleImportContacts', '연락처 가져오기');
define('TitleSelectedContacts', '선택된 연락처');
define('TitleNewContact', '새 연락처');
define('TitleViewContact', '연락처 보기');
define('TitleEditContact', '연락처 수정');
define('TitleNewGroup', '새 그룹');
define('TitleViewGroup', '그룹 보기');

define('AttachmentComplete', '완료.');

define('TestButton', '테스트');
define('AutoCheckMailIntervalLabel', '자동 확인 주기');
define('AutoCheckMailIntervalDisableName', '자동 확인 해제');
define('ReportCalendarSaved', '일정이 저장되었습니다.');

define('ContactSyncError', '동기화 실패');
define('ReportContactSyncDone', '동기화 완료');

define('MobileSyncUrlTitle', '모바일 디바이스 동기화 URL');
define('MobileSyncLoginTitle', '모바일 디바이스 동기화 로그인');

define('QuickReply', '빠른 회신');
define('SwitchToFullForm', '전체 보기');
define('SortFieldDate', '날짜');
define('SortFieldFrom', '보낸사람');
define('SortFieldSize', '크기');
define('SortFieldSubject', '제목');
define('SortFieldFlag', '플래그');
define('SortFieldAttachments', '첨부파일');
define('SortOrderAscending', '오름차순');
define('SortOrderDescending', '내림차순');
define('ArrangedBy', '정렬');

define('MessagePaneToRight', '미리보기 창을 메일 목록 오른쪽에 표시 (체크 해제 시 아래쪽에 표시)');

define('SettingsTabMobileSync', '모바일 디바이스 동기화');

define('MobileSyncContactDataBaseTitle', '모바일 디바이스 동기화 연락처 데이터베이스');
define('MobileSyncCalendarDataBaseTitle', '모바일 디바이스 동기화 일정 데이터베이스');
define('MobileSyncTitleText', '다음과 같이 변수 설정을 통하여 웹메일을 SyncML-enabled 모바일 디바이스와 동기화 시킬 수 있습니다.<br />"Mobile Sync URL" specifies path to SyncML Data Synchronization server, "Mobile Sync Login" is your login on SyncML Data Synchronization Server and use your own password upon request. Also, some devices need to specify database name for contact and calendar data.<br />Use "Mobile sync contact database" and "Mobile sync calendar database" respectively.');
define('MobileSyncEnableLabel', '모바일 디바이스 동기화 활성');

define('SearchInputText', '검색');

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

define('ReadAboutCSVLink', 'Read about CSV file fields');

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
