<?php
# Copyright (C) 2005 - 2006 Thomas Klein <tkl-online@gmx.de>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html


/**
* Extension to counting of working article on a user
*
* Use with:
*
* <useredit>UserName[|UserName]</useredit>
* <useredittopten>[all]|[number]|[]</useredittopten>
* <usercreate [all]>UserName</usercreate>
* <usereditfirst>UserName</usereditfirst>
* <usereditlast>UserName</usereditlast>
*
* @author Thomas Klein <tkl-online@gmx.de>
* @package MediaWiki
* @subpackage Extensions
*/

/**
* Version History
*
* 27.09.2006 1.1.2
*  - Having problems with MySQL 4.0.x fixed
*
* 25.09.2006 1.1.1
*  - Query the first edit date >> sample: <usereditfirst>UserName</usereditfirst>
*  - Query the last edit date >> sample: <usereditlast>UserName</usereditlast>
*
* 17.07.2006 1.1.0
*  - UserCreate is working with MySQL 4.0.x
*
* 17.07.2006 1.0.9
*  - In editcout an error with not user found fixed.
*
* 10.05.2006 1.0.8
*  - For the keyword useredit now several users can indicate, separately through |
*
* 10.05.2006 1.0.7
*  - EditCount sql query and TopTenEditCount sql query adjusted
*
* 04.05.2006 1.0.6
*  - Listing the top ten with count >> sample: <useredittopten>[all] | [number] | []</useredittopten>
*
* 03.04.2006 1.0.5
*  - Listing the top ten of edits
*
* 13.12.2005 1.0.4
*  - Counting the create dokument with namespace
*
* 23.11.2005 1.0.2
*  - Disable caching for pages using this extension
*
* 22.11.2005 1.0.1
*  - Checking the database version of MySQL
*
* 15.11.2005 1.0.0
*  - Release of the first version
*/
if( !defined( 'MEDIAWIKI' ) ) {
  die();
}

require_once( 'Sanitizer.php' );
require_once( 'HttpFunctions.php' );

$wgMySQL40Userright = true; 

$wgExtensionFunctions[] = "wfUserStatistics";
$wgExtensionCredits['parserhook'][] = array(
                                      'name' => 'UserStatistics',
                                      'author' => 'Thomas Klein',
                                      'url' => 'http://www.perrypedia.proc.org/index.php?title=Benutzer:Bully1966/UserStatistics',
                                      'description' => 'Extension to counting of working article on a user',
                                      'version'=>'1.1.2');

function wfUserStatistics() {
  global $wgParser;
  
  $wgParser->setHook( "useredit" , 'counting_useredit' ) ;
  $wgParser->setHook( "useredittopten" , 'counting_useredit_topten' ) ;

  $wgParser->setHook( "usercreate" , 'counting_usercreate' ) ;

  $wgParser->setHook( "usereditfirst" , 'first_useredit' ) ;
  $wgParser->setHook( "usereditlast" , 'last_useredit' ) ;

  //$wgParser->setHook( "usercreate4_0_x" , 'counting_usercreate_4_0_x' ) ;

  return true;
}

function counting_useredit( $text ) {
  global $wgVersion, $wgOut;
  global $wgParser;

  $ret = "" ;
  
  if ( version_compare( $wgVersion, '1.5beta4', '<' ) ) {
    $ret = "1.5.x  of MediaWiki required";
    return $ret ;
  }

  $wgParser->disableCache();

  $totalall = 0;
  // Parse each parameter
  $params = explode('|', $text);
  foreach ($params as $param) 
  {
    list( $username, $namespace ) = extractParamaters( $param );
  
    $username = Title::newFromText( $username );
    $username = is_object( $username ) ? $username->getText() : '';
  
    $uid = User::idFromName( $username );

    if ($uid != 0) {
      global $wgLang;
  
      $total = editsByNumber( $uid );
      $totalall = $totalall + $total;
    } else {
      $total = editsByName( $username );

      if ($uid != $total) {
        $totalall = $totalall + $total;
      } else {
        $totalall = -1;  
        break;
      }
    }
  }
  
  if ($totalall != -1) {
    global $wgLang;

    $ret = $wgLang->formatNum( $totalall );
  } else {
    $ret = "Benutzer nicht bekannt";  
  }

  return $ret ;
}

