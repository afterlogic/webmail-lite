<?php
define('PROC_ERROR_ACCT_CREATE', 'ในระหว่างการสร้างบัญชีได้รับการเกิดข้อผิดพลาด');
define('PROC_WRONG_ACCT_PWD', 'รหัสผ่านผิดบัญชี');
define('PROC_CANT_LOG_NONDEF', 'เข้าสู่มาตรฐานบัญชีล้มเหลว');
define('PROC_CANT_INS_NEW_FILTER', 'ไม่สามารถแทรกตัวกรองใหม่');
define('PROC_FOLDER_EXIST', 'ชื่อโฟลเดอร์ที่มีอยู่แล้ว');
define('PROC_CANT_CREATE_FLD', 'ไม่สามารถสร้างโฟลเดอร์');
define('PROC_CANT_INS_NEW_GROUP', 'ไม่สามารถแทรกกลุ่มใหม่');
define('PROC_CANT_INS_NEW_CONT', 'ไม่สามารถแทรกติดต่อใหม่');
define('PROC_CANT_INS_NEW_CONTS', 'ที่ติดต่อไม่สามารถเพิ่ม');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'ที่ติดต่อไม่สามารถเพิ่ม');
define('PROC_ERROR_ACCT_UPDATE', 'มีข้อผิดพลาดในขณะที่การอัปเดตบัญชี');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'ไม่สามารถติดต่ออัพเดทการตั้งค่า');
define('PROC_CANT_GET_SETTINGS', 'ไม่สามารถรับการตั้งค่า');
define('PROC_CANT_UPDATE_ACCT', 'ไม่สามารถอัปเดทบัญชี');
define('PROC_ERROR_DEL_FLD', 'ในขณะที่ลบโฟลเดอร์เป็นข้อผิดพลาด');
define('PROC_CANT_UPDATE_CONT', 'ไม่สามารถอัปเดตที่ติดต่อ');
define('PROC_CANT_GET_FLDS', 'เกิดข้อผิดพลาดในการอ่านรายการที่ติดต่อ');
define('PROC_CANT_GET_MSG_LIST', 'ไม่สามารถรับข้อความรายการ');
define('PROC_MSG_HAS_DELETED', 'ข้อความนี้ได้ถูกลบออกจากเมลเซิร์ฟเวอร์');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'ไม่สามารถโหลดการตั้งค่าที่ติดต่อ');
define('PROC_CANT_LOAD_SIGNATURE', 'ไม่สามารถโหลดบัญชีลายเซ็น');
define('PROC_CANT_GET_CONT_FROM_DB', 'ไม่สามารถได้รับการติดต่อจากฐานข้อมูล');
define('PROC_CANT_GET_CONTS_FROM_DB', 'ข้อผิดพลาดขณะอ่านจากฐานข้อมูลรายชื่อ');
define('PROC_CANT_DEL_ACCT_BY_ID', 'ไม่สามารถลบบัญชี');
define('PROC_CANT_DEL_FILTER_BY_ID', 'ไม่สามารถลบกรอง');
define('PROC_CANT_DEL_CONT_GROUPS', 'ที่ติดต่อหรือกลุ่มไม่ถูกลบ');
define('PROC_WRONG_ACCT_ACCESS', 'ที่ไม่ได้รับอนุญาตพยายามเข้าถึงบัญชีของผู้ใช้อื่นที่ระบุ');
define('PROC_SESSION_ERROR', 'ก่อนหน้านี้เซสชันถูกยกเลิกเนื่องจากการหยุดพักชั่วคราว');

define('MailBoxIsFull', 'กล่องจดหมายเต็ม');
define('WebMailException', 'เว็บเมล์เกิดข้อผิดพลาด');
define('InvalidUid', 'Uid ข่าวไม่ถูกต้อง');
define('CantCreateContactGroup', 'ไม่สามารถสร้างกลุ่มการติดต่อ');
define('CantCreateUser', 'ไม่สามารถสร้างผู้ใช้');
define('CantCreateAccount', 'ไม่สามารถสร้างบัญชี');
define('SessionIsEmpty', 'เซสชันว่างเปล่า');
define('FileIsTooBig', 'ไฟล์มีขนาดใหญ่เกินไป');

define('PROC_CANT_MARK_ALL_MSG_READ', 'ไม่สามารถทำเครื่องหมายข้อความทั้งหมดเป็นอ่านแล้ว');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'ไม่สามารถทำเครื่องหมายรายการทั้งหมดเป็นข้อความที่ยังไม่ได้อ่าน');
define('PROC_CANT_PURGE_MSGS', 'ไม่สามารถทำเครื่องหมายรายการทั้งหมดเป็นข้อความที่ยังไม่ได้อ่าน');
define('PROC_CANT_DEL_MSGS', 'ไม่สามารถกวาดล้างข้อความ (รายการ)');
define('PROC_CANT_UNDEL_MSGS', 'ไม่สามารถยกเลิกข้อความ (รายการ)');
define('PROC_CANT_MARK_MSGS_READ', 'ไม่สามารถทำเครื่องหมายข้อความ (รายการ) เป็นอ่านแล้ว');
define('PROC_CANT_MARK_MSGS_UNREAD', 'ไม่สามารถทำเครื่องหมายข้อความ (รายการ) ว่ายังไม่ได้อ่าน');
define('PROC_CANT_SET_MSG_FLAGS', 'ข้อความธงไม่สามารถตั้งค่า');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'ข้อความธงไม่สามารถลบ');
define('PROC_CANT_CHANGE_MSG_FLD', 'ไม่สามารถเปลี่ยนข้อความ (รายการ) โฟลเดอร์');
define('PROC_CANT_SEND_MSG', 'ไม่สามารถส่งข้อความ');
define('PROC_CANT_SAVE_MSG', 'ไม่สามารถบันทึกข้อความ');
define('PROC_CANT_GET_ACCT_LIST', 'ไม่สามารถรับบัญชีรายชื่อ');
define('PROC_CANT_GET_FILTER_LIST', 'ไม่สามารถรับรายการตัวกรอง');

define('PROC_CANT_LEAVE_BLANK', 'คุณไม่สามารถปล่อย * ฟิลด์ที่ว่างเปล่า');

define('PROC_CANT_UPD_FLD', 'ไม่สามารถปรับปรุงโฟลเดอร์');
define('PROC_CANT_UPD_FILTER', 'ไม่สามารถอัปเดทตัวกรองจดหมาย');

define('ACCT_CANT_ADD_DEF_ACCT', 'บัญชีนี้ไม่สามารถเพิ่มเพราะเราใช้เป็นค่าเริ่มต้นบัญชีโดยผู้ใช้รายอื่นแล้ว');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'สถานะบัญชีนี้ไม่สามารถเปลี่ยนค่าเริ่มต้น');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'ไม่สามารถสร้างบัญชีใหม่ (IMAP4 เชื่อมต่อข้อผิดพลาด)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'ไม่สามารถลบบัญชีเริ่มต้นล่าสุด');

define('LANG_LoginInfo', 'ข้อมูลล็อกอิน');
define('LANG_Email', 'อีเมล์');
define('LANG_Login', 'ล็อกอิน');
define('LANG_Password', 'รหัสผ่าน');
define('LANG_IncServer', 'อีเมลเซิร์ฟเวอร์');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'พอร์ต');
define('LANG_OutServer', 'อีเมลขาออก');
define('LANG_OutPort', 'พอร์ต');
define('LANG_UseSmtpAuth', 'ใช้การตรวจสอบ SMTP');
define('LANG_SignMe', 'เข้าสู่ระบบโดยอัตโนมัติ');
define('LANG_Enter', 'ลงทะเบียน');

// interface strings

define('JS_LANG_TitleLogin', 'ล็อกอิน');
define('JS_LANG_TitleMessagesListView', 'รายการข้อความ');
define('JS_LANG_TitleMessagesList', 'ข่าวรายการ');
define('JS_LANG_TitleViewMessage', 'ดูข้อความ');
define('JS_LANG_TitleNewMessage', 'ข้อความใหม่');
define('JS_LANG_TitleSettings', 'การตั้งค่า');
define('JS_LANG_TitleContacts', 'ติดต่อ');

define('JS_LANG_StandardLogin', 'เข้าสู่ระบบมาตรฐาน');
define('JS_LANG_AdvancedLogin', 'เข้าสู่ระบบขั้นสูง');

define('JS_LANG_InfoWebMailLoading', 'เว็บเมล์กำลังโหลด');
define('JS_LANG_Loading', 'กำลังโหลด');
define('JS_LANG_InfoMessagesLoad', 'เว็บเมล์กำลังโหลดรายการข้อความ');
define('JS_LANG_InfoEmptyFolder', 'โฟลเดอร์ว่างเปล่า');
define('JS_LANG_InfoPageLoading', 'หน้านี้ยังคงโหลด');
define('JS_LANG_InfoSendMessage', 'ข้อความที่ถูกส่ง');
define('JS_LANG_InfoSaveMessage', 'ข้อความนี้ถูกบันทึก');
define('JS_LANG_InfoHaveImported', 'คุณได้นำเข้า');
define('JS_LANG_InfoNewContacts', 'เพิ่มในรายชื่อที่ติดต่อของคุณ');
define('JS_LANG_InfoToDelete', 'ในการลบ');
define('JS_LANG_InfoDeleteContent', 'โฟลเดอร์ที่คุณควรลบทั้งหมดของเนื้อหาแรก');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'ลบที่ไม่ว่างเปล่าโฟลเดอร์นี้ไม่ได้รับอนุญาต. ในการลบไม่ checkable โฟลเดอร์ลบเนื้อหาของพวกเขาเป็นครั้งแรก.');
define('JS_LANG_InfoRequiredFields', 'ฟิลด์ที่ต้องระบุ');

