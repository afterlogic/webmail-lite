<?php
define('PROC_ERROR_ACCT_CREATE', 'Hesap oluşturulurken bir hata meydana geldi');
define('PROC_WRONG_ACCT_PWD', 'Hatalı şifre');
define('PROC_CANT_LOG_NONDEF', 'Tanımlı olmayan hesaba giriş yapılamıyor');
define('PROC_CANT_INS_NEW_FILTER', 'Yeni filtre eklenemedi');
define('PROC_FOLDER_EXIST', 'Klasör adı zaten mevcut');
define('PROC_CANT_CREATE_FLD', 'Klasör oluşturulamadı');
define('PROC_CANT_INS_NEW_GROUP', 'Yeni grup oluşturulamadı');
define('PROC_CANT_INS_NEW_CONT', 'Yeni kişi oluşturulamadı');
define('PROC_CANT_INS_NEW_CONTS', 'Yeni kişi(ler) oluşturulamadı');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Kişi(ler) gruba eklenemedi');
define('PROC_ERROR_ACCT_UPDATE', 'Hesap güncellenirken hata oluştu');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Kişi ayarları güncellenirken hata oluştu');
define('PROC_CANT_GET_SETTINGS', 'Ayarlara ulaşılamıyor');
define('PROC_CANT_UPDATE_ACCT', 'Hesap güncellenemedi');
define('PROC_ERROR_DEL_FLD', 'Klasör(ler) silinirken hata oluştu');
define('PROC_CANT_UPDATE_CONT', 'Kişi güncellenemedi');
define('PROC_CANT_GET_FLDS', 'Klasör listesi getirilemedi');
define('PROC_CANT_GET_MSG_LIST', 'Mesaj listesi getirilemedi');
define('PROC_MSG_HAS_DELETED', 'Bu mesaj, posta sunucusu üzerinden daha önce silinmiş');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Kişi ayarları yüklenemedi');
define('PROC_CANT_LOAD_SIGNATURE', 'Hesap imza bilgisi yüklenemedi');
define('PROC_CANT_GET_CONT_FROM_DB', 'Kişi, veritabanından alınamadı');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Kişi(ler), veritabanından alınamadı');
define('PROC_CANT_DEL_ACCT_BY_ID', 'id bilgisine göre hesap silinemedi');
define('PROC_CANT_DEL_FILTER_BY_ID', 'id bilgisine göre filtre silinemedi');
define('PROC_CANT_DEL_CONT_GROUPS', 'Kişi(ler) ve/veya gruplar silinemedi');
define('PROC_WRONG_ACCT_ACCESS', 'Diğer kullanıcı hesabına izinsiz giriş denemesi belirlendi.');
define('PROC_SESSION_ERROR', 'Önceki oturum, süre aşımı nedeniyle sona erdirilmiştir.');

define('MailBoxIsFull', 'Mesaj kutunuz dolu');
define('WebMailException', 'WebMail istisna durum oluştu');
define('InvalidUid', 'Hatalı Mesaj UID');
define('CantCreateContactGroup', 'Kişi grubu oluşturulamadı');
define('CantCreateUser', 'Kullanıcı oluşturulamadı');
define('CantCreateAccount', 'Hesap oluşturulamadı');
define('SessionIsEmpty', 'Oturum bilgisi boş');
define('FileIsTooBig', 'Dosya çok büyük');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Tüm mesajlar okunmuş olarak işaretlenemedi');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Tüm mesajlar okunmamış olarak işaretlenemedi');
define('PROC_CANT_PURGE_MSGS', 'Mesaj(lar) boşaltılamadı');
define('PROC_CANT_DEL_MSGS', 'Mesaj(lar) silinemedi');
define('PROC_CANT_UNDEL_MSGS', 'Silinmiş mesaj(lar) geri alınamadı');
define('PROC_CANT_MARK_MSGS_READ', 'Mesaj(lar) okunmuş olarak işaretlenemedi');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Mesaj(lar) okunmamış olarak işaretlenemedi');
define('PROC_CANT_SET_MSG_FLAGS', 'Mesaj(lar) işaretlenemedi');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Mesaj işaret(ler)i silinemedi');
define('PROC_CANT_CHANGE_MSG_FLD', 'Mesaj(lar)ın klaörü değiştirilemedi');
define('PROC_CANT_SEND_MSG', 'Mesaj gönderilemedi: ');
define('PROC_CANT_SAVE_MSG', 'Mesaj kaydedilemedi');
define('PROC_CANT_GET_ACCT_LIST', 'Hesap listesine erişilemedi');
define('PROC_CANT_GET_FILTER_LIST', 'Filtre listesine erişilemedi');

define('PROC_CANT_LEAVE_BLANK', '* işaretli alanları boş bırakamazsınız');

define('PROC_CANT_UPD_FLD', 'Klasör güncellenemedi');
define('PROC_CANT_UPD_FILTER', 'Filtre güncellenemedi');

define('ACCT_CANT_ADD_DEF_ACCT', 'Diğer kullanıcı tarafından öntanımlı hesap olarak kullanılan bu hesap eklenemedi.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Bu hesabın durumu, öntanımlı olarak değiştirilemedi.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Yeni hesap oluşturulamadı (IMAP bağlantı hatası)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Son öntanımlı hesap silinemedi');

define('LANG_LoginInfo', 'Giriş Bilgileri');
define('LANG_Email', 'E-posta');
define('LANG_Login', 'Kullanıcı Adı');
define('LANG_Password', 'Şifre');
define('LANG_IncServer', 'Gelen&nbsp;Posta&nbsp;Sunucusu');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'SMTP&nbsp;Sunucu');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'SMTP&nbsp;kimlik&nbsp;doğrulamayı&nbsp;kullan');
define('LANG_SignMe', 'Otomatik girişime izin ver');
define('LANG_Enter', 'Giriş');

// interface strings

define('JS_LANG_TitleLogin', 'Giriş');
define('JS_LANG_TitleMessagesListView', 'Mesaj Listesi');
define('JS_LANG_TitleMessagesList', 'Mesaj Listesi');
define('JS_LANG_TitleViewMessage', 'Mesaj Oku');
define('JS_LANG_TitleNewMessage', 'Yeni Mesaj');
define('JS_LANG_TitleSettings', 'Ayarlar');
define('JS_LANG_TitleContacts', 'Kişiler');

define('JS_LANG_StandardLogin', 'Standart&nbsp;Giriş');
define('JS_LANG_AdvancedLogin', 'Gelişmiş&nbsp;Giriş');

define('JS_LANG_InfoWebMailLoading', 'WebMail yüklenirken bekleyiniz &hellip;');
define('JS_LANG_Loading', 'Yükleniyor &hellip;');
define('JS_LANG_InfoMessagesLoad', 'WebMail mesaj listesini yüklerken bekleyiniz.');
define('JS_LANG_InfoEmptyFolder', 'Bu klasör boş');
define('JS_LANG_InfoPageLoading', 'Sayfa hala yüklenmekte &hellip;');
define('JS_LANG_InfoSendMessage', 'Mesaj gönderildi');
define('JS_LANG_InfoSaveMessage', 'Mesaj kaydedildi');
define('JS_LANG_InfoHaveImported', 'Aktarılan');
define('JS_LANG_InfoNewContacts', 'yeni  kişi, adres defterinize eklenmiştir.');
define('JS_LANG_InfoToDelete', 'Bu, ');
define('JS_LANG_InfoDeleteContent', 'isimli klasörü silmek için, öncelikle klasör içindekileri silmeniz gerekmektedir.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Boş olmayan klasörü silemezsiniz. Klasörleri silmek için, önce içindekileri silmeniz gerekmektedir.');
define('JS_LANG_InfoRequiredFields', '* zorunlu alanlar');