function counting_useredit_topten( $text ) {
  global $wgVersion, $wgOut, $wgUser, $wgLang;
  global $wgParser;

  $ret = "" ;
  
  if ( version_compare( $wgVersion, '1.5beta4', '<' ) ) {
    $ret = "1.5.x  of MediaWiki required";
    return $ret ;
  }

  $wgParser->disableCache();

  $skin = $wgUser->getSkin();
  
  $ret  = '<ol>';
  $dbr  =& wfGetDB( DB_SLAVE );
  $rev  = $dbr->tableName( 'revision' );

  if (empty( $text )) {
    $limit= "LIMIT 0,11";
  }
  else {
    if (is_numeric( $text )) {
      $text = $text + 1;
      $limit= "LIMIT 0,$text";
    }
    else {
      $limit= "";
    }
  }

  # We fetch 11, even though we want 10, because we *don't* want MediaWiki default (and we might get it)
  $sql  = "SELECT COUNT(*) AS count, rev_user FROM $rev GROUP BY rev_user ORDER BY count DESC $limit";
  $res  = $dbr->query( $sql, "UserStatistics::counting_useredit_topten" );
  
  while( $row = $dbr->fetchObject( $res ) ) {
    if( $row->rev_user != 0 ) {
      $upt  = Title::makeTitle( NS_USER, User::whoIs($row->rev_user) );
      $cpt  = Title::makeTitle( NS_SPECIAL, 'Contributions/' . User::whoIs($row->rev_user) );
      $upl  = $skin->makeLinkObj( $upt, $upt->getText() );
      $tpl  = $skin->makeLinkObj( $upt->getTalkPage(), $wgLang->getNsText( NS_TALK ) );
      $cpl  = $skin->makeKnownLinkObj( $cpt, wfMsgHtml( 'contribslink' ) );
      $uec  =  $wgLang->formatNum( $row->count );
      $ret .= "<li>$upl ($tpl | $cpl) - $uec</li>";
    }
  }
  $ret .= '</ol>';
  
  return( $ret == '<ul></ul>' ? '' : $ret );
}

function first_useredit( $text ) {
  global $wgVersion, $wgOut;
  global $wgParser;

  $ret = "hallo" ;
  
  if ( version_compare( $wgVersion, '1.5beta4', '<' ) ) {
    $ret = "1.5.x  of MediaWiki required";
    return $ret ;
  }

  list( $username, $namespace ) = extractParamaters( $text );

  $username = Title::newFromText( $username );
  $username = is_object( $username ) ? $username->getText() : '';
  
  $uid = User::idFromName( $username );

  if ($uid != 0) {
    $ret = editFirstDate( $uid ); 
  }

  return $ret ;
}

function last_useredit( $text ) {
  global $wgVersion, $wgOut;
  global $wgParser;

  $ret = "" ;
  
  if ( version_compare( $wgVersion, '1.5beta4', '<' ) ) {
    $ret = "1.5.x  of MediaWiki required";
    return $ret ;
  }

  $wgParser->disableCache();

  list( $username, $namespace ) = extractParamaters( $text );

  $username = Title::newFromText( $username );
  $username = is_object( $username ) ? $username->getText() : '';
  
  $uid = User::idFromName( $username );

  if ($uid != 0) {
    $ret = editLastDate( $uid ); 
  }

  return $ret ;
}


function counting_usercreate( $text, $params = array() ) {
  global $wgVersion, $wgOut;
  global $wgParser;

  $ret = "" ;
  
  if ( version_compare( $wgVersion, '1.5beta4', '<' ) ) {
    $ret = "1.5.x  of MediaWiki required";
    return $ret ;
  }

  $dbr =& wfGetDB( DB_SLAVE );
  
  if ( version_compare( $dbr->getServerVersion(), '4.1', '<' ) ) {
    if ( version_compare( $dbr->getServerVersion(), '4.0', '<' ) ) {
      $ret = "4.0 or higher of MySQL required";
      return $ret;
    }
    else {
      if ($wgMySQL40Userright) {
        return counting_usercreate_4_0_x ( $text, $params );
      }
      else {
        $ret = "DBUser need user right DROP and CREATE_TMP_TABLE";
        return $ret;
      }
    }
  }

  list( $username, $namespace ) = extractParamaters( $text );

  $username = Title::newFromText( $username );
  $username = is_object( $username ) ? $username->getText() : '';
  
  $uid = User::idFromName( $username );

  if ($uid != 0) {
    global $wgLang;
  
    if (isset( $params['all'] )) {
      $total = createsByUserAll( $uid );
    }
    else {
      $total = createsByUser( $uid );
    }

    $ret = $wgLang->formatNum( $total );
  } else {
    $ret = "Benutzer nicht bekannt";  
  }

  return $ret ;
}

