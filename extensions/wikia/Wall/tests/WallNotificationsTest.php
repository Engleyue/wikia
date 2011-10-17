<?php

require_once(dirname(__FILE__) . '/../Wall.setup.php');

class testWallNotifications extends WallNotifications {
	public function addNotificationToData(&$data, $uniqueId, $entityKey, $authorId, $isReply, $read = false) {
		return parent::addNotificationToData(&$data, $uniqueId, $entityKey, $authorId, $isReply, $read);
	}
}

class WallNotificationsTest extends PHPUnit_Framework_TestCase
{
	public function testNotifyEveryoneForMainThread() {
		$wn = $this->getMock('WallNotifications', array('sendEmails','addNotificationLinks'));
		
		$notification = $this->getMock('WallNotificationEntity',array('isMain') );
		
		$notification->data->wall_userid = '123';
		$notification->data->msg_author_id = '567';
		$notification->data->wall_username = 'LoremIpsum';
		$notification->data->title_id = 555;
		
		$notification
			->expects($this->exactly(2))
			->method('isMain')
			->will($this->returnValue(true));

		$wn
			->expects($this->once())
			->method('sendEmails')
			->with($this->anything(), $this->anything(), $this->equalTo(array('123')), $this->equalTo(true), $this->equalTo('123') );
			
		$wn
			->expects($this->once())
			->method('addNotificationLinks')
			->with($this->equalTo(array('123'=>'123')), $this->equalTo($notification));
		
		$wn->notifyEveryone($notification);
	}


	public function testNotifyEveryoneForReply() {
		$wn = $this->getMock('WallNotifications', array('sendEmails','addNotificationLinks','getWatchlist'));
		
		$notification = $this->getMock('WallNotificationEntity',array('isMain') );
		
		$notification->data->wall_userid = '123';
		$notification->data->msg_author_id = '567';
		$notification->data->wall_username = 'LoremIpsum';
		$notification->data->title_id = 555;
		$notification->data_noncached->parent_title_dbkey = 'dbkey';
		
		$notification
			->expects($this->exactly(2))
			->method('isMain')
			->will($this->returnValue(false));

		$users = array('123'=>'123','234'=>'234');
		
		$wn
			->expects($this->once())
			->method('getWatchlist')
			->will($this->returnValue( $users ) );

		$wn
			->expects($this->once())
			->method('sendEmails')
			->with($this->anything(), $this->anything(), $this->equalTo(array('123','234')), $this->equalTo(false), $this->equalTo('123') );
			
		$wn
			->expects($this->once())
			->method('addNotificationLinks')
			->with($this->equalTo( $users ), $this->equalTo($notification));
		
		$wn->notifyEveryone($notification);
	}