define('JS_LANG_ConfirmAreYouSure', 'Emin misiniz?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Seçili mesaj(lar) KALICI OLARAK silinecektir! Emin misiniz');
define('JS_LANG_ConfirmSaveSettings', 'Ayarlar kaydedilmedi. Kaydetmek için OK butuna basınız.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Kişi ayarları kaydedilmedi. Kaydetmek için OK butuna basınız.');
define('JS_LANG_ConfirmSaveAcctProp', 'Hesap özellikleri kaydedilmedi. Kaydetmek için OK butuna basınız.');
define('JS_LANG_ConfirmSaveFilter', 'Filtre özellikleri kaydedilmedi. Kaydetmek için OK butuna basınız.');
define('JS_LANG_ConfirmSaveSignature', 'İmza kaydedilmedi. Kaydetmek için OK butuna basınız.');
define('JS_LANG_ConfirmSavefolders', 'Klasörler kaydedilmedi. Kaydetmek için OK butuna basınız.');
define('JS_LANG_ConfirmHtmlToPlain', 'Uyarı : Mesaj biçimini HTML\'den düz yazıya değiştirmeniz durumunda, geçerli mesaja ait biçimlendirmeleri kaybedeceksiniz. Devam etmek için OK butonuna basınız.');
define('JS_LANG_ConfirmAddFolder', 'Klasör eklemeden önce değişiklikleri uygulamanız gereklidir. Kaydetmek için OK butuna basınız.');
define('JS_LANG_ConfirmEmptySubject', 'Mesaj konu başlığı boş. Devam etmek ister misiniz?');

define('JS_LANG_WarningEmailBlank', 'E-posta:<br /> boş bırakılamaz');
define('JS_LANG_WarningLoginBlank', 'Kullanıcı Adı: <br /> boş bırakılamaz');
define('JS_LANG_WarningToBlank', 'Kime: alanı boş bırakılamaz');
define('JS_LANG_WarningServerPortBlank', 'POP3 ve SMTP sunucu/port alanları<br /> boş bırakılamaz');
define('JS_LANG_WarningEmptySearchLine', 'Arama alanı boş. Lütfen bulmak istediğiniz kelimeyi giriniz.');
define('JS_LANG_WarningMarkListItem', 'Listede yeralan mesajlardan en az birini seçiniz.');
define('JS_LANG_WarningFolderMove', 'Klasör, farklı bir seviyeye taşınamadı.');
define('JS_LANG_WarningContactNotComplete', 'Lütfen e-posta veya ad giriniz');
define('JS_LANG_WarningGroupNotComplete', 'Lütfen grup adı giriniz');

define('JS_LANG_WarningEmailFieldBlank', 'E-posta alanı boş bırakılamaz');
define('JS_LANG_WarningIncServerBlank', 'POP3(IMAP4) Sunucu alanı boş bırakılamaz');
define('JS_LANG_WarningIncPortBlank', 'POP3(IMAP4) Sunucu port boş bırakılamaz');
define('JS_LANG_WarningIncLoginBlank', 'POP3(IMAP4) Kullanıcı Adı boş bırakılamaz');
define('JS_LANG_WarningIncPortNumber', 'POP3(IMAP4) Port alanı pozitif bir sayı olmalıdır.');
define('JS_LANG_DefaultIncPortNumber', 'Öntanımlı POP3(IMAP4) port numarası, sırasıyla 110(143) şeklindedir.');
define('JS_LANG_WarningIncPassBlank', 'POP3(IMAP4) Şifre alanı boş bırakılamaz');
define('JS_LANG_WarningOutPortBlank', 'SMTP Sunucu Port alanı boş bırakılamaz');
define('JS_LANG_WarningOutPortNumber', 'SMTP Port alanı  pozitif bir sayı olmalıdır.');
define('JS_LANG_WarningCorrectEmail', 'Lütfen geçerli bir e-posta adresi giriniz.');
define('JS_LANG_DefaultOutPortNumber', 'Öntanımlı SMTP portu 25\'dir');

define('JS_LANG_WarningCsvExtention', 'Dosya uzantısı .csv olmalıdır.');
define('JS_LANG_WarningImportFileType', 'Kişileri kopyalamak istediğiniz uygulamayı seçiniz:');
define('JS_LANG_WarningEmptyImportFile', 'Lütfen, Gözat (Browse) butonuna tıklayarak dosyayı seçiniz');

define('JS_LANG_WarningContactsPerPage', 'Sayfa başına kişi sayısı pozitif bir sayı olmalıdır');
define('JS_LANG_WarningMessagesPerPage', 'Sayfa başına mesaj sayısı pozitif bir sayı olmalıdır');
define('JS_LANG_WarningMailsOnServerDays', 'Mesajın sunucuda saklanma süresi gün bazında, pozitif bir sayı olmalıdır.');
define('JS_LANG_WarningEmptyFilter', 'Lütfen kelime giriniz');
define('JS_LANG_WarningEmptyFolderName', 'Lütfen klasör ismi giriniz');

define('JS_LANG_ErrorConnectionFailed', 'Bağlantı başarısız');
define('JS_LANG_ErrorRequestFailed', 'Veri transferi hala tamamlanmadı');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'XMLHttpRequest objesi eksik');
define('JS_LANG_ErrorWithoutDesc', 'Tanımsız bir hata oluştu');
define('JS_LANG_ErrorParsing', 'XML parsing hatası.');
define('JS_LANG_ResponseText', 'Yanıt metni:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Boş XML paketi');
define('JS_LANG_ErrorImportContacts', 'Kişiler aktarılırken hata oluştu');
define('JS_LANG_ErrorNoContacts', 'Aktarılacak kişi bulunamadı');
define('JS_LANG_ErrorCheckMail', 'Hataya bağlı olarak mesaj alımı sona erdirildi. Muhtemelen mesajların tamamı alınmamış olabilir.');

define('JS_LANG_LoggingToServer', 'Sunucuya giriş yapılıyor &hellip;');
define('JS_LANG_GettingMsgsNum', 'Mesaj sayısı alınıyor');
define('JS_LANG_RetrievingMessage', 'Mesajlar alınıyor');
define('JS_LANG_DeletingMessage', 'Mesaj siliniyor');
define('JS_LANG_DeletingMessages', 'Mesaj(lar) siliniyor');
define('JS_LANG_Of', '/');
define('JS_LANG_Connection', 'Bağlantı');
define('JS_LANG_Charset', 'Karakter seti');
define('JS_LANG_AutoSelect', 'Otomatik seçim');

