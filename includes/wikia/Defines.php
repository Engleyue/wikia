<?php
/**
 * A few constants that might be needed in Wikia code
 */

/**
 * @name DB_DPL
 * index for database slave used in heavies queries like DPL. Change to
 * define("DB_DPL", -1);
 * for using all slaves
 */
define("DB_DPL", 3);

/**
 * Defines for Forum namespace
 */
define('NS_FORUM', 110);
define('NS_FORUM_TALK', 111);


/**
 * Defines for Wall namespace
 */

/*
 * wikia page props type
 *
 */

define("WPP_IMAGE_SERVING", 0);
define("WPP_PLB_PROPS", 1);
define("WPP_PLB_LAYOUT_DELETE", 2);
define("WPP_PLB_LAYOUT_NOT_PUBLISH", 3);
define("WPP_BLOGS_VOTING", 4);
define("WPP_BLOGS_COMMENTING", 5);
define("WPP_PLACES_LATITUDE", 6);
define("WPP_PLACES_LONGITUDE", 7);

define("WPP_PLACES_CATEGORY_GEOTAGGED", 9);
//Wall flags
define("WPP_WALL_COUNT", 8);
define("WPP_WALL_ADMINDELETE", 10);
define("WPP_WALL_ARCHIVE", 11);
define("WPP_WALL_ACTIONREASON", 12);
define("WPP_WALL_REMOVE", 13);
