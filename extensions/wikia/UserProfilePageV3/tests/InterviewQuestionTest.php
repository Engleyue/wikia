<?php
require_once dirname(__FILE__) . '/../UserProfilePage.setup.php';
wfLoadAllExtensions();

class InterviewQuestionTest extends WikiaBaseTest {

	const TEST_QUESTION_ID = 1;
	const TEST_QUESTION_BODY = 'test question body';
	const TEST_ANSWER_BODY = 'test answer body';
	const TEST_QUESTION_CAPTION = 'test caption';

	/**
	 * @var InterviewQuestion
	 */
	protected $object;

	protected function setUp() {
		$this->object = $this->getMock( 'InterviewQuestion', array( 'getId', 'getDb' ), array(), '', false );

		parent::setUp();
	}

	public function testIncrementingAnswersCount() {
		$dbMock = $this->getMock( 'DatabaseMysql', array( 'update', 'commit' ), array(), '', false );
		$dbMock->expects( $this->once() )
		  ->method( 'update' )
		  ->with(
		      $this->equalTo( 'upp_interview_question' ),
		      $this->equalTo( array( "uiqu_answers_count=uiqu_answers_count+1" ) ),
		      $this->equalTo( array( "uiqu_id" => self::TEST_QUESTION_ID )),
		      $this->equalTo( 'InterviewQuestion::incrAnswersCount' )
		    );

		$dbMock->expects( $this->once() )
		  ->method( 'commit' );

		$this->object->expects( $this->exactly(2) )
		  ->method( 'getId' )
		  ->will( $this->returnValue( self::TEST_QUESTION_ID ) );

		$this->object->expects( $this->once() )
		  ->method( 'getDb' )
		  ->will( $this->returnValue( $dbMock ) );

		$this->object->incrAnswersCount();

		$this->assertEquals( 1, $this->object->getAnswersCount() );
	}

	public function testDecrementingAnswersCount() {
		$dbMock = $this->getMock( 'DatabaseMysql', array( 'update', 'commit' ), array(), '', false );
		$dbMock->expects( $this->once() )
		  ->method( 'update' )
		  ->with(
		      $this->equalTo( 'upp_interview_question' ),
		      $this->equalTo( array( "uiqu_answers_count=uiqu_answers_count-1" ) ),
		      $this->equalTo( array( "uiqu_id" => self::TEST_QUESTION_ID )),
		      $this->equalTo( 'InterviewQuestion::decrAnswersCount' )
		    );

		$dbMock->expects( $this->once() )
		  ->method( 'commit' );

		$this->object->expects( $this->exactly(2) )
		  ->method( 'getId' )
		  ->will( $this->returnValue( self::TEST_QUESTION_ID ) );

		$this->object->expects( $this->once() )
		  ->method( 'getDb' )
		  ->will( $this->returnValue( $dbMock ) );

		$this->object->setAnswersCount( 1 );
		$this->object->decrAnswersCount();

		$this->assertEquals( 0, $this->object->getAnswersCount() );
	}

	public function testConvertingToArray() {
		$this->object->expects( $this->once() )
		  ->method( 'getId' )
		  ->will( $this->returnValue( self::TEST_QUESTION_ID ) );

		$this->object->setBody( self::TEST_QUESTION_BODY );
		$this->object->setAnswerBody( self::TEST_ANSWER_BODY );
		$this->object->setCaption( self::TEST_QUESTION_CAPTION );

		$array = $this->object->toArray();

		$this->assertEquals( self::TEST_QUESTION_ID, $array['id'] );
		$this->assertEquals( self::TEST_QUESTION_BODY, $array['body'] );
		$this->assertEquals( self::TEST_ANSWER_BODY, $array['answerBody'] );
		$this->assertEquals( self::TEST_QUESTION_CAPTION, $array['caption'] );
	}
}