define('JS_LANG_ConfirmAreYouSure', 'คุณแน่ใจหรือไม่?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'ข้อความที่เลือก (รายการ) จะถูกลบอย่างถาวร! คุณแน่ใจหรือไม่?');
define('JS_LANG_ConfirmSaveSettings', 'การตั้งค่าไม่ถูกบันทึก เลือกตกลงเพื่อบันทึก');
define('JS_LANG_ConfirmSaveContactsSettings', 'รายชื่อไม่ได้บันทึกการตั้งค่า เลือกตกลงเพื่อบันทึก');
define('JS_LANG_ConfirmSaveAcctProp', 'บัญชีคุณสมบัติไม่ถูกบันทึก เลือกตกลงเพื่อบันทึก');
define('JS_LANG_ConfirmSaveFilter', 'การกรองไม่ได้บันทึกคุณสมบัติ เลือกตกลงเพื่อบันทึก');
define('JS_LANG_ConfirmSaveSignature', 'ลายเซ็นที่ไม่ได้จัดเก็บ เลือกตกลงเพื่อบันทึก');
define('JS_LANG_ConfirmSavefolders', 'โฟลเดอร์ที่ไม่ได้บันทึก เลือกตกลงเพื่อบันทึก');
define('JS_LANG_ConfirmHtmlToPlain', 'คำเตือน: เมื่อเปลี่ยนการจัดรูปแบบของข้อความนี้จาก HTML เพื่อข้อความล้วนคุณจะสูญเสียใดๆปัจจุบันการจัดรูปแบบในข้อความ เลือกตกลงเพื่อดำเนินการต่อ');
define('JS_LANG_ConfirmAddFolder', 'ก่อนที่จะเพิ่ม / ลบโฟลเดอร์นั้นจำเป็นต้องใช้การเปลี่ยนแปลง เลือกตกลงเพื่อบันทึก');
define('JS_LANG_ConfirmEmptySubject', 'หัวเรื่องคือฟิลด์ว่างเปล่า คุณต้องการดำเนินการต่อ?');

define('JS_LANG_WarningEmailBlank', 'คุณไม่สามารถออก "อีเมล" ฟิลด์ว่าง');
define('JS_LANG_WarningLoginBlank', 'คุณไม่สามารถออก "การเข้าสู่ระบบ" ฟิลด์ว่าง');
define('JS_LANG_WarningToBlank', 'คุณไม่สามารถปล่อยให้ "เป็น" ฟิลด์ว่างเปล่า');
define('JS_LANG_WarningServerPortBlank', 'คุณไม่สามารถปล่อย POP3 และ เซิร์ฟเวอร์ SMTP / พอร์ตฟิลด์ว่างเปล่า');
define('JS_LANG_WarningEmptySearchLine', 'ค้นหาบรรทัดว่าง โปรดป้อน substring ที่คุณจำเป็นต้องพบ');
define('JS_LANG_WarningMarkListItem', 'โปรดทำเครื่องหมายอย่างน้อยหนึ่งรายการในรายการ');
define('JS_LANG_WarningFolderMove', 'โฟลเดอร์ไม่สามารถย้ายเพราะนี่คืออีกระดับ');
define('JS_LANG_WarningContactNotComplete', 'โปรดป้อนอีเมลหรือชื่อ');
define('JS_LANG_WarningGroupNotComplete', 'โปรดป้อนชื่อกลุ่ม');

define('JS_LANG_WarningEmailFieldBlank', 'คุณไม่สามารถปล่อย "อีเมล" ฟิลด์ว่างเปล่า');
define('JS_LANG_WarningIncServerBlank', 'คุณไม่สามารถปล่อย POP3 (IMAP4) เซิร์ฟเวอร์ฟิลด์ว่าง');
define('JS_LANG_WarningIncPortBlank', 'คุณไม่สามารถปล่อย POP3 (IMAP4) เซิร์ฟเวอร์พอร์ตฟิลด์ว่างเปล่า');
define('JS_LANG_WarningIncLoginBlank', 'คุณไม่สามารถปล่อยให้ POP3 (IMAP4) ล็อกอินฟิลด์ว่าง');
define('JS_LANG_WarningIncPortNumber', 'คุณควรระบุเลขบวกใน POP3 (IMAP4) พอร์ตฟิลด์');
define('JS_LANG_DefaultIncPortNumber', 'เริ่มต้น POP3 (IMAP4) หมายเลขพอร์ตคือ 110 (143)');
define('JS_LANG_WarningIncPassBlank', 'คุณไม่สามารถปล่อย POP3 (IMAP4) ฟิลด์รหัสผ่านว่างเปล่า');
define('JS_LANG_WarningOutPortBlank', 'คุณไม่สามารถออกจากเซิร์ฟเวอร์ SMTP พอร์ตฟิลด์ว่าง');
define('JS_LANG_WarningOutPortNumber', 'คุณควรระบุเลขบวกใน SMTP พอร์ตฟิลด์');
define('JS_LANG_WarningCorrectEmail', 'คุณควรระบุอีเมลที่ถูกต้อง');
define('JS_LANG_DefaultOutPortNumber', 'ค่าเริ่มต้น SMTP หมายเลขพอร์ตคือ 25');

define('JS_LANG_WarningCsvExtention', 'ไฟล์ส่วนขยายควร Csv');
define('JS_LANG_WarningImportFileType', 'โปรดเลือกแอปพลิเคชันที่คุณต้องการคัดลอกจากรายชื่อของคุณ');
define('JS_LANG_WarningEmptyImportFile', 'โปรดเลือกไฟล์โดยคลิกที่ปุ่มเบราส์');

define('JS_LANG_WarningContactsPerPage', 'รายชื่อต่อค่าหน้าเป็นเลขบวก');
define('JS_LANG_WarningMessagesPerPage', 'ข้อความต่อหน้าค่าเป็นเลขบวก');
define('JS_LANG_WarningMailsOnServerDays', 'คุณควรระบุเลขบวกในข้อความบนเซิร์ฟเวอร์วันฟิลด์');
define('JS_LANG_WarningEmptyFilter', 'โปรดป้อนค่า');
define('JS_LANG_WarningEmptyFolderName', 'โปรดป้อนชื่อโฟลเดอร์');

define('JS_LANG_ErrorConnectionFailed', 'การเชื่อมต่อไม่สำเร็จ');
define('JS_LANG_ErrorRequestFailed', 'ข้อมูลที่ไม่สามารถเสร็จ');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'ที่ออบเจกต์ XMLHttpRequest คือขาด');
define('JS_LANG_ErrorWithoutDesc', 'โดยรายละเอียดข้อผิดพลาดเกิดขึ้น');
define('JS_LANG_ErrorParsing', 'เกิดข้อผิดพลาดขณะแยกวิเคราะห์ที่ XML ');
define('JS_LANG_ResponseText', 'การตอบกลับข้อความ');
define('JS_LANG_ErrorEmptyXmlPacket', 'ที่ว่างแพ็คเกจ XML ');
define('JS_LANG_ErrorImportContacts', 'เกิดข้อผิดพลาดขณะนำเข้าที่ติดต่อ');
define('JS_LANG_ErrorNoContacts', 'ไม่มีรายชื่อที่พบสำหรับการนำเข้า');
define('JS_LANG_ErrorCheckMail', 'รับข้อความยกเลิกเนื่องจากข้อผิดพลาด อาจไม่ทั้งหมดข้อความที่ได้รับ');

define('JS_LANG_LoggingToServer', 'ล็อกในเซิร์ฟเวอร์ &hellip;');
define('JS_LANG_GettingMsgsNum', 'การเดินทางจำนวนข้อความ');
define('JS_LANG_RetrievingMessage', 'ดึงข้อความ');
define('JS_LANG_DeletingMessage', 'ลบข้อความ');
define('JS_LANG_DeletingMessages', 'ลบข้อความ (รายการ)');
define('JS_LANG_Of', 'จาก');
define('JS_LANG_Connection', 'การเชื่อมต่อ');
define('JS_LANG_Charset', 'ตั้งกลุ่ม');
define('JS_LANG_AutoSelect', 'เลือกอัตโนมัติ');

define('JS_LANG_Contacts', 'รายชื่อ');
define('JS_LANG_ClassicVersion', 'เวอร์ชั่นคลาสสิค');
define('JS_LANG_Logout', 'ยกเลิกการสมัคร');
define('JS_LANG_Settings', 'การตั้งค่า');

define('JS_LANG_LookFor', 'มองหา:');
define('JS_LANG_SearchIn', 'ค้นหาใน:');
define('JS_LANG_QuickSearch', 'ค้นหา "จาก" "ในการ" และ "หัวเรื่อง" ฟิลด์เท่านั้น (เร็ว)');
define('JS_LANG_SlowSearch', 'ค้นหาข้อความทั้งหมด');
define('JS_LANG_AllMailFolders', 'จดหมายทั้งหมดโฟลเดอร์');
define('JS_LANG_AllGroups', 'ทุกกลุ่ม');

define('JS_LANG_NewMessage', 'ข้อความใหม่');
define('JS_LANG_CheckMail', 'ตรวจสอบอีเมล์');
define('JS_LANG_EmptyTrash', 'ถังขยะว่างเปล่า');
define('JS_LANG_MarkAsRead', 'ทำเครื่องหมายว่าอ่านแล้ว');
define('JS_LANG_MarkAsUnread', 'ทำเครื่องหมายว่ายังไม่ได้อ่าน');
define('JS_LANG_MarkFlag', 'ทำเครื่องหมาย');
define('JS_LANG_MarkUnflag', 'ไม่ทำเครื่องหมาย');
define('JS_LANG_MarkAllRead', 'ทำเครื่องหมายรายการทั้งหมดอ่าน');
define('JS_LANG_MarkAllUnread', 'ทำเครื่องหมายรายการทั้งหมดยังไม่ได้อ่าน');
define('JS_LANG_Reply', 'ตอบ');
define('JS_LANG_ReplyAll', 'ตอบกลับทั้งหมด');
define('JS_LANG_Delete', 'ลบ');
define('JS_LANG_Undelete', 'ยกเลิกการลบ');
define('JS_LANG_PurgeDeleted', 'ลบซินดิเคท');
define('JS_LANG_MoveToFolder', 'ย้ายไปที่');
define('JS_LANG_Forward', 'ฟอร์เวิร์ด');

