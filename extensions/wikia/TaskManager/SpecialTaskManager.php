<?php

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 * @author Krzysztof Krzyżaniak <eloy@wikia.com> for Wikia.com
 * @copyright (C) 2007-2009, Wikia Inc.
 * @licence GNU General Public Licence 2.0 or later
 * @version: $Id: SpecialTaskManager.php 5982 2007-10-02 14:07:24Z eloy $
 */

if ( !defined( 'MEDIAWIKI' ) ) {
    echo "This is MediaWiki extension and cannot be used standalone.\n";
    exit( 1 ) ;
}

$sSpecialPage = "TaskManager";
$wgExtensionCredits['specialpage'][] = array(
	"name" => $sSpecialPage,
	"description" => "Display and manage background tasks",
	"url" => "http://www.wikia.com/",
	"author" => "Krzysztof Krzyżaniak (eloy)"
);

/**
 * add all task which should be visible here
 */
require_once( dirname(__FILE__) . "/BatchTask.php" );
extAddBatchTask( dirname(__FILE__)."/Tasks/CloseWikiTask.php", "closewiki", "CloseWikiTask" );
extAddBatchTask( dirname(__FILE__)."/Tasks/MultiRestoreTask.php", "multirestore", "MultiRestoreTask" );
extAddBatchTask( dirname(__FILE__)."/Tasks/ImageGrabberTask.php", "imagegrabber", "ImageGrabberTask" );
extAddBatchTask( dirname(__FILE__)."/Tasks/ImageImporterTask.php", "imageimporter", "ImageImporterTask" );
extAddBatchTask( dirname(__FILE__)."/Tasks/PageGrabberTask.php", "pagegrabber", "PageGrabberTask" );
extAddBatchTask( dirname(__FILE__)."/Tasks/PageGrabberDumpTask.php", "pagegrabberdump", "PageGrabberDumpTask" );
extAddBatchTask( dirname(__FILE__)."/Tasks/PageImporterTask.php", "pageimporter", "PageImporterTask" );
extAddBatchTask( dirname(__FILE__)."/Tasks/SWMSendToGroupTask.php", "SWMSendToGroup", "SWMSendToGroupTask" );
extAddBatchTask( dirname(__FILE__)."/Tasks/LocalMaintenanceTask.php", "local-maintenance", "LocalMaintenanceTask" );
extAddBatchTask( dirname(__FILE__) ."/Tasks/RebuildLocalisationCacheTask.php", "rebuild_localisation_cache", "RebuildLocalisationCacheTask" );
extAddBatchTask( dirname(__FILE__)."/../AchievementsII/EnableAchievementsTask.php", "enbl-ach", "EnableAchievementsTask" );

/**
 * permissions
 */
$wgAvailableRights[] = 'taskmanager';
$wgGroupPermissions['vstf']['taskmanager'] = true;
$wgGroupPermissions['helper']['taskmanager'] = true;
$wgGroupPermissions['staff']['taskmanager'] = true;

$wgAvailableRights[] = 'taskmanager-action';
$wgGroupPermissions['util']['taskmanager'] = true;
$wgGroupPermissions['util']['taskmanager-action'] = true;

/**
 * message file
 */
$wgExtensionMessagesFiles[ $sSpecialPage ] = dirname(__FILE__) . "/Special{$sSpecialPage}.i18n.php";

extAddSpecialPage( dirname(__FILE__) . "/Special{$sSpecialPage}_body.php", $sSpecialPage, "{$sSpecialPage}Page" );
$wgSpecialPageGroups[$sSpecialPage] = 'wikia';