function counting_usercreate_4_0_x( $text, $params = array() ) {
  global $wgVersion, $wgOut;
  global $wgParser;

  $fname = 'UserStatistics::counting_usercreate_4_0_x';

  $ret = "" ;
  
  if ( version_compare( $wgVersion, '1.5beta4', '<' ) ) {
    $ret = "1.5.x  of MediaWiki required";
    return $ret ;
  }

  $wgParser->disableCache();

  list( $username, $namespace ) = extractParamaters( $text );

  $username = Title::newFromText( $username );
  $username = is_object( $username ) ? $username->getText() : '';
  
  $uid = User::idFromName( $username );

  if ($uid != 0) {
    global $wgLang;
  
    if (isset( $params['all'] )) {
    wfProfileIn( $fname );

    $dbw =& wfGetDB( DB_MASTER );

    $oldignore = $dbw->ignoreErrors( true );

    $old_user_abort = ignore_user_abort( true );

    $usercreatesTable = $dbw->tableName( 'accusercreate' );
    $revision = $dbw->tableName( 'revision' );
    $page = $dbw->tableName( 'page' );

    $dbw->query("CREATE TEMPORARY TABLE $usercreatesTable ENGINE=HEAP ".
                "SELECT MIN(rev_id), rev_user, rev_page FROM $revision GROUP BY rev_page ORDER BY rev_page, rev_timestamp", "UserStatistics::CreateTempTable");
    $sql = "SELECT COUNT(*) AS Counter FROM $usercreatesTable, $page WHERE rev_user = $uid AND rev_page = page_id AND page_is_redirect=0 ORDER BY rev_page";
    $res = $dbw->query( $sql, "UserStatistics::QueryCounting" );
    $dbw->query("DROP TABLE $usercreatesTable", "UserStatistics::DropTempTable");

    $obj = $dbw->fetchObject( $res );
    $nscount = 0;
      if ($obj)  {
        $nscount = $obj->Counter;
      }

      $dbw->freeResult( $res );
      
      $total = $nscount;

      ignore_user_abort( $old_user_abort );

      $oldignore = $dbw->ignoreErrors( $oldignore );
      wfProfileOut( $fname );
    }
    else {
      wfProfileIn( $fname );

      $dbw =& wfGetDB( DB_MASTER );

      $oldignore = $dbw->ignoreErrors( true );

      $old_user_abort = ignore_user_abort( true );

      $usercreatesTable = $dbw->tableName( 'accusercreate' );
      $revision = $dbw->tableName( 'revision' );
      $page = $dbw->tableName( 'page' );

      $dbw->query("CREATE TEMPORARY TABLE $usercreatesTable ENGINE=HEAP ".
                  "SELECT MIN(rev_id), rev_user, rev_page FROM $revision GROUP BY rev_page ORDER BY rev_page, rev_timestamp", "UserStatistics::CreateTempTable");
      $sql = "SELECT COUNT(*) AS Counter FROM $usercreatesTable, $page WHERE rev_user = $uid AND rev_page = page_id AND page_is_redirect=0 AND page_namespace = 0 ORDER BY rev_page";
      $res = $dbw->query( $sql, "UserStatistics::QueryCounting" );
      $dbw->query("DROP TABLE $usercreatesTable", "UserStatistics::DropTempTable");

      $obj = $dbw->fetchObject( $res );
      $nscount = 0;
      if ($obj)  {
        $nscount = $obj->Counter;
      }

      $dbw->freeResult( $res );
      
      $total = $nscount;

      ignore_user_abort( $old_user_abort );

      $oldignore = $dbw->ignoreErrors( $oldignore );
      wfProfileOut( $fname );
    }

    $ret = $wgLang->formatNum( $total );
  } else {
    $ret = "Benutzer nicht bekannt";  
  }

  return $ret ;
}

/**
 * Compute and return the total edits in all namespaces
 *
 * @access private
 *
 * @param array $nscount An associative array
 * @return int
 */
function getTotal( $nscount ) {
  $total = 0;
  foreach ( array_values( $nscount ) as $i )
    $total += $i;

  return $total;
}

/**
 * Parse the username and namespace parts of the input and return them
 *
 * @access private
 *
 * @param string $par
 * @return array
 */
function extractParamaters( $par ) {
  global $wgContLang;
  
  @list($user, $namespace) = explode( '/', $par, 2 );

  // str*cmp sucks
  if ( isset( $namespace ) )
    $namespace = $wgContLang->getNsIndex( $namespace );
  
  return array( $user, $namespace );
}

/**
 * Count the number of edits of a userid
 *
 * @param int $uid The user ID to check
 * @return array
 */
function editsByNumber( $uid ) {
  $fname = 'UserStatistics::editsByNumber';

  $dbr =& wfGetDB( DB_SLAVE );
  $rev  = $dbr->tableName( 'revision' );
  $sql  = "SELECT COUNT(*) AS count FROM $rev WHERE rev_user = $uid";
  $res  = $dbr->query( $sql, $fname );

  $row = $dbr->fetchObject( $res );
  return $row->count;
}