define('JS_LANG_Contacts', 'Kişiler');
define('JS_LANG_ClassicVersion', 'Klasik Sürüm');
define('JS_LANG_Logout', 'Çıkış');
define('JS_LANG_Settings', 'Ayarlar');

define('JS_LANG_LookFor', 'Ara: ');
define('JS_LANG_SearchIn', 'Klasör: ');
define('JS_LANG_QuickSearch', 'Sadece Kimde, Kime ve Konu alanlarında ara (daha hızlı sonuç).');
define('JS_LANG_SlowSearch', 'Mesaj içinde ara');
define('JS_LANG_AllMailFolders', 'Tüm Mesaj Klasörleri');
define('JS_LANG_AllGroups', 'Tüm Gruplar');

define('JS_LANG_NewMessage', 'Yeni Mesaj');
define('JS_LANG_CheckMail', 'E-posta Kontrol Et');
define('JS_LANG_EmptyTrash', 'Silinmiş Öğeleri Boşalt');
define('JS_LANG_MarkAsRead', 'Okundu');
define('JS_LANG_MarkAsUnread', 'Okunmadı');
define('JS_LANG_MarkFlag', 'İşaretle');
define('JS_LANG_MarkUnflag', 'İşareti Kaldır');
define('JS_LANG_MarkAllRead', 'Tümü Okundu');
define('JS_LANG_MarkAllUnread', 'Tümü Okunmadı');
define('JS_LANG_Reply', 'Yanıtla');
define('JS_LANG_ReplyAll', 'Tümünü Yanıtla');
define('JS_LANG_Delete', 'Sil');
define('JS_LANG_Undelete', 'Silinenleri gerial');
define('JS_LANG_PurgeDeleted', 'Silinmişleri temizle');
define('JS_LANG_MoveToFolder', 'Taşı');
define('JS_LANG_Forward', 'İlet');

define('JS_LANG_HideFolders', 'Klasörleri Gizle');
define('JS_LANG_ShowFolders', 'Klasörleri Göster');
define('JS_LANG_ManageFolders', 'Klasör Yönetimi');
define('JS_LANG_SyncFolder', 'Eşleştirilmiş klasör');
define('JS_LANG_NewMessages', 'Yeni Mesajlar');
define('JS_LANG_Messages', 'Mesaj(lar)');

define('JS_LANG_From', 'Kimden');
define('JS_LANG_To', 'Kime');
define('JS_LANG_Date', 'Tarih');
define('JS_LANG_Size', 'Boyut');
define('JS_LANG_Subject', 'Konu');

define('JS_LANG_FirstPage', 'İlk Sayfa');
define('JS_LANG_PreviousPage', 'Önceki Sayfa');
define('JS_LANG_NextPage', 'Sonraki Sayfa');
define('JS_LANG_LastPage', 'Son Sayfa');

define('JS_LANG_SwitchToPlain', 'Düz Yazı Görünüme Geç');
define('JS_LANG_SwitchToHTML', 'HTML Görünüme Geç');
define('JS_LANG_AddToAddressBook', 'Adres Defterine Ekle');
define('JS_LANG_ClickToDownload', 'Yüklemek için tıklayınız ');
define('JS_LANG_View', 'Göster');
define('JS_LANG_ShowFullHeaders', 'Detaylı Başlık Bilgisi Göster');
define('JS_LANG_HideFullHeaders', 'Detaylı Başlık Bilgisini Gizle');

define('JS_LANG_MessagesInFolder', 'adet mesaj bulundu');
define('JS_LANG_YouUsing', 'Posta kutunuzda kullanılan alan');
define('JS_LANG_OfYour', ', toplam alan ');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Gönder');
define('JS_LANG_SaveMessage', 'Kaydet');
define('JS_LANG_Print', 'Yazdır');
define('JS_LANG_PreviousMsg', 'Önceki Mesaj');
define('JS_LANG_NextMsg', 'Sonraki Mesaj');
define('JS_LANG_AddressBook', 'Adres Defteri');
define('JS_LANG_ShowBCC', 'BCC Göster');
define('JS_LANG_HideBCC', 'BCC Gizle');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Yanıtla');
define('JS_LANG_AttachFile', 'Dosya Ekle');
define('JS_LANG_Attach', 'Ekle');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Orijinal Mesaj');
define('JS_LANG_Sent', 'Gönderildi');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Düşük');
define('JS_LANG_Normal', 'Normal');
define('JS_LANG_High', 'Yüksek');
define('JS_LANG_Importance', 'Önem');
define('JS_LANG_Close', 'Kapat');

define('JS_LANG_Common', 'Genel');
define('JS_LANG_EmailAccounts', 'E-posta Hesapları');

define('JS_LANG_MsgsPerPage', 'Sayfa başına mesaj');
define('JS_LANG_DisableRTE', 'Metin editörü pasif');
define('JS_LANG_Skin', 'Şablon');
define('JS_LANG_DefCharset', 'Öntanımlı karatker seti');
define('JS_LANG_DefCharsetInc', 'Öntanımlı gelen karakter seti');
define('JS_LANG_DefCharsetOut', 'Öntanımlı giden karakter seti');
define('JS_LANG_DefTimeOffset', 'Öntanımlı zaman dilimi');
define('JS_LANG_DefLanguage', 'Öntanımlı dil');
define('JS_LANG_DefDateFormat', 'Öntanımlı tarih biçimi');
define('JS_LANG_ShowViewPane', 'Mesaj öngörünümü liste ile birlikte göster');
define('JS_LANG_Save', 'Kaydet');
define('JS_LANG_Cancel', 'İptal');
define('JS_LANG_OK', 'Tamam');

define('JS_LANG_Remove', 'Kaldır');
define('JS_LANG_AddNewAccount', 'Yeni Hesap Ekle');
define('JS_LANG_Signature', 'İmza');
define('JS_LANG_Filters', 'Filtre');
define('JS_LANG_Properties', 'Özellikler');
define('JS_LANG_UseForLogin', 'Giriş için bu hesap özelliklerini (kullanıcı adı ve şifre) kullan');
define('JS_LANG_MailFriendlyName', 'Adınız');
define('JS_LANG_MailEmail', 'E-posta');
define('JS_LANG_MailIncHost', 'Gelen Posta');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Kullanıcı Adı');
define('JS_LANG_MailIncPass', 'Şifre');
define('JS_LANG_MailOutHost', 'SMTP Sunucu');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP Kullanıcı Adı');
define('JS_LANG_MailOutPass', 'SMTP Şifre');
define('JS_LANG_MailOutAuth1', 'SMTP kimlik doğrulamayı kullan');
define('JS_LANG_MailOutAuth2', '(Eğer POP3/IMAP kullanıcı adı/şifre ile aynı ise bu alanı boş bırakabilirsiniz)');
define('JS_LANG_UseFriendlyNm1', '"Kimden:" alanında tam adımı kullan');
define('JS_LANG_UseFriendlyNm2', '(Ceren Berra &lt;ceren@postaci.org&gt;)');
define('JS_LANG_GetmailAtLogin', 'Girişte yeni mesajları al');
define('JS_LANG_MailMode0', 'Alınan mesajları sunucudan sil');
define('JS_LANG_MailMode1', 'Mesajları sunucu üzerinde sakla ');
define('JS_LANG_MailMode2', 'Mesajları sunucuda');
define('JS_LANG_MailsOnServerDays', 'gün sakla');
define('JS_LANG_MailMode3', 'Silinmiş Öğeler kutusundaki mesajlar silindiğinde sunucudaki mesajları da sil ');
define('JS_LANG_InboxSyncType', 'Inbox Eşleştirme Tipi');

