<?php
/**
 * @brief A notification module for gaming wikis that allows users to quickly review a snapshot of upcoming game releases.
 * @author Michał Roszka <michal@wikia-inc.com>
 * @author Will Lee <wlee@wikia-inc.com>
 */
class GamingCalendar {
	private static $ENTRY_PREFIX = 'gamingcalendar-';
	private static $ENTRY_DATE_FORMAT = 'Ymd';
	private static $ENTRY_TITLE_MARKER = '* ';
	private static $ENTRY_ATTRIBUTE_MARKER = '** ';
	private static $ENTRY_SYSTEMS_MARKER = 'SYSTEMS:';
	private static $ENTRY_DESCRIPTION_MARKER = 'DESCRIPTION:';
	private static $ENTRY_IMAGE_MARKER = 'IMAGE:';
	private static $ENTRY_MOREINFO_MARKER = 'MOREINFO:';
	private static $ENTRY_PREORDER_MARKER = 'PREORDER:';

	const CACHE_KEY = 'gamingcal';
	const CACHE_EXPIRY = 2700;

	/**
	 *
	 * @param int $startDate Number of weeks to start at, relative to this week
	 * @param int $weeks Number of weeks to return
	 * @return array of arrays of GamingCalendarEntry
	 */
	public static function loadEntries($offset = 0, $weeks = 2) {
		$oneDay = 86400;

		$entries = array();
		$week = 0;		

		// determine the start of the current week
		if ( date( 'w' ) == 1 ) {
			$thisWeekStart = time();
		} else {
			$thisWeekStart = strtotime( 'last Monday' );
		}

		// adjust date if needed
		$adjustedDate = $thisWeekStart + $offset * 7 * $oneDay;

		$memcKey = wfMemcKey( self::CACHE_KEY, $adjustedDate, $weeks ); 
		$entries = $wgMemc->get( $memcKey );
		if ( !empty( $entries ) ) {
			return $entries;
		}

		$date = $adjustedDate;

		for ( $i = 1; $i <= ( 7 * $weeks ); $i++ ) {
			if ( empty( $entries[$week] ) ) {
				$entries[$week] = array();
			}

			$msgKey = self::getEntryKey( $date );
			$msg = wfMsgForContent($msgKey);
			if (!wfEmptyMsg($msgKey, $msg)) {
				$newEntries = self::parseMessageForEntries($msg, $date);
				if (!empty($newEntries)) {
					$entries[$week] = array_merge( $entries[$week], $newEntries );
				}
			}

			if ( $i % 7 == 0 ) {
				$week++;
			}

			$date = $date + $oneDay;
		}

		$wgMemc->set( $memcKey, $entries, self::CACHE_EXPIRY );

		return $entries;
	}
		
	/**
	 *
	 * @param int $date Unix timestamp
	 * @return string
	 */
	private static function getEntryKey($date) {
		return self::$ENTRY_PREFIX . date(self::$ENTRY_DATE_FORMAT, $date);
	}
	
	/**
	 *
	 * @param string $msg MW message
	 * @return array of GamingCalendarEntry 
	 */
	private static function parseMessageForEntries($msg, $releaseDate) {
		$entries = array();
		
		$entry = new GamingCalendarEntry($releaseDate);
		$lines = explode("\n", $msg);
		foreach ($lines as $line) {
			$line = trim($line);
			if (startsWith($line, self::$ENTRY_TITLE_MARKER)) {
				// found new entry
				
				// first, save old entry
				if ($entry->getGameTitle()) {
					$entries[] = $entry->toArray();
				}
				
				// init new entry
				$entry = new GamingCalendarEntry($releaseDate);
				$entry->setGameTitle( trim( substr($line, strlen(self::$ENTRY_TITLE_MARKER)) ) );
			}
			elseif (startsWith($line, self::$ENTRY_ATTRIBUTE_MARKER)) {
				$attrib = trim( substr($line, strlen(self::$ENTRY_ATTRIBUTE_MARKER)) );
				if (startsWith($attrib, self::$ENTRY_SYSTEMS_MARKER)) {
					$entry->setSystems( explode(',', trim(substr($attrib, strlen(self::$ENTRY_SYSTEMS_MARKER))) ) );
				}
				elseif (startsWith($attrib, self::$ENTRY_DESCRIPTION_MARKER)) {
					$entry->setDescription( trim(substr($attrib, strlen(self::$ENTRY_DESCRIPTION_MARKER))) );
				}
				elseif (startsWith($attrib, self::$ENTRY_IMAGE_MARKER)) {
					$imageParts = explode('|', trim(substr($attrib, strlen(self::$ENTRY_IMAGE_MARKER))) );
					$entry->setImageSrc($imageParts[0]);
					if ($imageParts[1]) {
						$entry->setImageWidth(str_replace('px', '', $imageParts[1]));
					}
				}
				elseif (startsWith($attrib, self::$ENTRY_MOREINFO_MARKER)) {
					$entry->setMoreInfoUrl( trim(substr($attrib, strlen(self::$ENTRY_MOREINFO_MARKER))) );
				}
				elseif (startsWith($attrib, self::$ENTRY_PREORDER_MARKER)) {
					$entry->setPreorderUrl( trim(substr($attrib, strlen(self::$ENTRY_PREORDER_MARKER))) );
				}
			}
		}
		if ($entry->getGameTitle()) {
			$entries[] = $entry->toArray();
		}

		return $entries;
	}
}