	public function someDataProvider() {
		$tests = array();
		
		$uniqueId = 5555;
		$entityKey = '505_212';
		$authorId = 6666;
		$isReply = false;
		$read = false;
		
		$dataS = array(
			'notification' => array(
				0 => 4444
			),
			'relation' => array(
				4444 => array(
					'read' => true,
					'list' => array( array('entityKey' => '404_101', 'authorId' => 6600, 'isReply' => false ) ),
					'last' => 0,
					'count' => 1
				)
			)
		);
		
		
		$dataF = array(
			'notification' => array(
				0 => 4444,
				1 => $uniqueId
			),
			'relation' => array(
				4444 => array(
					'read' => true,
					'list' => array( 0 => array('entityKey' => '404_101', 'authorId' => 6600, 'isReply' => false ) ),
					'last' => 0,
					'count' => 1
				),
				$uniqueId => array(
					'read' => $read,
					'list' => array( 0 => array('entityKey' => $entityKey, 'authorId' => $authorId, 'isReply' => $isReply ) ),
					'last' => 1,
					'count' => 1
				)
			)
		);
		
		$tests[] = array( $uniqueId, $entityKey, $authorId, $isReply, $read, $dataS, $dataF );
		
		$dataS = $dataF;
		
		$entityKey = '404_102';
		
		$dataF = array(
			'notification' => array(
				0 => 4444,
				1 => null,
				2 => $uniqueId
			),
			'relation' => array(
				4444 => array(
					'read' => true,
					'list' => array( 0 => array('entityKey' => '404_101', 'authorId' => 6600, 'isReply' => false ) ),
					'last' => 0,
					'count' => 1
				),
				$uniqueId => array(
					'read' => $read,
					'list' => array( 1 => array('entityKey' => $entityKey, 'authorId' => $authorId, 'isReply' => $isReply ) ),
					'last' => 2,
					'count' => 1
				)
			)
		);		
		
		$tests[] = array( $uniqueId, $entityKey, $authorId, $isReply, $read, $dataS, $dataF );
		
		$authorId2 = 7777;
		$entityKey  = '505_212';
		$entityKey2 = '404_103';
		
		$dataF = array(
			'notification' => array(
				0 => 4444,
				1 => null,
				2 => $uniqueId
			),
			'relation' => array(
				4444 => array(
					'read' => true,
					'list' => array( 0 => array('entityKey' => '404_101', 'authorId' => 6600, 'isReply' => false ) ),
					'last' => 0,
					'count' => 1
				),
				$uniqueId => array(
					'read' => $read,
					'list' => array(
						0 => array('entityKey' => $entityKey,  'authorId' => $authorId,  'isReply' => $isReply ),
						1 => array('entityKey' => $entityKey2, 'authorId' => $authorId2, 'isReply' => $isReply )
					),
					'last' => 2,
					'count' => 2
				)
			)
		);
		
		$tests[] = array( $uniqueId, $entityKey2, $authorId2, $isReply, $read, $dataS, $dataF );
		
		$dataS = $dataF;
		
		$authorId3 = 7778;
		$entityKey3 = '404_104';
		
		$dataF = array(
			'notification' => array(
				0 => 4444,
				1 => null,
				2 => null,
				3 => $uniqueId
			),
			'relation' => array(
				4444 => array(
					'read' => true,
					'list' => array( 0 => array('entityKey' => '404_101', 'authorId' => 6600, 'isReply' => false ) ),
					'last' => 0,
					'count' => 1
				),
				$uniqueId => array(
					'read' => $read,
					'list' => array(
						0 => array('entityKey' => $entityKey,  'authorId' => $authorId,  'isReply' => $isReply ),
						1 => array('entityKey' => $entityKey2, 'authorId' => $authorId2, 'isReply' => $isReply ),
						2 => array('entityKey' => $entityKey3, 'authorId' => $authorId3, 'isReply' => $isReply )
					),
					'last' => 3,
					'count' => 3
				)
			)
		);		
		
		$tests[] = array( $uniqueId, $entityKey3, $authorId3, $isReply, $read, $dataS, $dataF );
		
		$dataS = $dataF;
		
		$authorId4 = 7779;
		$entityKey4 = '404_105';
		
		$dataF = array(
			'notification' => array(
				0 => 4444,
				1 => null,
				2 => null,
				3 => null,
				4 => $uniqueId
			),
			'relation' => array(
				4444 => array(
					'read' => true,
					'list' => array( 0 => array('entityKey' => '404_101', 'authorId' => 6600, 'isReply' => false ) ),
					'last' => 0,
					'count' => 1
				),
				$uniqueId => array(
					'read' => $read,
					'list' => array(
						0 => array('entityKey' => $entityKey2, 'authorId' => $authorId2, 'isReply' => $isReply ),
						1 => array('entityKey' => $entityKey3, 'authorId' => $authorId3, 'isReply' => $isReply ),
						2 => array('entityKey' => $entityKey4, 'authorId' => $authorId4, 'isReply' => $isReply )
					),
					'last' => 4,
					'count' => 4
				)
			)
		);		
		
		$tests[] = array( $uniqueId, $entityKey4, $authorId4, $isReply, $read, $dataS, $dataF );
		
		$dataS = $dataF;
		
		$entityKey5 = '404_106';
		
		$dataF = array(
			'notification' => array(
				0 => 4444,
				1 => null,
				2 => null,
				3 => null,
				4 => null,
				5 => $uniqueId
			),
			'relation' => array(
				4444 => array(
					'read' => true,
					'list' => array( 0 => array('entityKey' => '404_101', 'authorId' => 6600, 'isReply' => false ) ),
					'last' => 0,
					'count' => 1
				),
				$uniqueId => array(
					'read' => $read,
					'list' => array(
						0 => array('entityKey' => $entityKey2, 'authorId' => $authorId2, 'isReply' => $isReply ),
						1 => array('entityKey' => $entityKey3, 'authorId' => $authorId3, 'isReply' => $isReply ),
						3 => array('entityKey' => $entityKey5, 'authorId' => $authorId4, 'isReply' => $isReply )
					),
					'last' => 5,
					'count' => 4
				)
			)
		);		
		
		$tests[] = array( $uniqueId, $entityKey5, $authorId4, $isReply, $read, $dataS, $dataF );		
		
		return $tests;
	}
	/**
	 * @dataProvider someDataProvider
	 */
	public function testAddNotificationToData($uniqueId, $entityKey, $authorId, $isReply, $read, $dataS, $dataF) {
		$wn = new testWallNotifications();
		
		$wn->addNotificationToData($dataS, $uniqueId, $entityKey, $authorId, $isReply, $read);
		
		$this->assertEquals($dataS, $dataF);
	}
	
	
	
}

?>