define('JS_LANG_SyncTypeNo', 'Eşleştirme');
define('JS_LANG_SyncTypeNewHeaders', 'Yeni Başlıklar');
define('JS_LANG_SyncTypeAllHeaders', 'Tüm Başlıklar');
define('JS_LANG_SyncTypeNewMessages', 'Yeni Mesajlar');
define('JS_LANG_SyncTypeAllMessages', 'Tüm Mesajlar');
define('JS_LANG_SyncTypeDirectMode', 'Direkt Yöntem (mesaj sunucusu üzerinden direkt erişim) ');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Tüm Başlıklar');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Tüm Mesajlar');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Direkt Yöntem (mesaj sunucusu üzerinden direkt erişim) ');

define('JS_LANG_DeleteFromDb', 'Mesaj sunucusu üzerinde yeralmayan mesajları veritabanından sil ');

define('JS_LANG_EditFilter', 'Filtre&nbsp;Düzenle');
define('JS_LANG_NewFilter', 'Yeni Filtre Ekle');
define('JS_LANG_Field', 'Alan');
define('JS_LANG_Condition', 'Şart');
define('JS_LANG_ContainSubstring', 'İçinde geçen');
define('JS_LANG_ContainExactPhrase', 'Aynı yazıldığı gibi');
define('JS_LANG_NotContainSubstring', 'İçinde geçmeyen');
define('JS_LANG_FilterDesc_At', '');
define('JS_LANG_FilterDesc_Field', 'alanında');
define('JS_LANG_Action', 'Etki');
define('JS_LANG_DoNothing', 'Gözardı et');
define('JS_LANG_DeleteFromServer', 'Hemen sunucudan sil');
define('JS_LANG_MarkGrey', 'Gri işaretle');
define('JS_LANG_Add', 'Ekle');
define('JS_LANG_OtherFilterSettings', 'Diğer Filtreleme Ayarları');
define('JS_LANG_ConsiderXSpam', 'X-Spam başlıklarını dikkate al');
define('JS_LANG_Apply', 'Uygula');

define('JS_LANG_InsertLink', 'Link Ekle');
define('JS_LANG_RemoveLink', 'Linki Sil');
define('JS_LANG_Numbering', 'Numaralı Listele');
define('JS_LANG_Bullets', 'Listele');
define('JS_LANG_HorizontalLine', 'Yatay Çizği');
define('JS_LANG_Bold', 'Kalın');
define('JS_LANG_Italic', 'İtalik');
define('JS_LANG_Underline', 'Altı-çizili');
define('JS_LANG_AlignLeft', 'Sola Hizala');
define('JS_LANG_Center', 'Ortala');
define('JS_LANG_AlignRight', 'Sağa Hizala');
define('JS_LANG_Justify', 'Düzeltilmiş');
define('JS_LANG_FontColor', 'Yazı Rengi');
define('JS_LANG_Background', 'Arkaplan');
define('JS_LANG_SwitchToPlainMode', 'Düz Yazı Görünüme Geç ');
define('JS_LANG_SwitchToHTMLMode', 'HTML Görünüme Geç ');

define('JS_LANG_Folder', 'Klasör');
define('JS_LANG_Msgs', 'Mesaj,');
define('JS_LANG_Synchronize', 'Eşleştir');
define('JS_LANG_ShowThisFolder', 'Bu Klasörü Göster');
define('JS_LANG_Total', 'Toplam');
define('JS_LANG_DeleteSelected', 'Seçileni Sil');
define('JS_LANG_AddNewFolder', 'Yeni Klasör Ekle');
define('JS_LANG_NewFolder', 'Yeni Klasör');
define('JS_LANG_ParentFolder', 'Ana Klasör');
define('JS_LANG_NoParent', 'Bağımsız');
define('JS_LANG_FolderName', 'Klasör Adı');

define('JS_LANG_ContactsPerPage', 'Sayfa başına kişi');
define('JS_LANG_WhiteList', 'Adres Defterindeki kişiler beyaz listededir');