define('JS_LANG_HideFolders', 'ซ่อนโฟลเดอร์');
define('JS_LANG_ShowFolders', 'แสดงโฟลเดอร์');
define('JS_LANG_ManageFolders', 'จัดการโฟลเดอร์');
define('JS_LANG_SyncFolder', 'ตรงโฟลเดอร์');
define('JS_LANG_NewMessages', 'ข้อความใหม่');
define('JS_LANG_Messages', 'ข้อความ (รายการ)');

define('JS_LANG_From', 'จาก');
define('JS_LANG_To', 'ถึง');
define('JS_LANG_Date', 'วันที่');
define('JS_LANG_Size', 'ขนาด');
define('JS_LANG_Subject', 'เรื่อง');

define('JS_LANG_FirstPage', 'หน้าแรก');
define('JS_LANG_PreviousPage', 'หน้าก่อนหน้านี้');
define('JS_LANG_NextPage', 'หน้าถัดไป');
define('JS_LANG_LastPage', 'หน้าสุดท้าย');

define('JS_LANG_SwitchToPlain', 'สลับไปที่ดูข้อความล้วน');
define('JS_LANG_SwitchToHTML', 'สลับไปที่มุมมอง HTML');
define('JS_LANG_AddToAddressBook', 'เพิ่มไปยังรายชื่อ');
define('JS_LANG_ClickToDownload', 'คลิกที่นี่เพื่อดาวน์โหลด');
define('JS_LANG_View', 'ดู');
define('JS_LANG_ShowFullHeaders', 'แสดงเต็มหัวเรื่อง');
define('JS_LANG_HideFullHeaders', 'ซ่อนหัวเรื่องเต็ม');

define('JS_LANG_MessagesInFolder', 'ข้อความ (รายการ) ในโฟลเดอร์');
define('JS_LANG_YouUsing', 'คุณกำลังใช้');
define('JS_LANG_OfYour', 'ของคุณ');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'ส่ง');
define('JS_LANG_SaveMessage', 'บันทึก');
define('JS_LANG_Print', 'ปริ้น');
define('JS_LANG_PreviousMsg', 'ก่อนหน้าข้อความ');
define('JS_LANG_NextMsg', 'ข้อความถัดไป');
define('JS_LANG_AddressBook', 'สมุดที่อยู่');
define('JS_LANG_ShowBCC', 'แสดงสำเนาลับ');
define('JS_LANG_HideBCC', 'ซ่อนสำเนาลับ');
define('JS_LANG_CC', 'ก๊อบปี็');
define('JS_LANG_BCC', 'สำเนาลับ');
define('JS_LANG_ReplyTo', 'ตอบกลับ');
define('JS_LANG_AttachFile', 'แนบไฟล์');
define('JS_LANG_Attach', 'แนบ');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'ข้อความต้นฉบับ');
define('JS_LANG_Sent', 'ส่ง');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'ต่ำ');
define('JS_LANG_Normal', 'ธรรมดา');
define('JS_LANG_High', 'สูง');
define('JS_LANG_Importance', 'ความสําคัญ');
define('JS_LANG_Close', 'ปิด');

define('JS_LANG_Common', 'สามัญ');
define('JS_LANG_EmailAccounts', 'บัญชีอีเมล');

define('JS_LANG_MsgsPerPage', 'ข้อความต่อหน้า');
define('JS_LANG_DisableRTE', 'สมบูรณ์ข้อความบรรณาธิการเพื่อปิดการใช้งาน');
define('JS_LANG_Skin', 'หนัง');
define('JS_LANG_DefCharset', 'เริ่มต้นตั้งกลุ่ม');
define('JS_LANG_DefCharsetInc', 'มาตรฐานความลึกตั้งกลุ่ม');
define('JS_LANG_DefCharsetOut', 'มาตรฐานออกตั้งกลุ่ม');
define('JS_LANG_DefTimeOffset', 'เวลามาตรฐานชดเชย');
define('JS_LANG_DefLanguage', 'ภาษาเริ่มต้น');
define('JS_LANG_DefDateFormat', 'รูปแบบวันที่เริ่มต้น');
define('JS_LANG_ShowViewPane', 'รายการข้อความด้วยบานหน้าต่างแสดงตัวอย่าง');
define('JS_LANG_Save', 'บันทึก');
define('JS_LANG_Cancel', 'ยกเลิก');
define('JS_LANG_OK', 'ตกลง');

define('JS_LANG_Remove', 'นำออกไป');
define('JS_LANG_AddNewAccount', 'เพิ่มบัญชีใหม่');
define('JS_LANG_Signature', 'ลายเซ็น');
define('JS_LANG_Filters', 'ตัวกรอง');
define('JS_LANG_Properties', 'คุณสมบัติ');
define('JS_LANG_UseForLogin', 'ใช้การตั้งค่าบัญชีเหล่านี้ (และรหัสผ่าน) สำหรับการเข้าสู่ระบบ');
define('JS_LANG_MailFriendlyName', 'ชื่อของคุณ');
define('JS_LANG_MailEmail', 'อีเมล์');
define('JS_LANG_MailIncHost', 'อีเมล์');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'พอร์ต');
define('JS_LANG_MailIncLogin', 'ล็อกอิน');
define('JS_LANG_MailIncPass', 'รหัสผ่าน');
define('JS_LANG_MailOutHost', 'อีเมลขาออก');
define('JS_LANG_MailOutPort', 'พอร์ต');
define('JS_LANG_MailOutLogin', 'SMTP ล็อกอิน');
define('JS_LANG_MailOutPass', 'SMTP รหัสผ่าน');
define('JS_LANG_MailOutAuth1', 'ใช้การตรวจสอบ SMTP');
define('JS_LANG_MailOutAuth2', '(คุณอาจปล่อย SMTP ล็อกอิน / รหัสผ่านในช่องว่างถ้าพวกเขากำลังเหมือนกับ POP3/IMAP4 ล็อกอิน / รหัสผ่าน)');
define('JS_LANG_UseFriendlyNm1', 'ใช้ชื่อส่ง "จาก:" ฟิลด์');
define('JS_LANG_UseFriendlyNm2', '(ชื่อของคุณ <sender@mail.com>)');
define('JS_LANG_GetmailAtLogin', 'รับ / ตรงกันเมล์ที่เข้าสู่ระบบ');
define('JS_LANG_MailMode0', 'ลบได้รับข้อความจากเซิร์ฟเวอร์');
define('JS_LANG_MailMode1', 'เก็บจดหมายไว้บนเซิร์ฟเวอร์');
define('JS_LANG_MailMode2', 'เก็บจดหมายไว้บนเซิร์ฟเวอร์สำหรับ');
define('JS_LANG_MailsOnServerDays', 'วัน');
define('JS_LANG_MailMode3', 'ลบข้อความออกจากเซิร์ฟเวอร์เมลถ้าคุณอยู่ในถังขยะจะโฟลเดอร์');
define('JS_LANG_InboxSyncType', 'ประเภทของซิงค์กล่องจดหมาย');

define('JS_LANG_SyncTypeNo', 'ไม่ตรงกัน');
define('JS_LANG_SyncTypeNewHeaders', 'หัวเรื่องใหม่');
define('JS_LANG_SyncTypeAllHeaders', 'ทุกส่วนหัว');
define('JS_LANG_SyncTypeNewMessages', 'ข้อความใหม่');
define('JS_LANG_SyncTypeAllMessages', 'ข้อความทั้งหมด');
define('JS_LANG_SyncTypeDirectMode', 'โหมดโดยตรง');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'เฉพาะหัวเรื่อง');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'ทั้งข้อความ');
define('JS_LANG_Pop3SyncTypeDirectMode', 'โหมดโดยตรง');

define('JS_LANG_DeleteFromDb', 'ข้อความที่ลบจากฐานข้อมูลเมล์เซิร์ฟเวอร์ถ้าไม่มีอยู่');

define('JS_LANG_EditFilter', 'แก้ไขตัวกรอง');
define('JS_LANG_NewFilter', 'เพิ่มตัวกรองใหม่');
define('JS_LANG_Field', 'สนาม');
define('JS_LANG_Condition', 'เงื่อนไข');
define('JS_LANG_ContainSubstring', 'ประกอบด้วยคำ');
define('JS_LANG_ContainExactPhrase', 'แน่นอนคำ / ประโยค');
define('JS_LANG_NotContainSubstring', 'ไม่มีส่วนชุด');
define('JS_LANG_FilterDesc_At', 'ถึง');
define('JS_LANG_FilterDesc_Field', 'สนาม');
define('JS_LANG_Action', 'ปฏิบัติการ');
define('JS_LANG_DoNothing', 'ไม่ดำเนินการใด');
define('JS_LANG_DeleteFromServer', 'ลบจากเซิร์ฟเวอร์ทันที');
define('JS_LANG_MarkGrey', 'มาร์คเทา');
define('JS_LANG_Add', 'เพิ่ม');
define('JS_LANG_OtherFilterSettings', 'กรองการตั้งค่าอื่นๆ');
define('JS_LANG_ConsiderXSpam', 'พิจารณาเอ็กซ์-สแปมหัวเรื่อง');
define('JS_LANG_Apply', 'นำมาใช้');

define('JS_LANG_InsertLink', 'แทรกลิงก์');
define('JS_LANG_RemoveLink', 'ลบลิงก์');
define('JS_LANG_Numbering', 'หมายเลข');
define('JS_LANG_Bullets', 'เครื่องหมาย');
define('JS_LANG_HorizontalLine', 'เส้นแนวนอน');
define('JS_LANG_Bold', 'ตัวหนา');
define('JS_LANG_Italic', 'ตัวเอียง');
define('JS_LANG_Underline', 'ขีดเส้นใต้');
define('JS_LANG_AlignLeft', 'จัดชิดซ้าย');
define('JS_LANG_Center', 'ศูนย์กลาง');
define('JS_LANG_AlignRight', 'ถูก');
define('JS_LANG_Justify', 'ถูกต้อง');
define('JS_LANG_FontColor', 'สีแบบอักษร');
define('JS_LANG_Background', 'พื้นหลัง');
define('JS_LANG_SwitchToPlainMode', 'เปลี่ยนข้อความเฉพาะในโหมด');
define('JS_LANG_SwitchToHTMLMode', 'สลับไปที่โหมด HTML');

