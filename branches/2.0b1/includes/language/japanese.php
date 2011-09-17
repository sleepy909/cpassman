<?php
//japanese
if (!isset($_SESSION['settings']['cpassman_url'])) {
$cpassman_url = '';
}else{
$cpassman_url = $_SESSION['settings']['cpassman_url'];
}

$txt['account_is_locked'] = "このアカウントはロックされています。\";
<b";
$txt['activity'] = "Activity";
$txt['add_button'] = "Add\";
<b";
$txt['add_new_group'] = "新しいフォルダを追加する";
$txt['add_role_tip'] = "Add a new role.\";
<b";
$txt['admin'] = "Administration\";
<b";
$txt['admin_action'] = "確認\";
<b";
$txt['admin_actions_title'] = "メンテナンスアクション\";
<b";
$txt['admin_action_check_pf'] = "Actualize Personal Folders for all users (creates them if not existing)\";
<b";
$txt['admin_action_db_backup'] = "データベースのバックアップを作成する。\";
<b";
$txt['admin_action_db_backup_key_tip'] = "入力した暗号キーは忘れないようにどこかに記録してください。リストア時に求められます。(暗号化しない場合は空のまま実行してください。)\";
<b";
$txt['admin_action_db_backup_start_tip'] = "スタート\";
<b";
$txt['admin_action_db_backup_tip'] = "データベースのバックアップファイルを作成し、後でリストアすることができます。\";
<b";
$txt['admin_action_db_clean_items'] = "データベースに残った削除済みアイテムを削除する。\";
<b";
$txt['admin_action_db_clean_items_result'] = "個のアイテムが削除されました。\";
<b";
$txt['admin_action_db_clean_items_tip'] = "フォルダが削除された際に、そのフォルダに関連付けられていたアイテムとログはデータベース上にのこっていますので、これらを削除します。実行前にバックアップを作成することをお勧めします。\";
<b";
$txt['admin_action_db_optimize'] = "データベースを最適化する。\";
<b";
$txt['admin_action_db_restore'] = "データベースをリストアする。\";
<b";
$txt['admin_action_db_restore_key'] = "暗号化キーを入力してください。\";
<b";
$txt['admin_action_db_restore_tip'] = "バックアップ機能で作成したSQLバックアップファイルを使用し、リストアします\";
<b";
$txt['admin_action_purge_old_files'] = "古いキャッシュファイルを削除\";
<b";
$txt['admin_action_purge_old_files_result'] = "files have been deleted.\";
<b";
$txt['admin_action_purge_old_files_tip'] = "7日以上前の一時ファイルを削除します。\";
<b";
$txt['admin_action_reload_cache_table'] = "キャッシュテーブルを再読み込みする\";
<b";
$txt['admin_action_reload_cache_table_tip'] = "This permits to reload the full content of table Cache. Can be usefull to be done sometimes.\";
<b";
$txt['admin_backups'] = "Backups";
$txt['admin_error_no_complexity'] = "(<a href='index.php?page=manage_groups'>Define?</a>)";
$txt['admin_error_no_visibility'] = "No one can see this item. (<a href='index.php?page=manage_roles'>Customize roles</a>)";
$txt['admin_functions'] = "ロール管理\";
<b";
$txt['admin_groups'] = "フォルダ管理\";
<b";
$txt['admin_help'] = "ヘルプ\";
<b";
$txt['admin_info'] = "Some information concerning the tool\";
<b";
$txt['admin_info_loading'] = "Loading data ... please wait\";
<b";
$txt['admin_ldap_configuration'] = "LDAP認証設定\";
<b";
$txt['admin_ldap_menu'] = "LDAP options\";
<b";
$txt['admin_main'] = "インフォメーション\";
<b";
$txt['admin_misc_cpassman_dir'] = "cPassManへのフルパス\";
<b";
$txt['admin_misc_cpassman_url'] = "cPassManへのフルURL\";
<b";
$txt['admin_misc_custom_login_text'] = "Custom Login text";
$txt['admin_misc_custom_logo'] = "Full url to Custom Login Logo";
$txt['admin_misc_favicon'] = "faviconファイルへのフルURL\";
<b";
$txt['admin_misc_title'] = "カスタム設定\";
<b";
$txt['admin_one_shot_backup'] = "One shot backup and restore";
$txt['admin_script_backups'] = "Settings for Backups script";
$txt['admin_script_backups_tip'] = "For more security, it is recommended to parameter a scheduled backup of the database.<br />Use your server to schedule a daily cron task by calling the file 'script.backup.php' in 'backups' folder.<br />You first need to set the 2 first paramteres and SAVE them.";
$txt['admin_script_backup_decrypt'] = "Name of the file you want to decrypt";
$txt['admin_script_backup_decrypt_tip'] = "In order to decrypt a backup file, just indicate the name of the backup file (no extension and no path).<br />The file will be decrypted in the same folder as the backup files are.";
$txt['admin_script_backup_encryption'] = "Encryption key (optional)";
$txt['admin_script_backup_encryption_tip'] = "If set, this key will be used to encrypted your file";
$txt['admin_script_backup_filename'] = "Backup file name";
$txt['admin_script_backup_filename_tip'] = "File name you want for your backups file";
$txt['admin_script_backup_path'] = "Path where backups have to be stored";
$txt['admin_script_backup_path_tip'] = "In what folder the backup files have to be stored";
$txt['admin_settings'] = "設定\";
<b";
$txt['admin_settings_title'] = "cPassMan設定\";
<b";
$txt['admin_setting_activate_expiration'] = "Enable passwords expiration\";
<b";
$txt['admin_setting_activate_expiration_tip'] = "When enabled, items expired will not be displayed to users.\";
<b";
$txt['admin_users'] = "ユーザー管理\";
<b";
$txt['admin_views'] = "Views\";
<b";
$txt['alert_message_done'] = "Done!\";
<b";
$txt['alert_message_personal_sk_missing'] = "You must enter your personal saltkey!\";
<b";
$txt['all'] = "all\";
<b";
$txt['anyone_can_modify'] = "Allow this item to be modified by anyone that can access it\";
<b";
$txt['associated_role'] = "What role to associate this folder to :\";
<b";
$txt['associate_kb_to_items'] = "Select the items to associate to this KB\";
<b";
$txt['assoc_authorized_groups'] = "Allowed Associated Folders\";
<b";
$txt['assoc_forbidden_groups'] = "Forbidden Associated Folders\";
<b";
$txt['at'] = "at\";
<b";
$txt['at_add_file'] = "File added\";
<b";
$txt['at_category'] = "フォルダ\";
<b";
$txt['at_copy'] = "Copy created";
$txt['at_copy'] = "Copy done";
$txt['at_creation'] = "Creation\";
<b";
$txt['at_delete'] = "Deletion\";
<b";
$txt['at_del_file'] = "File deleted\";
<b";
$txt['at_description'] = "Description.\";
<b";
$txt['at_file'] = "File\";
<b";
$txt['at_import'] = "Importation\";
<b";
$txt['at_label'] = "ラベル\";
<b";
$txt['at_login'] = "ログイン\";
<b";
$txt['at_modification'] = "Modification\";
<b";
$txt['at_personnel'] = "Personal\";
<b";
$txt['at_pw'] = "Password changed.\";
<b";
$txt['at_restored'] = "Restored\";
<b";
$txt['at_shown'] = "Accessed";
$txt['at_url'] = "URL\";
<b";
$txt['auteur'] = "Author\";
<b";
$txt['author'] = "Author\";
<b";
$txt['authorized_groups'] = "許可されたフォルダ\";
<b";
$txt['auth_creation_without_complexity'] = "必要とされるパスワードの強度を無視したアイテムの作成を許可する。\";
<b";
$txt['auth_modification_without_complexity'] = "必要とされるパスワードの強度を無視したアイテムの修正を許可する。\";
<b";
$txt['auto_create_folder_role'] = "Create folder and role for \";
<b";
$txt['block_last_created'] = "Last created\";
<b";
$txt['bugs_page'] = "If you discover a bug, you can directly post it in <a href='https://sourceforge.net/tracker/?group_id=280505&amp;atid=1190333' target='_blank'><u>Bugs Forum</u></a>.";
$txt['by'] = "by\";
<b";
$txt['cancel'] = "キャンセル\";
<b";
$txt['cancel_button'] = "キャンセル\";
<b";
$txt['can_create_root_folder'] = "Can create a folder at root level\";
<b";
$txt['changelog'] = "Latest news\";
<b";
$txt['change_authorized_groups'] = "Change authorized folders\";
<b";
$txt['change_forbidden_groups'] = "Change forbidden folders\";
<b";
$txt['change_function'] = "Change roles\";
<b";
$txt['change_group_autgroups_info'] = "Select the authorized folders this Role can see and use\";
<b";
$txt['change_group_autgroups_title'] = "Customize the authorized folders\";
<b";
$txt['change_group_forgroups_info'] = "Select the forbidden folders this Role can't see and use";
$txt['change_group_forgroups_title'] = "Customize the forbidden folders\";
<b";
$txt['change_user_autgroups_info'] = "Select the authorized folders this account can see and use\";
<b";
$txt['change_user_autgroups_title'] = "Customize the authorized folders\";
<b";
$txt['change_user_forgroups_info'] = "Select the forbidden folders this account can't see nor use";
$txt['change_user_forgroups_title'] = "Customize the forbidden folders\";
<b";
$txt['change_user_functions_info'] = "Select the functions associated to this account\";
<b";
$txt['change_user_functions_title'] = "Customize associated functions\";
<b";
$txt['check_all_text'] = "Check all\";
<b";
$txt['close'] = "Close\";
<b";
$txt['complexity'] = "パスワードの強度\";
<b";
$txt['complex_asked'] = "必要なパスワードの強度\";
<b";
$txt['complex_asked'] = "Required complexity";
$txt['complex_level0'] = "とても弱い\";
<b";
$txt['complex_level1'] = "弱い\";
<b";
$txt['complex_level2'] = "普通\";
<b";
$txt['complex_level3'] = "強い\";
<b";
$txt['complex_level4'] = "とても強い\";
<b";
$txt['complex_level5'] = "さらに強い\";
<b";
$txt['complex_level6'] = "最強\";
<b";
$txt['confirm'] = "パスワードを再度入力\";
<b";
$txt['confirm_delete_group'] = "You have decided to delete this Folder and all included Items ... are you sure?\";
<b";
$txt['confirm_deletion'] = "本当に削除してもよろしいですか?\";
<b";
$txt['confirm_del_account'] = "You have decided to delete this Account. Are you sure?\";
<b";
$txt['confirm_del_from_fav'] = "Please confirm deletion from Favourites\";
<b";
$txt['confirm_del_role'] = "次のロールを削除します、よろしいですか？\";
<b";
$txt['confirm_edit_role'] = "次のロールの名前を入力してください。:\";
<b";
$txt['connection'] = "Connection\";
<b";
$txt['connections'] = "接続\";
<b";
$txt['copy'] = "コピー\";
<b";
$txt['copy_to_clipboard_small_icons'] = "Enable copy to clipboard small icons in items page\";
<b";
$txt['copy_to_clipboard_small_icons_tip'] = "<span style='font-size:11px;max-width:300px;'>This could help preventing memory usage if users have no recent computer.<br /> Indeed, the clipboard is not loaded with items informations. But no quick copy of password and login is possible.</span>
";
$txt['creation_date'] = "作成日時\";
<b";
$txt['csv_import_button_text'] = "CSVファイルを参照する\";
<b";
$txt['date'] = "日時\";
<b";
$txt['date'] = "Date";
$txt['date_format'] = "日付フォーマット\";
<b";
$txt['days'] = "days\";
<b";
$txt['definition'] = "定義\";
<b";
$txt['delete'] = "Delete\";
<b";
$txt['deletion'] = "削除ログ\";
<b";
$txt['deletion_title'] = "削除したアイテムリスト\";
<b";
$txt['del_button'] = "削除\";
<b";
$txt['del_function'] = "ロールを削除\";
<b";
$txt['del_group'] = "フォルダを削除\";
<b";
$txt['description'] = "説明\";
<b";
$txt['description'] = "Description";
$txt['disconnect'] = "ログアウト\";
<b";
$txt['disconnection'] = "Disconnection\";
<b";
$txt['div_dialog_message_title'] = "Information\";
<b";
$txt['done'] = "Done\";
<b";
$txt['drag_drop_helper'] = "Drag and drop item\";
<b";
$txt['duplicate_folder'] = "Authorize to have several folders with the same name.\";
<b";
$txt['duplicate_item'] = "Authorize to have several items with the same name.\";
<b";
$txt['email'] = "メールアドレス\";
<b";
$txt['email_altbody_1'] = "Item\";
<b";
$txt['email_altbody_2'] = "has been created.\";
<b";
$txt['email_announce'] = "このアイテムをメールで通知\";
<b";
$txt['email_body1'] = "Hi,<br><br>Item '";
$txt['email_body2'] = "has been created.<br /><br />You may view it by clicking <a href='";
$txt['email_body3'] = "'>HERE</a><br /><br />Regards.";
$txt['email_change'] = "アカウントのEメールを変更\";
<b";
$txt['email_changed'] = "Email changed!\";
<b";
$txt['email_select'] = "Select persons to inform\";
<b";
$txt['email_subject'] = "Creating a new Item in Passwords Manager\";
<b";
$txt['email_subject_new_user'] = "[cPassMan] Your account creation\";
<b";
$txt['email_text_new_user'] = "Hi,<br /><br />Your account has been created in cPassMan.<br />You can now access $cpassman_url using the next credentials:<br />\";
<b";
$txt['enable_favourites'] = "Enable the Users to store Favourites\";
<b";
$txt['enable_personal_folder'] = "Enable Personal folder\";
<b";
$txt['enable_personal_folder_feature'] = "Enable Personal folder feature\";
<b";
$txt['enable_user_can_create_folders'] = "Users are allowed to manage folders in allowed parent folders\";
<b";
$txt['encrypt_key'] = "暗号キー\";
<b";
$txt['errors'] = "エラー\";
<b";
$txt['error_complex_not_enought'] = "Password complexity is not fulfilled!\";
<b";
$txt['error_confirm'] = "Password confirmation is not correct!\";
<b";
$txt['error_cpassman_dir'] = "No path for cPassMan is set. Please select 'cPassMan settings' tab in Admin Settings page.";
$txt['error_cpassman_url'] = "No URL for cPassMan is set. Please select 'cPassMan settings' tab in Admin Settings page.";
$txt['error_fields_2'] = "The 2 fields are mandatory!\";
<b";
$txt['error_group'] = "A folder is mandatory!\";
<b";
$txt['error_group_complex'] = "The Folder must have a minimum required passwords complexity level!\";
<b";
$txt['error_group_exist'] = "This folder already exists!\";
<b";
$txt['error_group_label'] = "The Folder must be named!\";
<b";
$txt['error_html_codes'] = "Some text contains HTML codes! This is not allowed.\";
<b";
$txt['error_item_exists'] = "This Item already exists!\";
<b";
$txt['error_label'] = "A label is mandatory!\";
<b";
$txt['error_must_enter_all_fields'] = "You must fill in each fields!\";
<b";
$txt['error_mysql'] = "MySQL Error!\";
<b";
$txt['error_not_authorized'] = "このページを閲覧する権限がありません。";
$txt['error_not_exists'] = "ページが存在しません。";
$txt['error_no_folders'] = "まずはフォルダを作成してください。\";
<b";
$txt['error_no_password'] = "You need to enter your password!\";
<b";
$txt['error_no_roles'] = "ロールも作成し、フォルダに関連付けをしてください。\";
<b";
$txt['error_password_confirmation'] = "Passwords should be the same\";
<b";
$txt['error_pw'] = "A password is mandatory!\";
<b";
$txt['error_renawal_period_not_integer'] = "Renewal period should be expressed in months!\";
<b";
$txt['error_salt'] = "<b>The SALT KEY is too long! Please don't use the tool until an Admin has modified the salt key.</b> In settings.php file, SALT should not be longer than 32 characters.";
$txt['error_tags'] = "No punctuation characters allowed in TAGS! Only space.\";
<b";
$txt['error_user_exists'] = "User already exists\";
<b";
$txt['expiration_date'] = "有効期限切れ日時\";
<b";
$txt['expir_one_month'] = "1ヶ月\";
<b";
$txt['expir_one_year'] = "1年\";
<b";
$txt['expir_six_months'] = "6ヶ月\";
<b";
$txt['expir_today'] = "今日\";
<b";
$txt['files_&_images'] = "ファイル&amp; 画像\";
<b";
$txt['find'] = "検索\";
<b";
$txt['find_text'] = "Your search\";
<b";
$txt['folders'] = "フォルダ\";
<b";
$txt['forbidden_groups'] = "禁止されたフォルダ\";
<b";
$txt['forgot_my_pw'] = "パスワードを忘れた\";
<b";
$txt['forgot_my_pw_email_sent'] = "Email has been sent\";
<b";
$txt['forgot_my_pw_error_email_not_exist'] = "This email doesn't exist!";
$txt['forgot_my_pw_text'] = "あなたのパスワードがアカウントに紐付いたメールアドレスに送信されます。\";
<b";
$txt['forgot_pw_email_altbody_1'] = "Hi, Your identification credentials for cPassMan are:\";
<b";
$txt['forgot_pw_email_body'] = "Hi,<br /><br />Your new password for cPassMan is :\";
<b";
$txt['forgot_pw_email_body'] = "Hi,<br /><br />Your new password for cPassMan is :";
$txt['forgot_pw_email_body_1'] = "Hi, <br /><br />Your identification credentials for cPassMan are:<br /><br />\";
<b";
$txt['forgot_pw_email_subject'] = "cPassMan - Your password\";
<b";
$txt['forgot_pw_email_subject_confirm'] = "[cPassMan] Your password step 2\";
<b";
$txt['functions'] = "ロール\";
<b";
$txt['function_alarm_no_group'] = "This role is not associated to any Folder!\";
<b";
$txt['generate_pdf'] = "PDFファイルを生成\";
<b";
$txt['generation_options'] = "Generation options\";
<b";
$txt['gestionnaire'] = "マネージャー\";
<b";
$txt['give_function_tip'] = "新しいロールを追加する\";
<b";
$txt['give_function_title'] = "新しいロールを追加する\";
<b";
$txt['give_new_email'] = "Please enter new email for\";
<b";
$txt['give_new_login'] = "Please select the account\";
<b";
$txt['give_new_pw'] = "Please indicate the new password for\";
<b";
$txt['god'] = "神\";
<b";
$txt['group'] = "フォルダ\";
<b";
$txt['group_parent'] = "親階層フォルダ\";
<b";
$txt['group_pw_duration'] = "更新期間\";
<b";
$txt['group_pw_duration_tip'] = "月単位。0を指定すると更新なし\";
<b";
$txt['group_select'] = "Select folder\";
<b";
$txt['group_title'] = "フォルダラベル\";
<b";
$txt['history'] = "履歴\";
<b";
$txt['home'] = "ホーム\";
<b";
$txt['home_personal_menu'] = "Personal Actions\";
<b";
$txt['home_personal_saltkey'] = "Your personal SALTKey\";
<b";
$txt['home_personal_saltkey_button'] = "Store it!\";
<b";
$txt['home_personal_saltkey_info'] = "You should enter your personal saltkey if you need to use your personal items.\";
<b";
$txt['home_personal_saltkey_label'] = "Enter your personal salt key\";
<b";
$txt['importing_details'] = "List of details\";
<b";
$txt['importing_folders'] = "Importing folders\";
<b";
$txt['importing_items'] = "Importing items\";
<b";
$txt['import_button'] = "Import\";
<b";
$txt['import_csv_anyone_can_modify_in_role_txt'] = "インポートしたアイテムすべてに\"同じ権限ならだれでも修正できる\" 権限を設定する。\";
<b";
$txt['import_csv_anyone_can_modify_txt'] = "インポートしたアイテムすべてに\"誰でも修正できる\"権限を設定する。\";
<b";
$txt['import_csv_dialog_info'] = "インフォメーション: インポートはCSV形式のファイルを使用して行われます。特に、KeePasからエクスポートされたファイルを想定した構成になってます。<br />もし他のツールを使用してファイルを生成した場合は、CSVの構造が、`アカウント`,`ログイン名`,`パスワード`,`ウェブサイト`,`コメント`となっているかを確認してください。\";
<b";
$txt['import_csv_menu_title'] = "ファイルからアイテムをインポート(CSV/KeePass XML)\";
<b";
$txt['import_error_no_file'] = "You must select a file!\";
<b";
$txt['import_error_no_read_possible'] = "Can't read the file!";
$txt['import_error_no_read_possible_kp'] = "Can't read the file! It must be a KeePass file.";
$txt['import_keepass_dialog_info'] = "KeepPassのエキスポート機能で作成したXMLファイルを使用する場合にこの機能を使用します。KeePassファイルでのみ動作します。ツリー構造の同階層レベルにフォルダやエレメントが既にある場合は、インポートされません。\";
<b";
$txt['import_keepass_to_folder'] = "インポート先フォルダを選択\";
<b";
$txt['import_kp_finished'] = "KeePassからのインポートが終了しました。<br />デフォルトで新しくインポートされたフォルダに要求されるパスワードの複雑さは、”中”に設定されます。。必要に応じて設定を変更してください。\";
<b";
$txt['import_to_folder'] = "Tick the items you want to import to folder:\";
<b";
$txt['index_add_one_hour'] = "セッションを1時間延長する\";
<b";
$txt['index_alarm'] = "ALARM!!!\";
<b";
$txt['index_bas_pw'] = "アカウント又はパスワードが正しくありません。\";
<b";
$txt['index_change_pw'] = "Your password must be changed!\";
<b";
$txt['index_change_pw'] = "Change your password";
$txt['index_change_pw_button'] = "Change\";
<b";
$txt['index_change_pw_confirmation'] = "パスワードを再度入力\";
<b";
$txt['index_expiration_in'] = "セッション残り時間\";
<b";
$txt['index_get_identified'] = "ログインしてください\";
<b";
$txt['index_identify_button'] = "ログイン\";
<b";
$txt['index_identify_you'] = "Please identify yourself\";
<b";
$txt['index_last_pw_change'] = "パスワード変更日時\";
<b";
$txt['index_last_seen'] = "最終アクセス時刻\";
<b";
$txt['index_login'] = "アカウント\";
<b";
$txt['index_maintenance_mode'] = "メンテナンスモードが有効になってます。管理者のみログインすることができます。\";
<b";
$txt['index_maintenance_mode_admin'] = "メンテナンスモードが有効になってます。 一般ユーザーは現在cPassManにアクセスできません。\";
<b";
$txt['index_new_pw'] = "新しいパスワード\";
<b";
$txt['index_password'] = "パスワード\";
<b";
$txt['index_pw_error_identical'] = "The passwords have to be identical!\";
<b";
$txt['index_pw_expiration'] = "Actual password expiration in\";
<b";
$txt['index_pw_level_txt'] = "Complexity\";
<b";
$txt['index_refresh_page'] = "Refresh page\";
<b";
$txt['index_session_duration'] = "セッション期間\";
<b";
$txt['index_session_ending'] = "Your session will end in less than 1 minute.\";
<b";
$txt['index_session_expired'] = "ログアウトしました\";
<b";
$txt['index_welcome'] = "ようこそ\";
<b";
$txt['info'] = "Information\";
<b";
$txt['info_click_to_edit'] = "この値を編集するにはセルをクリック\";
<b";
$txt['is_admin'] = "Is Admin\";
<b";
$txt['is_manager'] = "Is Manager\";
<b";
$txt['items_browser_title'] = "フォルダ名\";
<b";
$txt['item_copy_to_folder'] = "\"Please select a folder in which the item has to be copied.";
$txt['item_menu_add_elem'] = "アイテムを追加\";
<b";
$txt['item_menu_add_rep'] = "フォルダを追加\";
<b";
$txt['item_menu_add_to_fav'] = "お気に入りに追加\";
<b";
$txt['item_menu_collab_disable'] = "Edition is not allowed\";
<b";
$txt['item_menu_collab_enable'] = "Edition is allowed\";
<b";
$txt['item_menu_copy_elem'] = "アイテムをコピー\";
<b";
$txt['item_menu_copy_login'] = "Copy login\";
<b";
$txt['item_menu_copy_pw'] = "Copy password\";
<b";
$txt['item_menu_del_elem'] = "アイテムを削除\";
<b";
$txt['item_menu_del_from_fav'] = "Delete from Favourites\";
<b";
$txt['item_menu_del_rep'] = "フォルダを削除\";
<b";
$txt['item_menu_edi_elem'] = "アイテムを編集\";
<b";
$txt['item_menu_edi_rep'] = "フォルダを編集\";
<b";
$txt['item_menu_find'] = "検索\";
<b";
$txt['item_menu_mask_pw'] = "Mask password\";
<b";
$txt['item_menu_refresh'] = "更新\";
<b";
$txt['kbs'] = "KBs";
$txt['kb_menu'] = "Knowledge Base\";
<b";
$txt['keepass_import_button_text'] = "Browse XML file\";
<b";
$txt['label'] = "ラベル\";
<b";
$txt['last_items_icon_title'] = "最後に見たアイテムを 表示/隠す\";
<b";
$txt['last_items_title'] = "最後に見たアイテム\";
<b";
$txt['ldap_extension_not_loaded'] = "The LDAP extension is not activated on the server.";
$txt['level'] = "レベル\";
<b";
$txt['link_copy'] = "このアイテムのURLをクリップボードへコピー\";
<b";
$txt['link_is_copied'] = "このアイテムへのリンクをクリップボードにコピーしました。\";
<b";
$txt['login'] = "ログイン (任意)\";
<b";
$txt['login_attempts_on'] = " login attempts on \";
<b";
$txt['login_copied_clipboard'] = "Login copied in clipboard\";
<b";
$txt['login_copy'] = "アカウントをクリップボードをコピー\";
<b";
$txt['logs'] = "ログ\";
<b";
$txt['logs_1'] = "Generate the log file for the passwords renewal done the\";
<b";
$txt['logs_passwords'] = "パスワード生成ログ\";
<b";
$txt['maj'] = "Uppercase letters\";
<b";
$txt['mask_pw'] = "パスワードを表示する/隠す\";
<b";
$txt['max_last_items'] = "Maximum number of last items seen by user (default is 10)\";
<b";
$txt['minutes'] = "分\";
<b";
$txt['modify_button'] = "Modify\";
<b";
$txt['my_favourites'] = "お気に入り\";
<b";
$txt['name'] = "Name\";
<b";
$txt['nb_false_login_attempts'] = "アカウントがロックされるログイン失敗回数(0は無制限)\";
<b";
$txt['nb_folders'] = "フォルダの数\";
<b";
$txt['nb_items'] = "アイテムの数\";
<b";
$txt['nb_items_by_page'] = "Number of items by page";
$txt['new_label'] = "新しいラベル\";
<b";
$txt['new_role_title'] = "新しいロールタイトル\";
<b";
$txt['new_user_title'] = "Add a new user\";
<b";
$txt['no'] = "No\";
<b";
$txt['nom'] = "Name\";
<b";
$txt['none'] = "None\";
<b";
$txt['none_selected_text'] = "None selected\";
<b";
$txt['not_allowed_to_see_pw'] = "You are not allowed to see that Item!\";
<b";
$txt['not_allowed_to_see_pw_is_expired'] = "This item has expired!\";
<b";
$txt['not_defined'] = "Not defined\";
<b";
$txt['no_last_items'] = "No items seen\";
<b";
$txt['no_restriction'] = "No restriction\";
<b";
$txt['numbers'] = "Numbers\";
<b";
$txt['number_of_used_pw'] = "古いパスワードを再利用する際にユーザーが入力しなければならないパスワードの回数\";
<b";
$txt['ok'] = "OK\";
<b";
$txt['pages'] = "Pages\";
<b";
$txt['pdf_del_date'] = "PDF generated the\";
<b";
$txt['pdf_del_title'] = "パスワードの更新フォロー\";
<b";
$txt['pdf_download'] = "Download file\";
<b";
$txt['personal_folder'] = "Personal folder\";
<b";
$txt['personal_salt_key'] = "Your personal salt key\";
<b";
$txt['personal_salt_key_empty'] = "Personal salt key has not been entered!\";
<b";
$txt['personal_salt_key_info'] = "This salt key will be used to encrypt and decrypt your passwords.<br />It is not stored in database, you are the only person who knows it.<br />So don't loose it!";
$txt['please_update'] = "Please update the tool!\";
<b";
$txt['print'] = "Print\";
<b";
$txt['print_out_menu_title'] = "Print out a listing of your items\";
<b";
$txt['print_out_pdf_title'] = "cPassMan - List of exported Items\";
<b";
$txt['print_out_warning'] = "All passwords and all confidential data will be written in this file without any encryption! By writing the file containing unencrypted items/passwords, you are accepting the full responsibility for further protection of this list!\";
<b";
$txt['pw'] = "パスワード\";
<b";
$txt['pw_change'] = "アカウントのパスワード変更\";
<b";
$txt['pw_changed'] = "パスワードが変更されました!\";
<b";
$txt['pw_copied_clipboard'] = "Password copied to clipboard\";
<b";
$txt['pw_copy_clipboard'] = "パスワードをクリップボードにコピー\";
<b";
$txt['pw_generate'] = "生成\";
<b";
$txt['pw_is_expired_-_update_it'] = "This item has expired! You need to change its password.\";
<b";
$txt['pw_life_duration'] = "ユーザーのパスワード有効期限(日数指定, 0は無制限)\";
<b";
$txt['pw_recovery_asked'] = "You have asked for a password recovery\";
<b";
$txt['pw_recovery_button'] = "Send me my new password\";
<b";
$txt['pw_recovery_info'] = "By clicking on the next button, you will receive an email that contains the new password for your account.\";
<b";
$txt['pw_used'] = "This password has already been used!\";
<b";
$txt['readme_open'] = "Open full readme file\";
<b";
$txt['refresh_matrix'] = "表をリフレッシュする\";
<b";
$txt['renewal_menu'] = "Renewal follow-up\";
<b";
$txt['renewal_needed_pdf_title'] = "List of Items that need to be renewed\";
<b";
$txt['renewal_selection_text'] = "次の期間内に期限切れになるアイテムを表示:\";
<b";
$txt['restore'] = "リストア\";
<b";
$txt['restore'] = "Restore";
$txt['restricted_to'] = "利用者限定\";
<b";
$txt['restricted_to_roles'] = "Allow to restrict items to Users and Roles\";
<b";
$txt['rights_matrix'] = "Users rights matrix\";
<b";
$txt['roles'] = "ロール\";
<b";
$txt['role_cannot_modify_all_seen_items'] = "Set this role not allowed to modify all accessible items (normal setting)\";
<b";
$txt['role_can_modify_all_seen_items'] = "Set this role allowed to modify all accessible items (not secure setting)\";
<b";
$txt['root'] = "Root\";
<b";
$txt['save_button'] = "保存\";
<b";
$txt['secure'] = "Secure\";
<b";
$txt['see_logs'] = "See Logs";
$txt['select'] = "select\";
<b";
$txt['select_folders'] = "フォルダを選択\";
<b";
$txt['select_language'] = "言語を選択\";
<b";
$txt['send'] = "Send\";
<b";
$txt['settings_anyone_can_modify'] = "Activate an option for each item that allows anyone to modify it\";
<b";
$txt['settings_anyone_can_modify_tip'] = "<span style='font-size:11px;max-width:300px;'>When activated, this will add a checkbox in the item form that permits the creator to allow the modification of this item by anyone.</span>";
$txt['settings_kb'] = "Enable Knowledge Base (beta)\";
<b";
$txt['settings_kb_tip'] = "<span style='font-size:11px;max-width:300px;'>When activated, this will add a page where you can build your knowledge base.</span>";
$txt['settings_ldap_domain'] = "あなたのドメインのLDAPアカウントサフィックス\";
<b";
$txt['settings_ldap_domain_controler'] = "ドメインコントローラーのLDAPアレイ\";
<b";
$txt['settings_ldap_domain_controler_tip'] = "<span style='font-size:11px;max-width:300px;'>Specifiy multiple controllers if you would like the class to balance the LDAP queries amongst multiple servers.<br />You must delimit the domains by a comma ( , )!<br />By example: domain_1,domain_2,domain_3</span>";
$txt['settings_ldap_domain_dn'] = "あなたのドメインのLDAPベース dn\";
<b";
$txt['settings_ldap_mode'] = "LDAPサーバーを使ったユーザー認証を有効にする。\";
<b";
$txt['settings_ldap_mode_tip'] = "Enable only if you have an LDAP server and if you want to use it to authentify cPassMan users through it.\";
<b";
$txt['settings_ldap_ssl'] = "SSL経由でLDAPを使用する(LDAPS)\";
<b";
$txt['settings_ldap_tls'] = "TLS経由でLDAPを使用する\";
<b";
$txt['settings_log_accessed'] = "Enable loggin who accessed the items";
$txt['settings_log_connections'] = "全てのユーザーのコネクションをDBにロギングする。\";
<b";
$txt['settings_maintenance_mode'] = "メンテナンスモードに設定\";
<b";
$txt['settings_maintenance_mode_tip'] = "このモードは管理者以外の全てのユーザーコネクションを拒絶します。\";
<b";
$txt['settings_manager_edit'] = "Managers can edit and delete Items they are allowed to see\";
<b";
$txt['settings_printing'] = "Enable printing items to PDF file\";
<b";
$txt['settings_printing_tip'] = "When enabled, a button will be added to user's home page that will permit him/her to write a listing of items to a PDF file he/she can view. Notice that the listed passwords will be uncrypted.";
$txt['settings_richtext'] = "Enable richtext for item description\";
<b";
$txt['settings_richtext_tip'] = "<span style='font-size:11px;max-width:300px;'>This will activate a richtext with BBCodes in description field.</span>";
$txt['settings_send_stats'] = "cPassManの利用状況を伝えるために、月間統計情報を作者に送信する。\";
<b";
$txt['settings_send_stats_tip'] = "These statistics are entirely anonymous!<br /><span style='font-size:10px;max-width:300px;'>Your IP is not sent, just the following data are transmitted: amount of Items, Folders, Users, cPassman version, personal folders enabled, ldap enabled.<br />Many thanks if you enable those statistics. By this you help me further develop cPassMan.</span>";
$txt['show'] = "Show\";
<b";
$txt['show_help'] = "ヘルプを表示\";
<b";
$txt['show_last_items'] = "Show last items block on main page\";
<b";
$txt['size'] = "Size\";
<b";
$txt['start_upload'] = "Start uploading files\";
<b";
$txt['sub_group_of'] = "Dependent on\";
<b";
$txt['support_page'] = "For any support, please use the <a href='https://sourceforge.net/projects/communitypasswo/forums' target='_blank'><u>Forum</u></a>.";
$txt['symbols'] = "Symbols\";
<b";
$txt['tags'] = "タグ\";
<b";
$txt['thku'] = "Thank you for using cPassMan!\";
<b";
$txt['timezone_selection'] = "タイムゾーン\";
<b";
$txt['time_format'] = "時間フォーマット\";
<b";
$txt['uncheck_all_text'] = "Uncheck all\";
<b";
$txt['unlock_user'] = "User is locked. Do you want to unlock this account?\";
<b";
$txt['update_needed_mode_admin'] = "It is recommended to update your cPassMan installation. Click <a href='install/upgrade.php'>HERE</a>";
$txt['uploaded_files'] = "Existing Files\";
<b";
$txt['upload_button_text'] = "Browse\";
<b";
$txt['upload_files'] = "Upload New Files\";
<b";
$txt['url'] = "URL\";
<b";
$txt['url_copied'] = "URLがコピーされました!\";
<b";
$txt['used_pw'] = "使用されているパスワード\";
<b";
$txt['user'] = "User\";
<b";
$txt['users'] = "Users\";
<b";
$txt['user_alarm_no_function'] = "This user has no Roles!\";
<b";
$txt['user_del'] = "アカウントを削除\";
<b";
$txt['version'] = "Current version\";
<b";
$txt['views_confirm_items_deletion'] = "Do you really want to delete the selected items from database?\";
<b";
$txt['views_confirm_restoration'] = "Please confirm the restoration of this Item\";
<b";
$txt['visibility'] = "Visibility\";
<b";
$txt['yes'] = "Yes\";
<b";
$txt['your_version'] = "Your version\";
<b";
?>