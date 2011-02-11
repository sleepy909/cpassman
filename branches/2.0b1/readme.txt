            88888888ba                                     88b           d88
            88      "8b                                    888b         d888
            88      ,8P                                    88`8b       d8'88
 ,adPPYba,  88aaaaaa8P'  ,adPPYYba,  ,adPPYba,  ,adPPYba,  88 `8b     d8' 88  ,adPPYYba,  8b,dPPYba,
a8"     ""  88""""""'    ""     `Y8  I8[    ""  I8[    ""  88  `8b   d8'  88  ""     `Y8  88P'   `"8a
8b          88           ,adPPPPP88   `"Y8ba,    `"Y8ba,   88   `8b d8'   88  ,adPPPPP88  88       88
"8a,   ,aa  88           88,    ,88  aa    ]8I  aa    ]8I  88    `888'    88  88,    ,88  88       88
 `"Ybbd8"'  88           `"8bbdP"Y8  `"YbbdP"'  `"YbbdP"'  88     `8'     88  `"8bbdP"Y8  88       88

|===================================================================================================|
|						cPassMan for Collaborative Passwords Manager								|
|								2009-2010 (c) Nils Laumaillé										|
|								nils_dot_laumaille_at_gmail_com										|
|===================================================================================================|

*****************************************************************************************************
***** 	  																						*****
***** 	  								LICENCE AGREEMENT										*****
***** 	  Before installing and using cPassMan, you must accept its licence defined				*****
*****	as "Creative Commons 3.0 BY-NC-ND" (http://creativecommons.org/licenses/by-nc-nd/3.0/)	*****
***** 	  																						*****
*****************************************************************************************************

----------------------------------------  INFORMATIONS  ---------------------------------------------
Website : http://cpassman.org/
Hosted at SourceForge : https://sourceforge.net/projects/communitypasswo/

BUGS :
*For bugs discovery, please report in SourceForge Tracker (https://sourceforge.net/tracker/?group_id=280505&atid=1190333).

INSTALLATION :
* http://cpassman.org/help
* Once uploaded, launch install/install.php and follow instructions.

UPDATE :
* Once uploaded, launch install/upgrade.php and follow instructions.
-----------------------------------------------------------------------------------------------------

THANKS TO Enrique, shauninja, seskahin, tstd0, Tommi, Petr and Philipp.
Languages files created by Petr (Czech) and Philipp (German) ... thanks for your work.

-----------------------------------------------------------------------------------------------------
TODO
* IMAP login
-----------------------------------------------------------------------------------------------------
IN PROGRESS :
* CSV and KP import : add modification by "roles" option

CHANGELOG
v2.00
* improvement: convert cPassMan to UTF-8 charset
* improvement: all data exchanges concerning items are encrypted based on AES-256
* improvement: new design
* improvement: cpassman uses jquery 1.4.4 and jqueryui 1.8.7
* improvement: drag and drop items into folder (remain some dropable area troubles)
* improvement: allow role access on item base [3160946]
* improvement: new strategy for user password recovery
* improvement: automatic creation of role and folder based on email domain
* improvement: when deleting a user, personal folder and items are deleted
* improvement: LDAP login (thanks to Paul)
* fixed: problem with slash in label [3141743]
* fixed: password in cleartext in log [3141167]
* fixed: deleted pws show up in the search [3160582]
* fixed: GUI corrections [3160611]
* fixed: delete session of a deleted user [16]

v1.80
* improvement: an item can be modified by anyone if activated [3040120]
* improvement: better implementation of personal saltkey
* improvement: added a knowledge base (KB). Actually in beta
* improvement: personal saltkey is now to set in home page
* improvement: option anyone can modify item
* improvement: account is locked after X false login attempts (option)
* improvement: user password are heavyly encrypted
* improvement: new right "can create folder at root level" for manager [3117061]
* fixed: header error due to setcookie [3091330]
* fixed: users defined as "manager" are not displayed [3025269]
* fixed: several strange behaviours when connected as Manager [3095670]
* fixed: bug in special signes such as + in passwords [3091308]
* fixed: richtext editor has been changed in order to provide better cross-over webbrowsers [394855]
* fixed: header error on download file [3105400]
* fixed: if no table prefix is given, installation is now possible [3105318]
* fixed: folder created by manager are not visible [3112088]
* fixed: managers created by a manager has by default roles [3112109][3115561]
* fixed: users can be deleted [3112105]
* fixed: installation is possible without any table prefix [3112105]
* fixed: login impossible with $ signe in user password [3123326]
* fixed: only administrator can set some options on users [3123409]

v1.70
* improvement: Richtext for description field is under specific option [3057250]
* improvement: sending anonymous statistics for better understanding of cpassman
* improvement: cPassMan is translated into German and Czech
* improvement: new secure passwords automatic creation option [3046043] ... Tommi's idea ;
* improvement: users can export a list of items in a PDF file (under option)
* improvement: a quicklink for put into favorites the items has been added
* improvement: language selection is stored in a cookie
* improvement: several forms/visual improvements
* fixed: SQL error in Find page [3052051]
* fixed: login under Google Chrome should now works in all situations [3044406]
* fixed: items page is not loaded when passwords contains specific characters
* fixed: passwords should be less sensible to special characters
* fixed: find page is empty [3044408 / 3047356]
* fixed: when deleting an item, the associate favorite is also deleted
* fixed: sanitize database password when installing [3075209]

v1.61
* fixed: bug in installation query

v1.60
* improvement: Maintenance mode has been added
* improvement: user doesn't need to get disconnected to propagate new rights
* improvement: item can now be copied
* improvement: in items list, small icons are displayed in order to quickly copy in clipboard
* improvement: Role rights on folders is now only done using a dynamic matrix
* improvement: online help has been iniated on some admin pages
* improvement: if upgrade needed then auto redirection to upgrade page
* improvement: update is now automatically detected
* fixed: admin users don't have any restriction. Full access is by default set.

v1.55
* improvement: WYSIWYG on description field [3022322]
* fixed: https (SSL) connections to cPassMan (v1.53) webserver... hopefully [3017647]
* fixed: bug in charset format in installation steps

v1.54
* Improvement: FIND page uses a table plugin much more powerfull than previous one
* Improvement: Created table CACHE which permits to get faster results in FIND page
* Improvement: install procedure check several things such as salt key.
* fixed: Page not refreshed after item move [3021358]
* fixed: Passwords not accepted with &-character [3021356]
* fixed: https (SSL) connections to cPassMan (v1.53) webserver... hopefully [3017647]

v1.53
* Improvement: importing big KeePass files [3009848]
* Improvement: Do backup and restore of the database from admin settings page
* Improvement: DB backup can be encrypted [3017095]
* Improvement: Do a clean database and temporary files from admin settings page
* Improvement: treeview is persistent using a cookie
* Minor code improvements (item forms minor changes; one new admin setting)
* Fixed: when deleting a folder, strategy is now to delete all subfolders and associated items.
* Fixed: if provided saltkey is not conform, then warn and stop install
* Fixed: IE refreshing page (on some ajax events) [3017072]

v1.52
* Bug: In one identified case, KeePass XLM file could not be identified as a KP file
* Bug: during update process, table FILES is badly defined

v1.51
* Quick corrections

v1.50
* New functionality: Add pictures and files to Items
* New functionality: Attached images are displayed in a lightbox style
* Improvement: Deleted items can massively be restored or (really) deleted from DB
* New functionality: Import ITEMS to DB can be done using a KeePass xml file

v1.40
* New functionality: Import passwords from CSV file (example from Keepass tool)
* Bug: syntaxe correction in query l89 "roles.php file". Could generate a warning from Apache (depending on warning level set)

v1.30
* Improvement: new option permiting to allow items modification by "Managers".
* Improvement: Managers can't change ADMIN accounts.
* Update jQueryUI library to 1.8 version.
* Some ugly javascript "prompt boxes" have been replaced by nice dialogboxes ;-)
* Reorganized administration settings page with tabs
* New functionality: Renewal period for items
    - Manage a renewal period on each Folder (can be enabled/disabled),
    - Generate a full listing of items expired or to be expired,
    - Items expired are not displayed to users (unless author, admin and manager),
    - Based on paswword creation/modification dates
* New functionality: user can ask to receive password by email
* Improvement: Admin can customized the number of different passwords a user have to user before reusing an old one
* bug: in users page, a mysql error appears when unselect all authorized/forbidden folders

v1.25
* Improvement : new admin setting in order to fix the number of different user passwords before reusing an old one.
* bug : a slash appears before a quote character in some pages.

v1.24
* bug correction: if SALT key exceed 32 characters then ajax queries on items generates errors.
* bug correction: if no "forbidden groups" are detected then a warning is rised by PHP.

v1.23
* bug correction: routine update "personal folders" is not working.
* if login or password is bad then error is displayed after page refreshing

v1.22
* bug correction: if login or password use quotes then identification is impossible.

v1.21
* bug correction: if new item is created in personal folder, then password remains encrypted.
* if setting "personal folder" is disabled, then user can't see its personal folder no more.

v1.20
Compilation of previous 1.20 beta releases.
* Pages presentation is improved

v1.20b2
* Added pages management in logs display
* Uneeded files have been deleted
* Added a button for link copying

v1.20b1
* Inactive menu icons can't be clicked
* jquery 1.4.2 and jquery-ui 1.8rc3 are used
* New language dropdown menu

v1.11
* Added missing new icons in v1.10

v1.11b1
* Request "Restrictions on duplicate names" is implemented
* Request "HTML get request passthrough login" is implemented

v1.10
Compilation of previous 1.10 beta releases with next improvements:
* Tags can be added to Items
* Search functionality uses tags
* New menu on Item page
* Password is masked by default
* Admin can ask for log users connections
* Admin can see a log of errors
* Admin can select date and time format
* Added an help for admin with steps to follow just after install

v1.10b7
LAST BETA BEFORE RELEASE 1.10
* Updated upgrade process
* Previous passwords are displayed in history
* Function "create personal folder if not exists" has been added to admin settings
* Change refresh logging page strategy due to a IE exception (I hate IE)
* Some global small improvements/corrections

v1.10b6
* Updated install and upgrade processes
* Corrected some minor bugs
* Added an help for admin with steps to follow just after install
* Admin can ask for log users connections
* Admin can see a log of errors
* Admin can select date and time format

v1.10b5
* Admin can choose between "fixed" or "contextual" Menu
* Admin can ask for log connections
* Admin settings page has been improved

v1.10b4
* 'database class' has been deployed in all source code
* log for users connection is now activated (under option in admin page)

v1.10b3
* For TAGS edition, an autocomplete system is displayed in order to show and select between the existing tags.

v1.10b2
* Tags are available in Add and Edit item dialogbox
* Tags are listed in "Search" page

v1.10b1
* start working on tags
* change terms for "groups" and "functions"

v1.09
* Bug correction; see https://sourceforge.net/tracker/?func=detail&aid=2941480&group_id=280505&atid=1190333
* Add link to cpassman.org

v1.08
Compilation of previous beta versions.
Main points are:
* New icons menu
* Block "latest items seen"
* "My Favourites" management by user
* "Personal Folder" for users. Specific salt key defined by user (many thanks to Enrique for the idea)
* Spanish translation (many thanks to Enrique)
* Many bugs correction (many thanks to Enrique)
For bugs discovery, please report bug in SourceForge Tracker.

v1.08b12
* improvment of Favourites page
* correction in home latest items block
* icons usage has been homogenized

v1.08b11
* language correction / improvment
* upgrade.php calls specific upgrade_db_1.08.php
* hardcoded language strings in users.php

v1.08b10
* Bug correction : In Groups Management, it shows "racine" instead of "root".
* Bug correction : In Groups Management, when adding a new group, in the combobox of groups, the personal folders are not hidden7
* Bug correction : In Functions Management, when a group is added to the "Allowed", it appears also in "Forbidden"
* Bug correction : In the passwords view, when a password is going to be deleted, in the dialog that shows, to delete you have to push the button "Save"
* Bug correction : lougout can generate a "header redirection error"
* Spanish language still doesn't work => should be a server or browser problem. In debug environment no such problem has been rised. Must be investigated ...

v1.08b9
* New menu based on icons
* Dedicated page for favourites
* Bug when loading out should be corrected
* Bug when entering 1st password should be corrected
* password accept all kings of codes (even html)
* label doesn't accept html codes
* Audit trail is now in all languages => this has a big impact on existing audit trail data. It's mandatory to run file /install/upgrade_db_1.08.php in order to update audit trail data.

v1.08b8
* Correction of a bad initialization of a variable

v1.08b7
* personal folders are hidden in admin pages
* loading spin added on some dialogbox
* improvment on some aspects
* personal salt key is put in SESSION for better usability

v1.08b6
* minor corrections
* spanish new 1.08

v1.08b5
* Personal Folder feature is implemented

v1.08b4
* remains one bug => if new user, pw change doesn't work
* started "Personal Folder"

v1.08b3
* upgrade is corrected
* languages are updated (excepted for Spanish)
* Some minor corrections

v1.08b2 :
* Add "My Favourites"
* Add "Last seen items"
* Change user's password dialogbox
* Add an Admin Settings page
* Add an upgrade file for database changes

v1.08b1
Added Spanish

v 1.07
* Installation is improved. Some warnings are corrected.
* bugs 13, 14, 15 corrected
=> improved administration pages (bugs correction)
=> add item improved with error management
* In general, cpassman is better managed.

v1.07 prep 5
=> Corrected INSTALL page

v1.07 prep 4
=> Corrected INSTALL page
* add a footer
* compliance with W3C

v1.07 prep 3
=> Corrected FUNCTIONS administration page
* new form for creating a new function
* corrected some english translations
* simplified queries

v1.07 prep 2
=> Corrected USERS administration page
* new form for creating a new user
* corrected some english translations
* simplified queries

v1.07 in preparation
* added load.php file that is called by index.php in order to load all generic scripts, css, etc

1.07b1
Correcting some bugs

1.06
- admin information page updated
- still some French hard coded language
- latest items block on home page