define('JS_LANG_Folder', 'โฟลเดอร์');
define('JS_LANG_Msgs', 'ข่าว');
define('JS_LANG_Synchronize', 'ตรงกัน');
define('JS_LANG_ShowThisFolder', 'โฟลเดอร์นี้ดู');
define('JS_LANG_Total', 'รวม');
define('JS_LANG_DeleteSelected', 'ลบที่เลือก');
define('JS_LANG_AddNewFolder', 'เพิ่มโฟลเดอร์ใหม่');
define('JS_LANG_NewFolder', 'โฟลเดอร์ใหม่');
define('JS_LANG_ParentFolder', 'ผู้ปกครองโฟลเดอร์');
define('JS_LANG_NoParent', 'ไม่มีผู้ปกครอง');
define('JS_LANG_FolderName', 'ชื่อโฟลเดอร์');

define('JS_LANG_ContactsPerPage', 'รายชื่อต่อหน้า');
define('JS_LANG_WhiteList', 'สมุดที่อยู่เป็นรายการขาว');

define('JS_LANG_CharsetDefault', 'ค่าเริ่มต้น');
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

define('JS_LANG_TimeDefault', 'ค่าเริ่มต้น');
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

define('JS_LANG_DateDefault', 'ค่าเริ่มต้น');
define('JS_LANG_DateDDMMYY', 'วัน/เดือน/ปี');
define('JS_LANG_DateMMDDYY', 'เดือน/วัน/ปี');
define('JS_LANG_DateDDMonth', 'วัน เดือน (ม.ค. 01)');
define('JS_LANG_DateAdvanced', 'ขั้นสูง');

define('JS_LANG_NewContact', 'ที่ติดต่อใหม่');
define('JS_LANG_NewGroup', 'กลุ่มใหม่');
define('JS_LANG_AddContactsTo', 'เพิ่มไปยังรายชื่อ');
define('JS_LANG_ImportContacts', 'นำเข้าที่อยู่ติดต่อ');