/**
 * First edit of a user
 *
 * @param int $uid The user ID to check
 * @return array
 */
function editFirstDate( $uid ) {
  global $wgLang;

  $ret = "";

  $fname = 'UserStatistics::editFirstDate';

  $dbr =& wfGetDB( DB_SLAVE );
  $rev  = $dbr->tableName( 'revision' );
  $sql  = "SELECT MIN(rev_id) AS number FROM $rev WHERE rev_user = $uid";
  $res  = $dbr->query( $sql, $fname );

  $revid = $dbr->fetchObject( $res );

  if ($revid)  {
    $sql  = "SELECT rev_timestamp FROM $rev WHERE rev_id = $revid->number";
    $res  = $dbr->query( $sql, $fname );

    $row = $dbr->fetchObject( $res );
    if ($row)  {
      $ret = $wgLang->timeanddate( wfTimestamp(TS_MW, $row->rev_timestamp), true);
    }
  }

  return $ret;
}

/**
 * Last edit of a user
 *
 * @param int $uid The user ID to check
 * @return array
 */
function editLastDate( $uid ) {
  global $wgLang;

  $ret = "";

  $fname = 'UserStatistics::editLastDate';

  $dbr =& wfGetDB( DB_SLAVE );
  $rev  = $dbr->tableName( 'revision' );
  $sql  = "SELECT MAX(rev_id) AS number FROM $rev WHERE rev_user = $uid";
  $res  = $dbr->query( $sql, $fname );

  $revid = $dbr->fetchObject( $res );

  if ($revid)  {
    $sql  = "SELECT rev_timestamp FROM $rev WHERE rev_id = $revid->number";
    $res  = $dbr->query( $sql, $fname );

    $row = $dbr->fetchObject( $res );
    if ($row)  {
      $ret = $wgLang->timeanddate( wfTimestamp(TS_MW, $row->rev_timestamp), true);
    }
  }

  return $ret;
}

/**
 * Count the number of edits of a username
 *
 * @param string $usName The username to check
 * @return array
 */
function editsByName( $usName ) {
  $fname = 'UserStatistics::editsByName';
  $nscount = array();

  $dbr =& wfGetDB( DB_SLAVE );
  $rev  = $dbr->tableName( 'revision' );
  $sql  = "SELECT COUNT(*) AS count FROM $rev WHERE rev_user_text = \"$usName\"";
  $res  = $dbr->query( $sql, $fname );

  $row = $dbr->fetchObject( $res );
  return $row->count;
}

/**
 * Count the number of creates of a user 
 *
 * @param int $uid The user ID to check
 * @return array
 */
function createsByUser( $uid ) {
  $fname = 'UserStatistics::createsByUser';

  $db =& wfGetDB( DB_SLAVE );
  $revision = $db->tableName( 'revision' );
  $page = $db->tableName( 'page' );

  $sql = "SELECT COUNT(*) AS Counter FROM (SELECT MIN(rev_id), rev_user, rev_page FROM " .
         "$revision GROUP BY rev_page ORDER BY rev_page, rev_timestamp) AS temp, $page " .
         "WHERE rev_user = $uid AND rev_page = page_id AND page_is_redirect=0 AND page_namespace = 0 ORDER BY rev_page";
  $res = $db->query( $sql, "UserStatistics::createsByUser" );

  $obj = $db->fetchObject( $res );
  $nscount = 0;
  if ($obj)  {
    $nscount = $obj->Counter;
  }

  $db->freeResult( $res );

  return $nscount;
}

/**
 * Count the number of creates of a user 
 *
 * @param int $uid The user ID to check
 * @return array
 */
function createsByUserAll( $uid ) {
  $fname = 'UserStatistics::createsByUser';

  $db =& wfGetDB( DB_SLAVE );
  $revision = $db->tableName( 'revision' );
  $page = $db->tableName( 'page' );

  $sql = "SELECT COUNT(*) AS Counter FROM (SELECT MIN(rev_id), rev_user, rev_page FROM " .
         "$revision GROUP BY rev_page ORDER BY rev_page, rev_timestamp) AS temp, $page " .
         "WHERE rev_user = $uid AND rev_page = page_id AND page_is_redirect=0 ORDER BY rev_page";
  $res = $db->query( $sql, "UserStatistics::createsByUser" );

  $obj = $db->fetchObject( $res );
  $nscount = 0;
  if ($obj)  {
    $nscount = $obj->Counter;
  }

  $db->freeResult( $res );

  return $nscount;
}

?>
