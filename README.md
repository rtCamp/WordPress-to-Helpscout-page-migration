# WordPress-to-Helpscout-page-migration
This script will migrate all the pages from WordPress website to helpscout articles

Script created based on these resources: 

http://developer.helpscout.net/help-desk-api/

http://developer.helpscout.net/docs-api/articles/create/

**How to use this script:**

1. Just download https://github.com/rtCamp/WordPress-to-Helpscout-pages-migration/blob/master/wordpress-to-helpscout-pages-migration.php file.
2. Do require changes in script (e.g database login details, table name).
3. Change the user API key and docs collection key in above file. Replace `xxxxx` with actual keys. User API Key can be found under helpscout -> Manage -> User Name. 
Collection API kay can be found in Collection URL once open the collection where we need to migrate all the pages. 

![all_articles_-_my_docs](https://cloud.githubusercontent.com/assets/1140051/19466494/07fbdb98-9529-11e6-8683-706dd17eb3d4.png)


4. Place this file under your WordPress installation.

5. run `php wordpress-to-helpscout-pages-migration.php`

`Note`: This script is still under progress.