define('JS_LANG_Name', 'ชื่อ');
define('JS_LANG_Email', 'อีเมล์');
define('JS_LANG_DefaultEmail', 'อีเมลมาตรฐาน');
define('JS_LANG_NotSpecifiedYet', 'ไม่ได้กำหนด');
define('JS_LANG_ContactName', 'ชื่อ');
define('JS_LANG_Birthday', 'วันเกิด');
define('JS_LANG_Month', 'เดือน');
define('JS_LANG_January', 'มกราคม');
define('JS_LANG_February', 'กุมภาพันธ์');
define('JS_LANG_March', 'มีนาคม');
define('JS_LANG_April', 'เมษายน');
define('JS_LANG_May', 'พฤษภาคม');
define('JS_LANG_June', 'มิถุนายน');
define('JS_LANG_July', 'กรกฎาคม');
define('JS_LANG_August', 'สิงหาคม');
define('JS_LANG_September', 'กันยายน');
define('JS_LANG_October', 'ตุลาคม');
define('JS_LANG_November', 'พฤศจิกายน');
define('JS_LANG_December', 'ธันวาคม');
define('JS_LANG_Day', 'วัน');
define('JS_LANG_Year', 'ปี');
define('JS_LANG_UseFriendlyName1', 'ชื่อใช้ส่ง');
define('JS_LANG_UseFriendlyName2', '(เช่นนาย John Doe <johndoe@mail.com>)');
define('JS_LANG_Personal', 'ส่วนบุคคล');
define('JS_LANG_PersonalEmail', 'อีเมลส่วนบุคคล');
define('JS_LANG_StreetAddress', 'ที่อยู่');
define('JS_LANG_City', 'เมือง');
define('JS_LANG_Fax', 'โทรสาร');
define('JS_LANG_StateProvince', 'รัฐ / จังหวัด');
define('JS_LANG_Phone', 'โทรศัพท์');
define('JS_LANG_ZipCode', 'รหัสไปรษณีย์');
define('JS_LANG_Mobile', 'มือถือ');
define('JS_LANG_CountryRegion', 'ประเทศ / ภูมิภาค');
define('JS_LANG_WebPage', 'เว็บไซต์');
define('JS_LANG_Go', 'ไป');
define('JS_LANG_Home', 'บ้าน');
define('JS_LANG_Business', 'ธุรกิจ');
define('JS_LANG_BusinessEmail', 'อีเมลธุรกิจ');
define('JS_LANG_Company', 'บริษัท');
define('JS_LANG_JobTitle', 'ฟังก์ชัน');
define('JS_LANG_Department', 'แผนก');
define('JS_LANG_Office', 'สำนักงาน');
define('JS_LANG_Pager', 'เพจเจอร์');
define('JS_LANG_Other', 'อื่นๆ');
define('JS_LANG_OtherEmail', 'อีเมลอื่น');
define('JS_LANG_Notes', 'หมายเหตุ');
define('JS_LANG_Groups', 'กลุ่ม');
define('JS_LANG_ShowAddFields', 'แสดงเพิ่มเติมฟิลด์');
define('JS_LANG_HideAddFields', 'ซ่อนเพิ่มเติมฟิลด์');
define('JS_LANG_EditContact', 'แก้ไขข้อมูลที่ติดต่อ');
define('JS_LANG_GroupName', 'ชื่อกลุ่ม');
define('JS_LANG_AddContacts', 'เพิ่มรายชื่อ');
define('JS_LANG_CommentAddContacts', '(คุณมีมากกว่าหนึ่งที่อยู่โปรดติดต่อเกาะแกะที่คั่นด้วยจุลภาค)');
define('JS_LANG_CreateGroup', 'สร้างกลุ่ม');
define('JS_LANG_Rename', 'เปลี่ยนชื่อ');
define('JS_LANG_MailGroup', 'อีเมลกลุ่ม');
define('JS_LANG_RemoveFromGroup', 'ลบจากกลุ่ม');
define('JS_LANG_UseImportTo', ' การใช้รายชื่อของคุณที่จะนำเข้าจากโปรแกรม Microsoft Outlook, ของ Microsoft Outlook Express เพื่อการนำเข้าเว็บเมล');
define('JS_LANG_Outlook1', 'โปรแกรม Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'ไมโครซอฟท์ที่ Outlook Express 6');
define('JS_LANG_SelectImportFile', 'ไฮไลท์แฟ้ม ( รูปแบบ CSV ) ที่คุณต้องการนำเข้า.');
define('JS_LANG_Import', 'นำเข้า');
define('JS_LANG_ContactsMessage', 'นี่คือรายชื่อหน้า!');
define('JS_LANG_ContactsCount', 'ติดต่อ');
define('JS_LANG_GroupsCount', 'กลุ่ม');

// webmail 4.1 constants
define('PicturesBlocked', 'รูปภาพในข้อความนี้มีการบล็อคเพื่อความปลอดภัย');
define('ShowPictures', 'ดูรูปภาพ');
define('ShowPicturesFromSender', 'รูปภาพในข้อความจากผู้ส่งเสมอนี้แสดง');
define('AlwaysShowPictures', 'รูปภาพในข้อความแสดงเสมอ');

define('TreatAsOrganization', 'ในฐานะองค์กรที่มีการซื้อขาย');

define('WarningGroupAlreadyExist', 'กลุ่มที่มีชื่อดังกล่าวอยู่แล้ว โปรดระบุชื่ออื่น');
define('WarningCorrectFolderName', 'คุณควรระบุชื่อโฟลเดอร์ที่ถูกต้อง');
define('WarningLoginFieldBlank', 'คุณไม่สามารถปล่อย "เข้าสู่ระบบ" ฟิลด์ว่าง');
define('WarningCorrectLogin', 'คุณควรระบุถูกต้องล็อกอิน');
define('WarningPassBlank', 'คุณไม่สามารถออกจาก "รหัสผ่าน" ฟิลด์ว่าง');
define('WarningCorrectIncServer', 'คุณควรระบุถูกต้อง POP3 (IMAP) ที่อยู่เซิร์ฟเวอร์');
define('WarningCorrectSMTPServer', 'คุณควรระบุที่อยู่อีเมลขาออกถูกต้อง');
define('WarningFromBlank', 'คุณไม่สามารถปล่อย "จาก" ฟิลด์ว่าง');
define('WarningAdvancedDateFormat', 'โปรดระบุวันที่เวลารูปแบบ');

define('AdvancedDateHelpTitle', 'วันที่ขั้นสูง');
define('AdvancedDateHelpIntro', 'เมื่อ "ขั้นสูง" กล่องตรวจสอบคุณสามารถคลิกช่องข้อความของคุณเพื่อป้อนรูปแบบวันที่ที่แล้ว Aftermarket เว็บเมล์ผู้เชี่ยวชาญจะปรากฏ ต่อไปนี้อาจมีตัวเลือก \':\' หรือ \'/\' ตัวอักษรเพื่อประโยค:');
define('AdvancedDateHelpConclusion', 'ตัวอย่างเช่นถ้าคุณได้ระบุไว้ "เดือน / วัน / ปี" ค่าในช่องข้อความของ "ขั้นสูง" ฟิลด์วันที่จะแสดงผลเป็นเดือน / วัน / ปี (เช่น 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'วันของเดือน (1 ถึง 31)');
define('AdvancedDateHelpNumericMonth', 'เดือน (1 ถึง 12)');
define('AdvancedDateHelpTextualMonth', 'เดือน (ม.ค. ถึง ธ.ค.)');
define('AdvancedDateHelpYear2', 'ปี, 2 หลัก');
define('AdvancedDateHelpYear4', 'ปี, 4 หลัก');
define('AdvancedDateHelpDayOfYear', 'วันของปี (1 ถึง 366)');
define('AdvancedDateHelpQuarter', 'ไตรมาส');
define('AdvancedDateHelpDayOfWeek', 'วันของสัปดาห์ (จันทร์ถึงอาทิตย์)');
define('AdvancedDateHelpWeekOfYear', 'สัปดาห์ของปี (1 ถึง 53)');

define('InfoNoMessagesFound', 'ไม่พบจดหมาย');
define('ErrorSMTPConnect', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ SMTP ตรวจสอบการตั้งค่าเซิร์ฟเวอร์ SMTP');
define('ErrorSMTPAuth', 'ผิดชื่อผู้ใช้  หรือรหัสผ่าน การตรวจสอบล้มเหลว');
define('ReportMessageSent', 'ข้อความของคุณถูกส่ง');
define('ReportMessageSaved', 'ข้อความของคุณได้รับการบันทึก');
define('ErrorPOP3Connect', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ POP3, POP3 เซิร์ฟเวอร์ตรวจสอบการตั้งค่า');
define('ErrorIMAP4Connect', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ IMAP4, IMAP4 เซิร์ฟเวอร์ตรวจสอบการตั้งค่า');
define('ErrorPOP3IMAP4Auth', 'ผิดอีเมล / การเข้าสู่ระบบ / หรือรหัสผ่าน การตรวจสอบล้มเหลว');
define('ErrorGetMailLimit', 'ขออภัยขนาดจำกัดของกล่องจดหมายของคุณจะเกิน');

define('ReportSettingsUpdatedSuccessfuly', 'การตั้งค่าได้รับการอัปเดทเสร็จสมบูรณ์');
define('ReportAccountCreatedSuccessfuly', 'บัญชีถูกสร้างขึ้นสำเร็จ');
define('ReportAccountUpdatedSuccessfuly', 'บัญชีที่ได้รับการอัปเดทเสร็จสมบูรณ์');
define('ConfirmDeleteAccount', 'คุณแน่ใจหรือไม่ว่าต้องการลบบัญชี?');
define('ReportFiltersUpdatedSuccessfuly', 'ตัวกรองได้รับการอัปเดทเสร็จสมบูรณ์');
define('ReportSignatureUpdatedSuccessfuly', 'ลายเซ็นได้รับการอัปเดทเสร็จสมบูรณ์');
define('ReportFoldersUpdatedSuccessfuly', 'โฟลเดอร์ที่มีการอัปเดทเสร็จสมบูรณ์');
define('ReportContactsSettingsUpdatedSuccessfuly', 'รายชื่อ \'การตั้งค่าได้รับการอัปเดทเสร็จสมบูรณ์');

define('ErrorInvalidCSV', 'ไฟล์ CSV ที่คุณเลือกมีรูปแบบไม่ถูกต้อง');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'กลุ่ม');
define('ReportGroupSuccessfulyAdded2', 'สำเร็จเพิ่ม');
define('ReportGroupUpdatedSuccessfuly', 'กลุ่มที่ได้รับการอัปเดทเสร็จสมบูรณ์');
define('ReportContactSuccessfulyAdded', 'ติดต่อสำเร็จ');
define('ReportContactUpdatedSuccessfuly', 'การติดต่อได้รับการอัปเดทเสร็จสมบูรณ์');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', ' ติดต่อ (รายการ) ถูกเพิ่มเข้าในกลุ่ม');
define('AlertNoContactsGroupsSelected', 'ไม่มีที่อยู่ติดต่อหรือกลุ่มเลือก');

define('InfoListNotContainAddress', 'หากไม่มีรายการที่อยู่ที่คุณกำลังมองหา ให้พิมพ์ตัวอักษรแรก');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'โหมดโดยตรง ข้อความเข้าเว็บเมลโดยตรงบนเซิร์ฟเวอร์เมล');

define('FolderInbox', 'จดหมายเข้า');
define('FolderSentItems', 'ส่งรายการ');
define('FolderDrafts', 'ร่างจดหมาย');
define('FolderTrash', 'ถังขยะ');

define('FileLargerAttachment', 'ขนาดไฟล์ของเอกสารแนบเกินขนาดจำกัด');
define('FilePartiallyUploaded', 'สำหรับข้อผิดพลาดที่ไม่รู้จักเป็นเพียงส่วนหนึ่งของไฟล์ที่อัปโหลด');
define('NoFileUploaded', 'ไม่มีการอัปโหลดไฟล์');
define('MissingTempFolder', 'โฟลเดอร์ชั่วคราวหายไป');
define('MissingTempFile', 'ไฟล์ชั่วคราวหายไป');
define('UnknownUploadError', 'อัปโหลดไฟล์ที่ไม่รู้จักเกิดข้อผิดพลาด');
define('FileLargerThan', 'ข้อผิดพลาดการอัปโหลดไฟล์ อาจแฟ้มมีขนาดใหญ่กว่า');
define('PROC_CANT_LOAD_DB', 'ไม่สามารถเชื่อมต่อกับฐานข้อมูล');
define('PROC_CANT_LOAD_LANG', 'ไม่พบแฟ้มภาษาที่ต้องการ');
define('PROC_CANT_LOAD_ACCT', 'บัญชีที่ไม่มีอยู่หรือถูกลบ');

define('DomainDosntExist', 'โดเมนนี้ไม่อยู่ในเซิร์ฟเวอร์เมล');
define('ServerIsDisable', 'ห้ามเข้าถึงโดยผู้ดูแลระบบ / บล็อค');

define('PROC_ACCOUNT_EXISTS', 'บัญชีไม่สามารถสร้างได้เนื่องจากมีอยู่แล้ว');
define('PROC_CANT_GET_MESSAGES_COUNT', 'ไม่สามารถรับข้อความนับโฟลเดอร์');
define('PROC_CANT_MAIL_SIZE', 'ไม่สามารถรับเมลที่จัดเก็บขนาด');

define('Organization', 'การจัดระเบียบ');
define('WarningOutServerBlank', 'คุณไม่สามารถปล่อย "อีเมลขาออก" ฟิลด์ว่าง');

//
define('JS_LANG_Refresh', 'เติมพลัง');
define('JS_LANG_MessagesInInbox', 'ข้อความ ในกล่องจดหมาย');
define('JS_LANG_InfoEmptyInbox', 'กล่องจดหมายว่างเปล่า');

// webmail 4.2 constants
define('BackToList', 'กลับไปที่รายการ');
define('InfoNoContactsGroups', 'ไม่มีที่อยู่ติดต่อหรือกลุ่ม');
define('InfoNewContactsGroups', 'คุณสามารถสร้างที่ติดต่อใหม่ / กลุ่มหรือนำเข้าที่อยู่ติดต่อจาก ไฟล์ CSV ของ Outlook ในรูปแบบของ MS..');
define('DefTimeFormat', 'รูปแบบเวลามาตรฐาน');
define('SpellNoSuggestions', 'ฃไม่มีคำแนะนำ');
define('SpellWait', 'โปรดรอสักครู่ &hellip;');

define('InfoNoMessageSelected', 'ไม่มีข้อความที่เลือก');
define('InfoSingleDoubleClick', 'คุณสามารถคลิกครั้งเดียวเพื่อดูข้อความจากจดหมายใดๆในรายการเพื่อดูตัวอย่างได้ที่นี่หรือดับเบิลคลิกเพื่อดูขนาดเต็ม');

// calendar
define('TitleDay', 'ดูวัน');
define('TitleWeek', 'มุมมองแบบสัปดาห์');
define('TitleMonth', 'ดูเดือน');

define('ErrorNotSupportBrowser', ' AfterLogic ปฏิทินไม่สนับสนุนเบราเซอร์ของคุณ. กรุณาใช้ของ Firefox 2.0 หรือสูงกว่า, โอเปร่า 9.0 หรือสูงกว่า, Internet Explorer เวอร์ชั่น 6.0 หรือสูงกว่า, ซาฟารี 3.0.2 หรือสูงกว่า.');
define('ErrorTurnedOffActiveX', 'สนับสนุน ActiveX ที่ถูกปิด คุณควรหันบนเพื่อที่จะใช้โปรแกรมนี้');

define('Calendar', 'ปฏิทิน');

define('TabDay', 'วัน');
define('TabWeek', 'สัปดาห์');
define('TabMonth', 'เดือน');

define('ToolNewEvent', 'กิจกรรมใหม่');
define('ToolBack', 'กลับ');
define('ToolToday', 'วันนี้');
define('AltNewEvent', 'กิจกรรมใหม่');
define('AltBack', 'กลับ');
define('AltToday', 'วันนี้');
define('CalendarHeader', 'ปฏิทิน');
define('CalendarsManager', 'ปฏิทินผู้จัดการ');

define('CalendarActionNew', 'ปฏิทินใหม่');
define('EventHeaderNew', 'กิจกรรมใหม่');
define('CalendarHeaderNew', 'ปฏิทินใหม่');

define('EventSubject', 'สาขาวิชา');
define('EventCalendar', 'ปฏิทิน');
define('EventFrom', 'จาก');
define('EventTill', 'ถึง');
define('CalendarDescription', 'คำอธิบาย');
define('CalendarColor', 'สี');
define('CalendarName', 'ชื่อปฏิทิน');
define('CalendarDefaultName', 'ปฏิทินของฉัน');

define('ButtonSave', 'บันทึก');
define('ButtonCancel', 'ยกเลิก');
define('ButtonDelete', 'ลบ');

define('AltPrevMonth', 'เดือนก่อนหน้า');
define('AltNextMonth', 'เดือนหน้า');

define('CalendarHeaderEdit', 'แก้ไขปฏิทิน');
define('CalendarActionEdit', 'แก้ไขปฏิทิน');
define('ConfirmDeleteCalendar', 'แน่ใจหรือไม่ว่าคุณต้องการลบปฏิทิน');
define('InfoDeleting', 'กำลังลบ&hellip;');
define('WarningCalendarNameBlank', 'คุณสามารถดูปฏิทินชื่อไม่ว่างเปล่า');
define('ErrorCalendarNotCreated', 'ปฏิทินไม่ได้สร้าง');
define('WarningSubjectBlank', 'หัวเรื่องต้องไม่ว่างเปล่า');
define('WarningIncorrectTime', 'ที่ระบุเวลาประกอบด้วยอักขระไม่ถูกต้อง');
define('WarningIncorrectFromTime', 'จากเวลาที่ไม่ถูกต้อง');
define('WarningIncorrectTillTime', 'จนกระทั่งเวลาที่ไม่ถูกต้อง');
define('WarningStartEndDate', 'เป้าหมายวันที่จะต้องเท่ากับหรือมากกว่าการวันที่เริ่มต้น');
define('WarningStartEndTime', 'ที่เวลาสิ้นสุดต้องมากกว่าเวลาเริ่ม');
define('WarningIncorrectDate', 'วันที่จะต้องถูกต้อง');
define('InfoLoading', 'กำลังโหลด&hellip;');
define('EventCreate', 'แต่งตั้ง');
define('CalendarHideOther', 'ซ่อนปฏิทินอื่นๆ');
define('CalendarShowOther', 'ดูปฏิทินอื่นๆ');
define('CalendarRemove', 'ลบปฏิทิน');
define('EventHeaderEdit', 'วันที่แก้ไข');

define('InfoSaving', 'กำลังบันทึก&hellip;');
define('SettingsDisplayName', 'แสดงชื่อ');
define('SettingsTimeFormat', 'รูปแบบเวลา');
define('SettingsDateFormat', 'รูปแบบวัน');
define('SettingsShowWeekends', 'แสดงสุดสัปดาห์');
define('SettingsWorkdayStarts', 'วันธรรมดา');
define('SettingsWorkdayEnds', 'จบ');
define('SettingsShowWorkday', 'แสดงวันทำการ');
define('SettingsWeekStartsOn', 'สัปดาห์ที่เริ่มต้น');
define('SettingsDefaultTab', 'มาตรฐานแท็บ');
define('SettingsCountry', 'ประเทศ');
define('SettingsTimeZone', 'โซนเวลา');
define('SettingsAllTimeZones', 'ทุกโซนเวลา');

define('WarningWorkdayStartsEnds', 'ที่ \'ธุรกิจสิ้น\' ต้องมากกว่า \'เริ่มทำงาน\'');
define('ReportSettingsUpdated', 'การตั้งค่าได้สำเร็จปรับปรุง');

define('SettingsTabCalendar', 'ปฏิทิน');

define('FullMonthJanuary', 'มกราคม');
define('FullMonthFebruary', 'กุมภาพันธ์');
define('FullMonthMarch', 'มีนาคม');
define('FullMonthApril', 'เมษายน');
define('FullMonthMay', 'พฤษภาคม');
define('FullMonthJune', 'มิถุนายน');
define('FullMonthJuly', 'กรกฎาคม');
define('FullMonthAugust', 'สิงหาคม');
define('FullMonthSeptember', 'กันยายน');
define('FullMonthOctober', 'ตุลาคม');
define('FullMonthNovember', 'พฤษจิกายน');
define('FullMonthDecember', 'ธันวาคม');

define('ShortMonthJanuary', 'ม.ค');
define('ShortMonthFebruary', 'ก.พ');
define('ShortMonthMarch', 'มี.ค');
define('ShortMonthApril', 'เม.ย');
define('ShortMonthMay', 'พ.ค');
define('ShortMonthJune', 'มิ.ย');
define('ShortMonthJuly', 'ก.ค');
define('ShortMonthAugust', 'ส.ค');
define('ShortMonthSeptember', 'ก.ย');
define('ShortMonthOctober', 'ต.ค');
define('ShortMonthNovember', 'พ.ย');
define('ShortMonthDecember', 'ธ.ค');

define('FullDayMonday', 'วันจันทร์');
define('FullDayTuesday', 'วันอังคาร');
define('FullDayWednesday', 'วันพุธ');
define('FullDayThursday', 'วันพฤหัสบดี');
define('FullDayFriday', 'วันศุกร์');
define('FullDaySaturday', 'วันเสาร์');
define('FullDaySunday', 'วันอาทิตย์');

define('DayToolMonday', 'จ.');
define('DayToolTuesday', 'อัง.');
define('DayToolWednesday', 'พ.');
define('DayToolThursday', 'พฤ.');
define('DayToolFriday', 'ศ.');
define('DayToolSaturday', 'ส.');
define('DayToolSunday', 'อา.');

define('CalendarTableDayMonday', 'จ.');
define('CalendarTableDayTuesday', 'อ.');
define('CalendarTableDayWednesday', 'พ.');
define('CalendarTableDayThursday', 'พ.');
define('CalendarTableDayFriday', 'ศ.');
define('CalendarTableDaySaturday', 'ส.');
define('CalendarTableDaySunday', 'อ.');

define('ErrorParseJSON', 'ที่ JSON ส่งคืนการตอบสนองจากเซิร์ฟเวอร์ไม่สามารถแยกวิเคราะห์');

define('ErrorLoadCalendar', 'ไม่สามารถโหลดปฏิทิน');
define('ErrorLoadEvents', 'ไม่สามารถโหลดกิจกรรม');
define('ErrorUpdateEvent', 'ไม่สามารถบันทึกกิจกรรม');
define('ErrorDeleteEvent', 'ไม่สามารถ][กิจกรรม');
define('ErrorUpdateCalendar', 'ไม่สามารถบันทึกปฏิทิน');
define('ErrorDeleteCalendar', 'ไม่สามารถลบปฏิทิน');
define('ErrorGeneral', 'เกิดข้อผิดพลาดในเซิร์ฟเวอร์ ลองอีกครั้งในภายหลัง');

// webmail 4.3 constants
define('SharedTitleEmail', 'อีเมล');
define('ShareHeaderEdit', 'ปฏิทินหุ้นและเผยแพร่');
define('ShareActionEdit', 'ปฏิทินหุ้นและเผยแพร่');
define('CalendarPublicate', 'ทำให้ประชาชนเว็บเข้าถึงปฏิทินนี้');
define('CalendarPublicationLink', 'เชื่อมโยง');
define('ShareCalendar', 'ใช้ปฏิทินนี้ร่วมกัน');
define('SharePermission1', 'สามารถทำการเปลี่ยนแปลงและจัดการการใช้งานร่วมกัน');
define('SharePermission2', 'สามารถทำการเปลี่ยนแปลงเหตุการณ์');
define('SharePermission3', 'สามารถดูรายละเอียดกิจกรรมทั้งหมด');
define('SharePermission4', 'สามารถดูเฉพาะว่าง / ไม่ว่าง (ซ่อนรายละเอียด)');
define('ButtonClose', 'ปิด');
define('WarningEmailFieldFilling', 'คุณควรกรอกอีเมลฟิลด์แรก');
define('EventHeaderView', 'ดูกิจกรรม');
define('ErrorUpdateSharing', 'ไม่สามารถบันทึกข้อมูลร่วมกันและสิ่งพิมพ์');
define('ErrorUpdateSharing1', 'ไม่สามารถที่จะเปิดเผยให้ผู้ใช้ไปยัง %s ตามที่ไม่มี');
define('ErrorUpdateSharing2', 'เป็นไปไม่ได้ที่จะแบ่งปันปฏิทินนี้ให้ผู้ใช้ที่ %s ');
define('ErrorUpdateSharing3', 'ปฏิทินนี้ผู้ใช้ที่ใช้ร่วมกัน %s ');
define('Title_MyCalendars', 'ปฏิทินของฉัน');
define('Title_SharedCalendars', 'ปฏิทินที่ใช้ร่วมกัน');
define('ErrorGetPublicationHash', 'ไม่สามารถสร้างสิ่งพิมพ์ลิงค์');
define('ErrorGetSharing', 'ไม่สามารถเพิ่มการใช้งานร่วมกัน');
define('CalendarPublishedTitle', 'ปฏิทินนี้มีการเผยแพร่');
define('RefreshSharedCalendars', 'รีเฟรชปฏิทินที่ใช้ร่วมกัน');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'สมาชิก');

define('ReportMessagePartDisplayed', 'จดที่เพียงส่วนหนึ่งของข้อความจะปรากฏ');
define('ReportViewEntireMessage', 'ในการดูข้อความทั้งหมด,');
define('ReportClickHere', 'คลิกที่นี่');
define('ErrorContactExists', 'ที่ติดต่อกับชื่อและอีเมลที่มีอยู่แล้ว');

define('Attachments', 'สิ่งที่แนบ');

define('InfoGroupsOfContact', 'กลุ่มที่ติดต่อเป็นสมาชิกของมีเครื่องหมายตรวจสอบเครื่องหมาย');
define('AlertNoContactsSelected', 'ไม่มีที่ติดต่อที่ถูกเลือก');
define('MailSelected', 'เลือกที่อยู่เมล');
define('CaptionSubscribed', 'สมัครเป็นสมาชิก');

define('OperationSpam', 'สแปม');
define('OperationNotSpam', 'ไม่มีสแปม');
define('FolderSpam', 'สแปม');

// webmail 4.4 contacts
define('ContactMail', 'อีเมล์ติดต่อ');
define('ContactViewAllMails', 'ดูทั้งหมดอีเมล์ที่ติดต่อ');
define('ContactsMailThem', 'เมล์พวกเขา');
define('DateToday', 'วันนี้');
define('DateYesterday', 'เมื่อวาน');
define('MessageShowDetails', 'แสดงรายละเอียด');
define('MessageHideDetails', 'ซ่อนรายละเอียด');
define('MessageNoSubject', 'ไม่มีหัวเรื่อง');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'ถึง');
define('SearchClear', 'ลบการค้นหา');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'ผลลัพธ์การค้นหาสำหรับ "#s" ใน #f โฟลเดอร์:');
define('SearchResultsInAllFolders', 'ผลลัพธ์การค้นหาสำหรับ "#s" ในโฟลเดอร์จดหมายทั้งหมด:');
define('AutoresponderTitle', 'ระบบตอบรับอัตโนมัติ');
define('AutoresponderEnable', 'เปิดใช้งานระบบตอบรับอัตโนมัติ');
define('AutoresponderSubject', 'เรื่อง');
define('AutoresponderMessage', 'ข้อความ');
define('ReportAutoresponderUpdatedSuccessfuly', 'ระบบตอบรับอัตโนมัติได้รับการอัปเดทเสร็จสมบูรณ์');
define('FolderQuarantine', 'กักกัน');