define('JS_LANG_CharsetDefault', 'Default');
define('JS_LANG_CharsetArabicAlphabetISO', 'Arapça (ISO)');
define('JS_LANG_CharsetArabicAlphabet', 'Arapça (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', 'Baltık (ISO)');
define('JS_LANG_CharsetBalticAlphabet', 'Baltık (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', 'Orta Avrupa (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', 'Orta Avrupa (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', 'Chinese Simplified (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', 'Chinese Simplified (GB2312)');
define('JS_LANG_CharsetChineseTraditional', 'Geleneksel Çince (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', 'Kril (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', 'Kril (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', 'Kril (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', 'Yunan (ISO)');
define('JS_LANG_CharsetGreekAlphabet', 'Yunan (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', 'İbrani (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', 'İbrani (Windows)');
define('JS_LANG_CharsetJapanese', 'Japon');
define('JS_LANG_CharsetJapaneseShiftJIS', 'Japon (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', 'Kore (EUC)');
define('JS_LANG_CharsetKoreanISO', 'Kore (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', 'Latin 3 (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', 'Türkçe');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Üniversal (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Üniversal (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', 'Vietnam(Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', 'Batı(ISO)');
define('JS_LANG_CharsetWesternAlphabet', 'Batı(Windows)');

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

define('JS_LANG_DateDefault', 'Öntanımlı');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Month (01 Oca)');
define('JS_LANG_DateAdvanced', 'Gelişmiş');

define('JS_LANG_NewContact', 'Yeni Kişi');
define('JS_LANG_NewGroup', 'Yeni Grup');
define('JS_LANG_AddContactsTo', 'Kişi Ekle');
define('JS_LANG_ImportContacts', 'Kişi Aktar');

define('JS_LANG_Name', 'Adı');
define('JS_LANG_Email', 'E-posta');
define('JS_LANG_DefaultEmail', 'Öntanımlı e-posta');
define('JS_LANG_NotSpecifiedYet', 'Henüz tanımlanmadı');
define('JS_LANG_ContactName', 'Adı');
define('JS_LANG_Birthday', 'Doğumgünü');
define('JS_LANG_Month', 'Ay');
define('JS_LANG_January', 'Ocak');
define('JS_LANG_February', 'Şubat');
define('JS_LANG_March', 'Mart');
define('JS_LANG_April', 'Nisan');
define('JS_LANG_May', 'Mayıs');
define('JS_LANG_June', 'Haziran');
define('JS_LANG_July', 'Temmuz');
define('JS_LANG_August', 'Ağustos');
define('JS_LANG_September', 'Eylül');
define('JS_LANG_October', 'Ekim');
define('JS_LANG_November', 'Kasım');
define('JS_LANG_December', 'Aralık');
define('JS_LANG_Day', 'Gün');
define('JS_LANG_Year', 'Yıl');
define('JS_LANG_UseFriendlyName1', 'Tam adı kullan');
define('JS_LANG_UseFriendlyName2', '(örneğin, Ceren Berra  &lt;gonderen@postaci.org&gt;)');
define('JS_LANG_Personal', 'Kişisel');
define('JS_LANG_PersonalEmail', 'Kişisel E-posta');
define('JS_LANG_StreetAddress', 'Cadde');
define('JS_LANG_City', 'Şehir');
define('JS_LANG_Fax', 'Faks');
define('JS_LANG_StateProvince', 'Semt/İlçe');
define('JS_LANG_Phone', 'Telefon');
define('JS_LANG_ZipCode', 'Posta Kodu');
define('JS_LANG_Mobile', 'Mobil');
define('JS_LANG_CountryRegion', 'Ülke/Bölge');
define('JS_LANG_WebPage', 'Web Adresi');
define('JS_LANG_Go', 'Git');
define('JS_LANG_Home', 'Ev');
define('JS_LANG_Business', 'İş');
define('JS_LANG_BusinessEmail', 'İş E-posta');
define('JS_LANG_Company', 'Şirket');
define('JS_LANG_JobTitle', 'Ünvan');
define('JS_LANG_Department', 'Bölüm');
define('JS_LANG_Office', 'Ofis');
define('JS_LANG_Pager', 'Çağrı Cihazı');
define('JS_LANG_Other', 'Diğer');
define('JS_LANG_OtherEmail', 'Diğer E-posta');
define('JS_LANG_Notes', 'Notlar');
define('JS_LANG_Groups', 'Gruplar');
define('JS_LANG_ShowAddFields', 'Ek alanları göster');
define('JS_LANG_HideAddFields', 'Ek alanları gizle');
define('JS_LANG_EditContact', 'Kişi bilgilerini güncelle');
define('JS_LANG_GroupName', 'Grup Adı');
define('JS_LANG_AddContacts', 'Kişi Ekle');
define('JS_LANG_CommentAddContacts', '(Birden fazla adres için lütfen virgül ile ayırınız)');
define('JS_LANG_CreateGroup', 'Yeni Grup ');
define('JS_LANG_Rename', 'yeniden adlandır');
define('JS_LANG_MailGroup', 'Mesaj Grubu');
define('JS_LANG_RemoveFromGroup', 'Gruptan Sil');
define('JS_LANG_UseImportTo', 'Kişi listelerinizi Microsoft Outlook Express ve Microsoft Outlook\'tan, WebMail kişi listenize aktarabilirsiniz.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Aktarmak istediğiniz dosyayı (.CSV biçiminde) seçiniz');
define('JS_LANG_Import', 'Aktar');
define('JS_LANG_ContactsMessage', 'Kişi sayfası!!!');
define('JS_LANG_ContactsCount', 'Kişi(ler)');
define('JS_LANG_GroupsCount', 'grup(lar)');

// webmail 4.1 constants
define('PicturesBlocked', 'Bu mesajdaki resimler, güvenliğiniz için bloklanmıştır.');
define('ShowPictures', 'Resimleri göster');
define('ShowPicturesFromSender', 'Bu göndericiden gelen mesajlardaki resimleri her zaman göster');
define('AlwaysShowPictures', 'Mesajlardaki resimleri her zaman göster');

define('TreatAsOrganization', 'Organizasyon olarak davran');

define('WarningGroupAlreadyExist', 'Benzer isimli grup mevcut. Lütfen bir başka grup adı belirtiniz.');
define('WarningCorrectFolderName', 'Hatasız klasör ismi belirtmelisiniz.');
define('WarningLoginFieldBlank', 'Giriş alanını boş bırakamazsınız.');
define('WarningCorrectLogin', 'Hatasız giriş bilgisi belirtmelisiniz.');
define('WarningPassBlank', 'Şifre alanını boş bırakamazsınız.');
define('WarningCorrectIncServer', 'Doğru POP3 (IMAP) sunucu adresi girmelisiniz.');
define('WarningCorrectSMTPServer', 'Doğru SMTP sunucu adresi girmelisiniz.');
define('WarningFromBlank', 'Kimden alanını boş bırakamazsınız.');
define('WarningAdvancedDateFormat', 'Lütfen tarih-zaman formatı tanımlayınız.');

define('AdvancedDateHelpTitle', 'Gelişmiş Tarih');
define('AdvancedDateHelpIntro', '&quot;Gelişmiş&quot; alanı seçilince, WebMail\'de görünecek kendi tarih formatınızı oluşturabilirsiniz. Ayraç olarak kullanılan seçenekler \':\' veya \'/\' :');
define('AdvancedDateHelpConclusion', 'Örneğin, &quot;Gelişmiş&quot; alanında &quot;mm/dd/yyyy&quot; olarak tanımlamışsanız, tarih bilgisi ay/gün/yıl (11/23/2005 gibi) olarak gözükecektir.');
define('AdvancedDateHelpDayOfMonth', 'Ayın günleri (1 - 31)');
define('AdvancedDateHelpNumericMonth', 'Ay (1 - 12)');
define('AdvancedDateHelpTextualMonth', 'Ay (Oca - Ara)');
define('AdvancedDateHelpYear2', 'Yıl, 2 haneli');
define('AdvancedDateHelpYear4', 'Yıl, 4 haneli');
define('AdvancedDateHelpDayOfYear', 'Yılın Gün (1 - 366)');
define('AdvancedDateHelpQuarter', 'Çeyrek');
define('AdvancedDateHelpDayOfWeek', 'Haftanın günleri (Pzt - Paz)');
define('AdvancedDateHelpWeekOfYear', 'Hafta (1 - 53)');

define('InfoNoMessagesFound', 'Mesaj bulunamadı');
define('ErrorSMTPConnect', 'SMTP sunucusuna bağlanılamadı. SMTP sunucu ayarlarını kontrol ediniz.');
define('ErrorSMTPAuth', 'Hatalı kullanıcı adı ve/veya şifre. Doğrulama başarısız.');
define('ReportMessageSent', 'Mesajınız gönderilmiştir.');
define('ReportMessageSaved', 'Mesajınız kaydedilmiştir.');
define('ErrorPOP3Connect', 'POP3 sunucusuna bağlanılamadı. POP3 sunucu ayarlarını kontrol ediniz.');
define('ErrorIMAP4Connect', 'IMAP sunucusuna bağlanılamadı. IMAP sunucu ayarlarını kontrol ediniz.');
define('ErrorPOP3IMAP4Auth', 'Hatalı eposta/giriş ve/veya şifre. Doğrulama başarısız.');
define('ErrorGetMailLimit', 'Üzgünüm, posta kutunuzun limiti doldu. ');

define('ReportSettingsUpdatedSuccessfuly', 'Ayarlar başarıyla güncellendi.');
define('ReportAccountCreatedSuccessfuly', 'Hesap başarıyla oluşturuldu.');
define('ReportAccountUpdatedSuccessfuly', 'Hesap başarıyla güncellendi.');
define('ConfirmDeleteAccount', 'Hesabı silmek istediğinize emin misiniz?');
define('ReportFiltersUpdatedSuccessfuly', 'Filtreler başarıyla güncellendi.');
define('ReportSignatureUpdatedSuccessfuly', 'İmza başarıyla güncellendi.');
define('ReportFoldersUpdatedSuccessfuly', 'Klasörler başarıyla güncellendi.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Kişi ayarları başarıyla güncellendi.');

define('ErrorInvalidCSV', 'Seçilen CSV dosyası geçersiz formattadır.');
//"guies" isimli grup başarıyla eklendi.
define('ReportGroupSuccessfulyAdded1', '');
define('ReportGroupSuccessfulyAdded2', 'isimli grup başarıyla eklendi.');
define('ReportGroupUpdatedSuccessfuly', 'Grup başarıyla güncellendi.');
define('ReportContactSuccessfulyAdded', 'Kişi başarıyla eklendi.');
define('ReportContactUpdatedSuccessfuly', 'Kişi başarıyla güncellendi.');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Kişi(ler) takip eden gruba eklendi ');
define('AlertNoContactsGroupsSelected', 'Kişi veya grup seçilmedi');

define('InfoListNotContainAddress', 'Aradığınız adresi listede bulamıyorsanız, adresin ilk karakterini yazınız.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Direkt yöntem. WebMail, mesajlara sunucu üzerinden direkt erişir.');

define('FolderInbox', 'Gelen kutusu');
define('FolderSentItems', 'Gönderilmiş Öğeler');
define('FolderDrafts', 'Taslaklar');
define('FolderTrash', 'Çöp');

define('FileLargerAttachment', 'Dosya boyutu, ek dosya boyutu sınırlarını aşmıştır. ');
define('FilePartiallyUploaded', 'Bilinmeyen bir hata nedeniyle, dosyanın sadece bazı parçaları yüklendi.');
define('NoFileUploaded', 'Dosya yüklenemedi.');
define('MissingTempFolder', 'Geçici klasör kayıp.');
define('MissingTempFile', 'Geçici dosya kayıp.');
define('UnknownUploadError', 'Bilinmeyen bir yükleme hatası oluştu.');
define('FileLargerThan', 'Dosya yükleme hatası. Yüksek olasılıkla; dosya, izin verilenden büyük ');
define('PROC_CANT_LOAD_DB', 'Veritabanına bağlanamadı.');
define('PROC_CANT_LOAD_LANG', 'Gerekli dil dosyası bulunamadı.');
define('PROC_CANT_LOAD_ACCT', 'Hesap bulunamadı, muhtemelen daha önce silinmiştir.');

define('DomainDosntExist', 'Böyle bir domain posta sunucusu üzerinde bulunamadı.');
define('ServerIsDisable', 'Posta sunucusu kullanımı, yönetici tarafından engellenmiştir.');

define('PROC_ACCOUNT_EXISTS', 'Hesap oluşturulamadı, çünkü zaten mevcut.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Klasördeki mesaj sayısı alınamadı.');
define('PROC_CANT_MAIL_SIZE', 'Posta saklama boyut bilgisi alınamadı.');

define('Organization', 'Organizasyon');
define('WarningOutServerBlank', 'SMTP sunucu alanı boş bırakılamaz.');

//
define('JS_LANG_Refresh', 'Yenile');
define('JS_LANG_MessagesInInbox', 'Gelen kutusundaki mesajlar');
define('JS_LANG_InfoEmptyInbox', 'Gelen kutusu boş');

// webmail 4.2 constants
define('BackToList', 'Listeye Dön');
define('InfoNoContactsGroups', 'Kişi veya Grup Yok.');
define('InfoNewContactsGroups', 'MS Outlook biçimine uygun yeni kişiler/gruplar oluşturabilir veya .CSV dosyasını içeri aktarabilirsiniz.');
define('DefTimeFormat', 'Öntanımlı zaman biçimi');
define('SpellNoSuggestions', 'Öneri yok');
define('SpellWait', 'Lütfen bekleyiniz&hellip;');

define('InfoNoMessageSelected', 'Mesaj seçilmedi.');
define('InfoSingleDoubleClick', 'Herhangi bir mesajın üzerine tek tıklarsanız öngörünüm biçiminde, çift tıklarsanız tam boyutta açabilirsiniz.');

// calendar
define('TitleDay', 'Günlük');
define('TitleWeek', 'Haftalık');
define('TitleMonth', 'Aylık');

define('ErrorNotSupportBrowser', 'AfterLogic Calendar, kullanmakta olduğunuz internet gezginini (browser) desteklemiyor. Lütfen, FireFox 2.0 veya daha üstü, Opera 9.0 veya daha üstü, Internet Explorer 6.0 veya daha üstü, Safari 3.0.2 veya daha üstü sürümleri kullanınız..');
define('ErrorTurnedOffActiveX', 'ActiveX desteği kapalı. <br/>Bu uygulamayı kullanabilmeniz için ActiveX desteğini açmanız gerekir.');

define('Calendar', 'Takvim');

define('TabDay', 'Gün');
define('TabWeek', 'Hafta');
define('TabMonth', 'Ay');

define('ToolNewEvent', 'Yeni&nbsp;Olay');
define('ToolBack', 'Geri');
define('ToolToday', 'Bugün');
define('AltNewEvent', 'Yeni Olay');
define('AltBack', 'Geri');
define('AltToday', 'Bugün');
define('CalendarHeader', 'Takvim');
define('CalendarsManager', 'Takvim Yöneticisi');

define('CalendarActionNew', 'Yeni takvim');
define('EventHeaderNew', 'Yeni Olay');
define('CalendarHeaderNew', 'Yeni Takvim');

define('EventSubject', 'Konu');
define('EventCalendar', 'Takvim');
define('EventFrom', 'Zaman aralığı');
define('EventTill', '-');
define('CalendarDescription', 'Açıklama');
define('CalendarColor', 'Renk');
define('CalendarName', 'Takvim Adı');
define('CalendarDefaultName', 'Benim Takvimim');

define('ButtonSave', 'Kaydet');
define('ButtonCancel', 'İptal');
define('ButtonDelete', 'Sil');

define('AltPrevMonth', 'Önceki Ay');
define('AltNextMonth', 'Sonraki Ay');

define('CalendarHeaderEdit', 'Takvim Düzenle');
define('CalendarActionEdit', 'Takvim Düzenle');
define('ConfirmDeleteCalendar', 'Takvimi silmek istediğinize emin misiniz');
define('InfoDeleting', 'Siliniyor&hellip;');
define('WarningCalendarNameBlank', 'Takvim adını boş bırakamazsınız.');
define('ErrorCalendarNotCreated', 'Takvim oluşturulmadı.');
define('WarningSubjectBlank', 'Konu alanını boş bırakamazsınız.');
define('WarningIncorrectTime', 'Tanımlı zaman bilgisi geçersiz karakterler içermektedir.');
define('WarningIncorrectFromTime', 'Zaman aralığı bilgisi hatalı.');
define('WarningIncorrectTillTime', 'Zaman aralığı bilgisi hatalı.');
define('WarningStartEndDate', 'Bitiş tarihi, başlangıç tarihine eşit ya da büyük olmalıdır.');
define('WarningStartEndTime', 'Bitiş saati, başlangıç saatinden büyük olmalıdır.');
define('WarningIncorrectDate', 'Geçerli bir tarih giriniz.');
define('InfoLoading', 'Yükleniyor&hellip;');
define('EventCreate', 'Olay oluştur');
define('CalendarHideOther', 'Diğer takvimleri gizle');
define('CalendarShowOther', 'Diğer takvimleri göster');
define('CalendarRemove', 'Takvim Sil');
define('EventHeaderEdit', 'Olay Düzenle');

define('InfoSaving', 'Kadediliyor&hellip;');
define('SettingsDisplayName', 'Display Name');
define('SettingsTimeFormat', 'Saat biçimi');
define('SettingsDateFormat', 'Tarih biçimi');
define('SettingsShowWeekends', 'Haftasonlarını göster');
define('SettingsWorkdayStarts', 'İş başlangıcı');
define('SettingsWorkdayEnds', 'bitişi');
define('SettingsShowWorkday', 'İşgünü göster');
define('SettingsWeekStartsOn', 'Hafta başlangıcı');
define('SettingsDefaultTab', 'Öntanımlı seçim');
define('SettingsCountry', 'Ülke');
define('SettingsTimeZone', 'Zaman Dilimi');
define('SettingsAllTimeZones', 'Tüm zaman dilimleri');

define('WarningWorkdayStartsEnds', 'İş bitiş saati, başlangıç saatinden büyük olmalıdır.');
define('ReportSettingsUpdated', 'Ayarlar başarılı bir şekilde güncellenmiştir.');

define('SettingsTabCalendar', 'Takvim');

define('FullMonthJanuary', 'Ocak');
define('FullMonthFebruary', 'Şubat');
define('FullMonthMarch', 'Mart');
define('FullMonthApril', 'Nisan');
define('FullMonthMay', 'Mayıs');
define('FullMonthJune', 'Haziran');
define('FullMonthJuly', 'Temmuz');
define('FullMonthAugust', 'Ağustos');
define('FullMonthSeptember', 'Eylül');
define('FullMonthOctober', 'Ekim');
define('FullMonthNovember', 'Kasım');
define('FullMonthDecember', 'Aralık');

define('ShortMonthJanuary', 'Oca');
define('ShortMonthFebruary', 'Şub');
define('ShortMonthMarch', 'Mar');
define('ShortMonthApril', 'Nis');
define('ShortMonthMay', 'May');
define('ShortMonthJune', 'Haz');
define('ShortMonthJuly', 'Tem');
define('ShortMonthAugust', 'Ağu');
define('ShortMonthSeptember', 'Eyl');
define('ShortMonthOctober', 'Eki');
define('ShortMonthNovember', 'Kas');
define('ShortMonthDecember', 'Ara');

define('FullDayMonday', 'Pazartesi');
define('FullDayTuesday', 'Salı');
define('FullDayWednesday', 'Çarşamba');
define('FullDayThursday', 'Perşembe');
define('FullDayFriday', 'Cuma');
define('FullDaySaturday', 'Cumartesi');
define('FullDaySunday', 'Pazar');

define('DayToolMonday', 'Pzt');
define('DayToolTuesday', 'Sal');
define('DayToolWednesday', 'Çar');
define('DayToolThursday', 'Per');
define('DayToolFriday', 'Cum');
define('DayToolSaturday', 'Cts');
define('DayToolSunday', 'Paz');

define('CalendarTableDayMonday', 'Pt');
define('CalendarTableDayTuesday', 'S');
define('CalendarTableDayWednesday', 'Ç');
define('CalendarTableDayThursday', 'P');
define('CalendarTableDayFriday', 'C');
define('CalendarTableDaySaturday', 'Ct');
define('CalendarTableDaySunday', 'P');

define('ErrorParseJSON', 'JSON, sunucu tarafından işlenemediğine dair cevap döndü.');

define('ErrorLoadCalendar', 'Takvimler yüklenemiyor');
define('ErrorLoadEvents', 'Olaylar yüklenemiyor');
define('ErrorUpdateEvent', 'Olay kaydedilemedi');
define('ErrorDeleteEvent', 'Olay silinemedi');
define('ErrorUpdateCalendar', 'Takvim kaydedilemiyor');
define('ErrorDeleteCalendar', 'Takvim silinemiyor');
define('ErrorGeneral', 'Sunucuda bir hata oluştur. Lütfen daha sonra tekrar deneyiniz.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-posta');
define('ShareHeaderEdit', 'Takvim yayınla ve paylaş');
define('ShareActionEdit', 'Takvim yayınla ve paylaş');
define('CalendarPublicate', 'Bu takvime genel ağ erişimi sağla');
define('CalendarPublicationLink', 'Bağlantı');
define('ShareCalendar', 'Bu takvimi paylaş');
define('SharePermission1', 'Değişiklik yapma ve paylaşım yönetimi');
define('SharePermission2', 'Etkinlik değitirebilme');
define('SharePermission3', 'Bütün etkinlik bilgilerini görebilir');
define('SharePermission4', 'Sadece uygun/meşgul durumunu görebilir (bilgileri gizle)');
define('ButtonClose', 'Kapat');
define('WarningEmailFieldFilling', 'Öncelikle e-posta kısmına giriş yapmalısınız');
define('EventHeaderView', 'Etkinlik göster');
define('ErrorUpdateSharing', 'Paylaşım ve yayınlama verileri kaydedilemedi');
define('ErrorUpdateSharing1', '%s kullanıcısı mevcut olmadığından paylaşım sağlanamaz');
define('ErrorUpdateSharing2', 'Bu takvimi %s kullanıcısı ile paylaşmak imkansız');
define('ErrorUpdateSharing3', 'Bu takvim zaten %s kullanıcısı ile paylaşım halinde');
define('Title_MyCalendars', 'Takvimlerim');
define('Title_SharedCalendars', 'Paylaşılan takvimler');
define('ErrorGetPublicationHash', 'Yayınlama linki yaratılamıyor');
define('ErrorGetSharing', 'Paylaşım eklenemiyor');
define('CalendarPublishedTitle', 'Bu takvim yayınlanmıştır');
define('RefreshSharedCalendars', 'Paylaşılan Takvimleri Yenile');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Üyeler');

define('ReportMessagePartDisplayed', 'Bu mesajın yalnızca bir kısmı görüntülenmektedir.');
define('ReportViewEntireMessage', 'Mesajın bütününü görüntülemek için, ');
define('ReportClickHere', 'click here');
define('ErrorContactExists', 'Bu isim ve e-posta adresi ile kayıtlı bir kişi zaten mevcuttur.');

define('Attachments', 'Eklentiler');

define('InfoGroupsOfContact', 'Bu kişinin üye olduğu gruplara işaret konulmustur.');
define('AlertNoContactsSelected', 'Kişi seçilemedi.');
define('MailSelected', 'Seçili adreslere ileti');
define('CaptionSubscribed', 'Kayıt olundu.');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'Not Spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contacts
define('ContactMail', 'Mail contact');
define('ContactViewAllMails', 'View all mails with this contact');
define('ContactsMailThem', 'Mail them');
define('DateToday', 'Today');
define('DateYesterday', 'Yesterday');
define('MessageShowDetails', 'Show details');
define('MessageHideDetails', 'Hide details');
define('MessageNoSubject', 'No subject');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'to');
define('SearchClear', 'Clear search');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Search results for "#s" in #f folder:');
define('SearchResultsInAllFolders', 'Search results for "#s" in all mail folders:');
define('AutoresponderTitle', 'Autoresponder');
define('AutoresponderEnable', 'Enable autoresponder');
define('AutoresponderSubject', 'Subject');
define('AutoresponderMessage', 'Message');
define('ReportAutoresponderUpdatedSuccessfuly', 'Autoresponder has been updated successfully.');
define('FolderQuarantine', 'Quarantine');

//calendar
define('EventRepeats', 'Repeats');
define('NoRepeats', 'Does not repeat');
define('DailyRepeats', 'Daily');
define('WorkdayRepeats', 'Every weekday (Mon. - Fri.)');
define('OddDayRepeats', 'Every Mon., Wed. and Fri.');
define('EvenDayRepeats', 'Every Tues. and Thurs.');
define('WeeklyRepeats', 'Weekly');
define('MonthlyRepeats', 'Monthly');
define('YearlyRepeats', 'Yearly');
define('RepeatsEvery', 'Repeats every');
define('ThisInstance', 'Only this instance');
define('AllEvents', 'All events in the series');
define('AllFollowing', 'All following');
define('ConfirmEditRepeatEvent', 'Would you like to change only this event or all events in the series?');
define('RepeatEventHeaderEdit', 'Edit Recurring Event');
define('First', 'First');
define('Second', 'Second');
define('Third', 'Third');
define('Fourth', 'Fourth');
define('Last', 'Last');
define('Every', 'Every');
define('SetRepeatEventEnd', 'Set end date');
define('NoEndRepeatEvent', 'No end date');
define('EndRepeatEventAfter', 'End after');
define('Occurrences', 'occurrences');
define('EndRepeatEventBy', 'End by');
define('EventCommonDataTab', 'Main details');
define('EventRepeatDataTab', 'Recurrence details');
define('RepeatEventNotPartOfASeries', 'This event has been changed and is no longer part of a series.');
define('UndoRepeatExclusion', 'Undo changes to include in the series.');

define('MonthMoreLink', '%d more...');
define('NoNewSharedCalendars', 'No new calendars');
define('NNewSharedCalendars', '%d new calendars found');
define('OneNewSharedCalendars', '1 new calendar found');
define('ConfirmUndoOneRepeat', 'Would you like to restore this event in the series?');

define('RepeatEveryDayInfin', 'Every day');
define('RepeatEveryDayTimes', 'Every day, %TIMES% times');
define('RepeatEveryDayUntil', 'Every day, until %UNTIL%');
define('RepeatDaysInfin', 'Every %PERIOD% days');
define('RepeatDaysTimes', 'Every %PERIOD% days, %TIMES% times');
define('RepeatDaysUntil', 'Every %PERIOD% days, until %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Every week on weekdays');
define('RepeatEveryWeekWeekdaysTimes', 'Every week on weekdays, %TIMES% times');
define('RepeatEveryWeekWeekdaysUntil', 'Every week on weekdays, until %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Every %PERIOD% weeks on weekdays');
define('RepeatWeeksWeekdaysTimes', 'Every %PERIOD% weeks on weekdays, %TIMES% times');
define('RepeatWeeksWeekdaysUntil', 'Every %PERIOD% weeks on weekdays, until %UNTIL%');

define('RepeatEveryWeekInfin', 'Every week on %DAYS%');
define('RepeatEveryWeekTimes', 'Every week on %DAYS%, %TIMES% times');
define('RepeatEveryWeekUntil', 'Every week on %DAYS%, until %UNTIL%');
define('RepeatWeeksInfin', 'Every %PERIOD% weeks on %DAYS%');
define('RepeatWeeksTimes', 'Every %PERIOD% weeks on %DAYS%, %TIMES% times');
define('RepeatWeeksUntil', 'Every %PERIOD% weeks on %DAYS%, until %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Every month on day %DATE%');
define('RepeatEveryMonthDateTimes', 'Every month on day %DATE%, %TIMES% times');
define('RepeatEveryMonthDateUntil', 'Every month on day %DATE%, until %UNTIL%');
define('RepeatMonthsDateInfin', 'Every %PERIOD% months on day %DATE%');
define('RepeatMonthsDateTimes', 'Every %PERIOD% months on day %DATE%, %TIMES% times');
define('RepeatMonthsDateUntil', 'Every %PERIOD% months on day %DATE%, until %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Every month on %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Every month on %NUMBER% %DAY%, %TIMES% times');
define('RepeatEveryMonthWDUntil', 'Every month on %NUMBER% %DAY%, until %UNTIL%');
define('RepeatMonthsWDInfin', 'Every %PERIOD% months on %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Every %PERIOD% months on %NUMBER% %DAY%, %TIMES% times');
define('RepeatMonthsWDUntil', 'Every %PERIOD% months on %NUMBER% %DAY%, until %UNTIL%');

define('RepeatEveryYearDateInfin', 'Every year on day %DATE%');
define('RepeatEveryYearDateTimes', 'Every year on day %DATE%, %TIMES% times');
define('RepeatEveryYearDateUntil', 'Every year on day %DATE%, until %UNTIL%');
define('RepeatYearsDateInfin', 'Every %PERIOD% years on day %DATE%');
define('RepeatYearsDateTimes', 'Every %PERIOD% years on day %DATE%, %TIMES% times');
define('RepeatYearsDateUntil', 'Every %PERIOD% years on day %DATE%, until %UNTIL%');

define('RepeatEveryYearWDInfin', 'Every year on %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Every year on %NUMBER% %DAY%, %TIMES% times');
define('RepeatEveryYearWDUntil', 'Every year on %NUMBER% %DAY%, until %UNTIL%');
define('RepeatYearsWDInfin', 'Every %PERIOD% years on %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Every %PERIOD% years on %NUMBER% %DAY%, %TIMES% times');
define('RepeatYearsWDUntil', 'Every %PERIOD% years on %NUMBER% %DAY%, until %UNTIL%');

define('RepeatDescDay', 'day');
define('RepeatDescWeek', 'week');
define('RepeatDescMonth', 'month');
define('RepeatDescYear', 'year');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Please specify end recurrence date');
define('WarningWrongUntilDate', 'End recurrence date must be later than the start recurrence date');

define('OnDays', 'On days');
define('CancelRecurrence', 'Cancel recurrence');
define('RepeatEvent', 'Repeat this event');

define('Spellcheck', 'Check Spelling');
define('LoginLanguage', 'Language');
define('LanguageDefault', 'Öntanımlı');

// webmail 4.5.x new
define('EmptySpam', 'Empty Spam');
define('Saving', 'Saving&hellip;');
define('Sending', 'Sending&hellip;');
define('LoggingOffFromServer', 'Logging off from server&hellip;');

//webmail 4.6
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