//calendar
define('EventRepeats', 'ซ้ำ');
define('NoRepeats', 'ไม่ซ้ำ');
define('DailyRepeats', 'รายวัน');
define('WorkdayRepeats', 'ทุกวันธรรมดาไม่ใช่วันเสาร์-อาทิตย์ (จันทร์. - ศุกร์.)');
define('OddDayRepeats', 'ทุก จ.., พ.. และศ..');
define('EvenDayRepeats', 'ทุกอังคารและพฤหัสบดี');
define('WeeklyRepeats', 'รายสัปดาห์');
define('MonthlyRepeats', 'รายเดือน');
define('YearlyRepeats', 'รายปี');
define('RepeatsEvery', 'ซ้ำแต่ละ');
define('ThisInstance', 'เฉพาะครั้งนี้');
define('AllEvents', 'กิจกรรมทั้งหมดในชุด');
define('AllFollowing', 'ทั้งหมดหลังจากนี้');
define('ConfirmEditRepeatEvent', 'คุณต้องการเปลี่ยนเฉพาะกิจกรรมนี้กิจกรรมทั้งหมดหรือกิจกรรมนี้และกิจกรรมในอนาคตในชุด?');
define('RepeatEventHeaderEdit', 'แก้ไขกิจกรรมที่เกิดซ้ำ');
define('First', 'ที่หนึ่ง');
define('Second', 'ที่สอง');
define('Third', 'ที่สาม');
define('Fourth', 'ที่สี่');
define('Last', 'หลัง');
define('Every', 'ทุกที่');
define('SetRepeatEventEnd', 'กำหนดวันที่สิ้นสุด');
define('NoEndRepeatEvent', 'ไม่มีวันที่สิ้นสุด');
define('EndRepeatEventAfter', 'หลังจากที่สิ้นสุด');
define('Occurrences', 'ที่ปรากฏ');
define('EndRepeatEventBy', 'สิ้นสุดโดย');
define('EventCommonDataTab', 'หลักรายละเอียด');
define('EventRepeatDataTab', 'รายละเอียดการเกิดซ้ำ');
define('RepeatEventNotPartOfASeries', 'กิจกรรมนี้มีการเปลี่ยนแปลงและไม่มีส่วนหนึ่งของชุด');
define('UndoRepeatExclusion', 'ยกเลิกการเปลี่ยนแปลงเพื่อรวมไว้ในชุด');

define('MonthMoreLink', '%d อีก...');
define('NoNewSharedCalendars', 'ไม่มีปฏิทินใหม่');
define('NNewSharedCalendars', '%d พบปฏิทินใหม่');
define('OneNewSharedCalendars', 'พบปฏิทินใหม่ 1');
define('ConfirmUndoOneRepeat', 'คุณต้องการเรียกคืนกิจกรรมนี้ในชุด?');

define('RepeatEveryDayInfin', 'ทุกวัน');
define('RepeatEveryDayTimes', 'ทุกวัน, %TIMES% เวลา');
define('RepeatEveryDayUntil', 'ทุกวัน, ถึง %UNTIL%');
define('RepeatDaysInfin', 'ทุก %PERIOD% วัน');
define('RepeatDaysTimes', 'ทุก %PERIOD% วัน, %TIMES% เวลา');
define('RepeatDaysUntil', 'ทุก %PERIOD% วัน, ถึง %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'ทุกสัปดาห์ในวันธรรมดา');
define('RepeatEveryWeekWeekdaysTimes', 'ทุกสัปดาห์ในวันธรรมดา, %TIMES% เวลา');
define('RepeatEveryWeekWeekdaysUntil', 'ทุกสัปดาห์ในวันธรรมดา, ถึง %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'ทุก %PERIOD% สัปดาห์ในวันธรรมดา');
define('RepeatWeeksWeekdaysTimes', 'ทุก %PERIOD% สัปดาห์ในวันธรรมดา, %TIMES% เวลา');
define('RepeatWeeksWeekdaysUntil', 'ทุก %PERIOD% สัปดาห์ในวันธรรมดา, ถึง %UNTIL%');

define('RepeatEveryWeekInfin', 'ในทุกสัปดาห์ %DAYS%');
define('RepeatEveryWeekTimes', 'ในทุกสัปดาห์ %DAYS%, %TIMES% เวลา');
define('RepeatEveryWeekUntil', 'ในทุกสัปดาห์ %DAYS%, ถึง %UNTIL%');
define('RepeatWeeksInfin', 'ทุกสัปดาห์ %PERIOD% ในสัปดาห์ %DAYS%');
define('RepeatWeeksTimes', 'ทุกสัปดาห์ %PERIOD% ในสัปดาห์ %DAYS%, %TIMES% เวลา');
define('RepeatWeeksUntil', 'ทุกสัปดาห์ %PERIOD% ในสัปดาห์  %DAYS%, ถึง %UNTIL%');

define('RepeatEveryMonthDateInfin', 'ทุกเดือนในวันที่ %DATE%');
define('RepeatEveryMonthDateTimes', 'ทุกเดือนในวันที่ %DATE%, %TIMES% เวลา');
define('RepeatEveryMonthDateUntil', 'ทุกเดือนในวันที่ %DATE%, ถึง %UNTIL%');
define('RepeatMonthsDateInfin', 'ทุก %PERIOD% เดือนในวันที่ %DATE%');
define('RepeatMonthsDateTimes', 'ทุก %PERIOD% เดือนในวันที่ %DATE%, %TIMES% เวลา');
define('RepeatMonthsDateUntil', 'ทุก %PERIOD% เดือนในวันที่ %DATE%, ถึง %UNTIL%');

define('RepeatEveryMonthWDInfin', 'ในทุกเดือน %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'ในทุกเดือน %NUMBER% %DAY%, %TIMES% เวลา');
define('RepeatEveryMonthWDUntil', 'ในทุกเดือน %NUMBER% %DAY%, ถึง %UNTIL%');
define('RepeatMonthsWDInfin', 'ทุก %PERIOD% ในเดือน %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'ทุก %PERIOD% ในเดือน %NUMBER% %DAY%, %TIMES% เวลา');
define('RepeatMonthsWDUntil', 'ทุก %PERIOD% ในเดือน %NUMBER% %DAY%, ถึง %UNTIL%');

define('RepeatEveryYearDateInfin', 'ทุกๆปีในวันที่ %DATE%');
define('RepeatEveryYearDateTimes', 'ทุกๆปีในวันที่ %DATE%, %TIMES% เวลา');
define('RepeatEveryYearDateUntil', 'ทุกๆปีในวันที่ %DATE%, ถึง %UNTIL%');
define('RepeatYearsDateInfin', 'ทุกๆ %PERIOD% ปีในวันที่ %DATE%');
define('RepeatYearsDateTimes', 'ทุกๆ %PERIOD% ปีในวันที่ %DATE%, %TIMES% เวลา');
define('RepeatYearsDateUntil', 'ทุกๆ %PERIOD% ปีในวันที่ %DATE%, ถึง %UNTIL%');

define('RepeatEveryYearWDInfin', 'ในทุกๆปี %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'ในทุกๆปี %NUMBER% %DAY%, %TIMES% เวลา');
define('RepeatEveryYearWDUntil', 'ในทุกๆปี %NUMBER% %DAY%, ถึง %UNTIL%');
define('RepeatYearsWDInfin', 'ทุกๆ %PERIOD% ปี %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'ทุกๆ %PERIOD% ปี %NUMBER% %DAY%, %TIMES% เวลา');
define('RepeatYearsWDUntil', 'ทุกๆ %PERIOD% ปี %NUMBER% %DAY%, ถึง %UNTIL%');

define('RepeatDescDay', 'วัน');
define('RepeatDescWeek', 'สัปดาห์');
define('RepeatDescMonth', 'เดือน');
define('RepeatDescYear', 'ปี');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'โปรดระบุวันที่สิ้นสุดการกำเริบ');
define('WarningWrongUntilDate', 'การเกิดซ้ำวันที่สิ้นสุดต้องอยู่หลังวันที่เริ่มต้นการกำเริบ');

define('OnDays', 'ในวันที่');
define('CancelRecurrence', 'ยกเลิกการกำเริบ');
define('RepeatEvent', 'ทำซ้ำวันที่นี้');

define('Spellcheck', 'ตรวจสอบการสะกดคำ');
define('LoginLanguage', 'ภาษา');
define('LanguageDefault', 'ค่าเริ่มต้น');

// webmail 4.5.x new

define('EmptySpam', 'สแปมว่างเปล่า');
define('Saving', 'กำลังบันทึก…');
define('Sending', 'กำลังส่ง…');
define('LoggingOffFromServer', 'ล็อกเอาต์จากเซิร์ฟเวอร์…');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Can\'t mark message(s) as spam');
    define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Can\'t mark message(s) as non-spam');
define('ExportToICalendar', 'Export to iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Your account is disabled because maximum number of users allowed by license is exceeded. Please contact your system administrator.');
define('RepliedMessageTitle', 'Replied Message');
define('ForwardedMessageTitle', 'Forwarded Message');
define('RepliedForwardedMessageTitle', 'Replied and Forwarded Message');
define('ErrorDomainExist', 'The user cannot be created because corresponding domain doesn\'t exist. You should create the domain first.');

// webmail 4.6.x or 4.7
define('RequestReadConfirmation', 'Reading confirmation');
define('FolderTypeDefault', 'Default');
define('ShowFoldersMapping', 'Let me use another folder as a system folder (e.g. use MyFolder as Sent Items)');
define('ShowFoldersMappingNote', 'For instance, to change Sent Items location from Sent Items to MyFolder, specify "Sent Items" in "Use for" dropdown of "MyFolder".');
define('FolderTypeMapTo', 'Use for');

define('ReminderEmailExplanation', 'This message arrived to your %EMAIL% account because you ordered event notification in your calendar: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Open calendar');

define('AddReminder', 'Remind me about this event');
define('AddReminderBefore', 'Remind me % before this event');
define('AddReminderAnd', 'and % before');
define('AddReminderAlso', 'and also % before');
define('AddMoreReminder', 'More reminders');
define('RemoveAllReminders', 'Remove all reminders');
define('ReminderNone', 'None');
define('ReminderMinutes', 'minutes');
define('ReminderHour', 'hour');
define('ReminderHours', 'hours');
define('ReminderDay', 'day');
define('ReminderDays', 'days');
define('ReminderWeek', 'week');
define('ReminderWeeks', 'weeks');
define('Allday', 'All day');

define('Folders', 'Folders');
define('NoSubject', 'No Subject');
define('SearchResultsFor', 'Search results for');

define('Back', 'Back');
define('Next', 'Next');
define('Prev', 'Prev');

define('MsgList', 'Messages');
define('Use24HTimeFormat', 'Use 24 hour time format');
define('UseCalendars', 'Use calendars');
define('Event', 'Event');
define('CalendarSettingsNullLine', 'No calendars');
define('CalendarEventNullLine', 'No events');
define('ChangeAccount', 'Change account');

define('TitleCalendar', 'Calendar');
define('TitleEvent', 'Event');
define('TitleFolders', 'Folders');
define('TitleConfirmation', 'Confirmation');

define('Yes', 'Yes');
define('No', 'No');

define('EditMessage', 'Edit Message');

define('AccountNewPassword', 'New password');
define('AccountConfirmNewPassword', 'Confirm new password');
define('AccountPasswordsDoNotMatch', 'Passwords do not match.');

define('ContactTitle', 'Title');
define('ContactFirstName', 'First name');
define('ContactSurName', 'Surname');
define('ContactNickName', 'Nickname');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'reload');
define('CaptchaError', 'Captcha text is incorrect.');

define('WarningInputCorrectEmails', 'Please specify correct emails.');
define('WrongEmails', 'Incorrect emails:');

define('ConfirmBodySize1', 'Sorry, but text messages are max.');
define('ConfirmBodySize2', 'characters long. Everything beyond the limit will be truncated. Click "Cancel" if you want to edit the message.');
define('BodySizeCounter', 'Counter');
define('InsertImage', 'Insert Image');
define('ImagePath', 'Image Path');
define('ImageUpload', 'Insert');
define('WarningImageUpload', 'The file being attached is not an image. Please choose an image file.');

define('ConfirmExitFromNewMessage', 'Changes will be lost if you leave the page. Would you like to save draft before leaving the page?');

define('SensivityConfidential', 'Please treat this message as Confidential');
define('SensivityPrivate', 'Please treat this message as Private');
define('SensivityPersonal', 'Please treat this message as Personal');

define('ReturnReceiptTopText', 'The sender of this message has asked to be notified when you receive this message.');
define('ReturnReceiptTopLink', 'Click here to notify the sender.');
define('ReturnReceiptSubject', 'Return Receipt (displayed)');
define('ReturnReceiptMailText1', 'This is a Return Receipt for the mail that you sent to');
define('ReturnReceiptMailText2', 'Note: This Return Receipt only acknowledges that the message was displayed on the recipient\'s computer. There is no guarantee that the recipient has read or understood the message contents.');
define('ReturnReceiptMailText3', 'with subject');

define('SensivityMenu', 'Sensitivity');
define('SensivityNothingMenu', 'Nothing');
define('SensivityConfidentialMenu', 'Confidential');
define('SensivityPrivateMenu', 'Private');
define('SensivityPersonalMenu', 'Personal');

define('ErrorLDAPonnect', 'Can\'t connect to ldap server.');

define('MessageSizeExceedsAccountQuota', 'This message size exceeds your account quota.');
define('MessageCannotSent', 'The message cannot be sent.');
define('MessageCannotSaved', 'The message cannot be saved.');

define('ContactFieldTitle', 'Field');
define('ContactDropDownTO', 'TO');
define('ContactDropDownCC', 'CC');
define('ContactDropDownBCC', 'BCC');

// 4.9
define('NoMoveDelete', 'Message(s) can\'t be moved to Trash. Most likely your message box is full. Should this unmoved message(s) be deleted?');

define('WarningFieldBlank', 'This field cannot be empty.');
define('WarningPassNotMatch', 'Passwords do not match, please check.');
define('PasswordResetTitle', 'Password recovery - step %d');
define('NullUserNameonReset', 'user');
define('IndexResetLink', 'Forgot password?');
define('IndexRegLink', 'Account Registration');

define('RegDomainNotExist', 'Domain does not exist.');
define('RegAnswersIncorrect', 'Answers are incorrect.');
define('RegUnknownAdress', 'Unknown email address.');
define('RegUnrecoverableAccount', 'Password recovery cannot be applied for this email address.');
define('RegAccountExist', 'This address is already used.');
define('RegRegistrationTitle', 'Registration');
define('RegName', 'Name');
define('RegEmail', 'e-mail address');
define('RegEmailDesc', 'For example, myname@domain.com. This information will be used to enter the system.');
define('RegSignMe', 'Remember me');
define('RegSignMeDesc', 'Do not ask for login and password on next login to the system on this PC.');
define('RegPass1', 'Password');
define('RegPass2', 'Repeat password ');
define('RegQuestionDesc', 'Please, provide two secret questions and answers which know only you. In case of password lost you can use these questions in order to recover the password.');
define('RegQuestion1', 'Secret question 1');
define('RegAnswer1', 'Answer 1');
define('RegQuestion2', 'Secret question 2');
define('RegAnswer2', 'Answer 2');
define('RegTimeZone', 'Time zone');
define('RegLang', 'Interface language');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Register');

define('ResetEmail', 'Please provide your email');
define('ResetEmailDesc', 'Provide emails address used for registration.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Send');
define('ResetQuestion1', 'Secret question 1');
define('ResetAnswer1', 'Answer');
define('ResetQuestion2', 'Secret question 2');
define('ResetAnswer2', 'Answer');
define('ResetSubmitStep2', 'Send');

define('ResetTopDesc1Step2', 'Providede email address');
define('ResetTopDesc2Step2', 'Please confirm correctness.');

define('ResetTopDescStep3', 'please specify below new password for your email.');

define('ResetPass1', 'New password');
define('ResetPass2', 'Repeat password');
define('ResetSubmitStep3', 'Send');
define('ResetDescStep4', 'Your password has been changed.');
define('ResetSubmitStep4', 'Return');

define('RegReturnLink', 'Return to login screen');
define('ResetReturnLink', 'Return to login screen');

// Appointments
define('AppointmentAddGuests', 'Add guests');
define('AppointmentRemoveGuests', 'Cancel Meeting');
define('AppointmentListEmails', 'Enter email addresses separated by commas and press Save');
define('AppointmentParticipants', 'Participants');
define('AppointmentRefused', 'Refuse');
define('AppointmentAwaitingResponse', 'Awaiting response');
define('AppointmentInvalidGuestEmail', 'The following guest email addresses are invalid:');
define('AppointmentOwner', 'Owner');

define('AppointmentMsgTitleInvite', 'Invite to event.');
define('AppointmentMsgTitleUpdate', 'Event was modified.');
define('AppointmentMsgTitleCancel', 'Event was cancelled.');
define('AppointmentMsgTitleRefuse', 'Guest %guest% is refuse invitation');
define('AppointmentMoreInfo', 'More info');
define('AppointmentOrganizer', 'Organizer');
define('AppointmentEventInformation', 'Event information');
define('AppointmentEventWhen', 'When');
define('AppointmentEventParticipants', 'Participants');
define('AppointmentEventDescription', 'Description');
define('AppointmentEventWillYou', 'Will you participate');
define('AppointmentAdditionalParameters', 'Additional parameters');
define('AppointmentHaventRespond', 'Not responded yet');
define('AppointmentRespondYes', 'I will participate');
define('AppointmentRespondMaybe', 'Not sure yet');
define('AppointmentRespondNo', 'Will not participate');
define('AppointmentGuestsChangeEvent', 'Guests can change event');

define('AppointmentSubjectAddStart', 'You\'ve received invitation to event ');
define('AppointmentSubjectAddFrom', ' from ');
define('AppointmentSubjectUpdateStart', 'Modification of event ');
define('AppointmentSubjectDeleteStart', 'Cancellation of event ');
define('ErrorAppointmentChangeRespond', 'Unable to change appointment respond');
define('SettingsAutoAddInvitation', 'Add invitations into calendar automatically');
define('ReportEventSaved', 'Your event has been saved');
define('ReportAppointmentSaved', ' and notifications were sent');
define('ErrorAppointmentSend', 'Can\'t send invitations.');
define('AppointmentEventName', 'Name:');

// End appointments

define('ErrorCantUpdateFilters', 'Can\'t update filters');

define('FilterPhrase', 'If there\'s %field header %condition %string then %action');
define('FiltersAdd', 'Add Filter');
define('FiltersCondEqualTo', 'equal to');
define('FiltersCondContainSubstr', 'containing substring');
define('FiltersCondNotContainSubstr', 'not containing substring');
define('FiltersActionDelete', 'delete message');
define('FiltersActionMove', 'move');
define('FiltersActionToFolder', 'to %folder folder');
define('FiltersNo', 'No filters specified yet');

define('ReminderEmailFriendly', 'reminder');
define('ReminderEventBegin', 'starts at: ');

define('FiltersLoading', 'Loading Filters...');
define('ConfirmMessagesPermanentlyDeleted', 'All messages in this folder will be permanently deleted.');

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
define('ReportCalendarSaved', 'Calendar has been saved.');

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
