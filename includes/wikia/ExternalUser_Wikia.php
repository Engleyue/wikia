<?php

class ExternalUser_Wikia extends ExternalUser {
	private $mRow, $mDb, $mUser;

	protected function initFromName( $name ) {
		wfDebug( __METHOD__ . ": init User from name: $name \n" );
		$name = User::getCanonicalName( $name, 'usable' );

		if ( !is_string( $name ) ) {
			return false;
		}

		return $this->initFromCond( array( 'user_name' => $name ) );
	}

	protected function initFromId( $id ) {
		wfDebug( __METHOD__ . ": init User from id: $id \n" );
		return $this->initFromCond( array( 'user_id' => $id ) );
	}

	protected function initFromUser( $user ) {
		wfDebug( __METHOD__ . ": init User from object: " . print_r($user, true) ." \n" );
		$this->mUser = $user;
		return $this->initFromCond( array( 'user_id' => $user->getId() ) );
	}

	private function initFromCond( $cond ) {
		global $wgExternalSharedDB;

		wfDebug( __METHOD__ . ": init User from cond: " . print_r($cond, true) . " \n" );

		$this->mDb = wfGetDB( DB_MASTER, array(), $wgExternalSharedDB );

		$row = $this->mDb->selectRow(
			'`user`',
			array( '*' ),
			$cond,
			__METHOD__
		);
		if ( !$row ) {
			return false;
		}
		$this->mRow = $row;

		return true;
	}

	public function initFromCookie() {
		global $wgMemc,$wgDBcluster;
		wfDebug( __METHOD__ . " \n" );

        if ( wfReadOnly() ) {
			wfDebug( __METHOD__ . ": Cannot load from session - DB is running with the --read-only option " );
            return false;
        }

		$uid = @$_SESSION['wsUserID'];
		wfDebug( __METHOD__ . ": user from session: $uid \n" );
		if ( empty($uid) ) {
			return false;
		}

		// exists on central
		$this->initFromId( $uid );

		// exists on local
		$User = null;
		if ( !empty($this->mRow) ) {
			$memkey = sprintf("extuser:%d:%s", $this->getId(), $wgDBcluster);
			$user_touched = $wgMemc->get( $memkey );
			if ( $user_touched != $this->getUserTouched() ) {
				$_key = wfSharedMemcKey( "user_touched", $this->getId() );
				wfDebug ( __METHOD__ . ": user touched is different on central and $wgDBcluster \n" );
				wfDebug ( __METHOD__ . ": clear $_key \n" );
				$wgMemc->set( $memkey, $this->getUserTouched() );
				$wgMemc->delete( $_key );
			} else {
				$User = $this->getLocalUser();
			}
		}

		wfDebug( __METHOD__ . ": return user object \n" );
		return is_null( $User );
	}

	public function getId() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_id . " \n" );
		return $this->mRow->user_id;
	}

	public function getName() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_name . " \n" );
		return $this->mRow->user_name;
	}

	public function getEmail() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_email . " \n" );
		return $this->mRow->user_email;
	}

	public function getEmailAuthentication() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_email_authenticated . " \n" );
		return $this->mRow->user_email_authenticated;
	}

	public function getUserTouched() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_touched . " \n" );
		return $this->mRow->user_touched;
	}

	public function getRealName() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_real_name . " \n" );
		return $this->mRow->user_real_name;
	}

	public function getPassword() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_password . " \n" );
		return $this->mRow->user_password;
	}

	public function getNewPassword() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_newpassword . " \n" );
		return $this->mRow->user_newpassword;
	}

	public function getOptions() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_options . " \n" );
		return $this->mRow->user_options;
	}

	public function getToken() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_token . " \n" );
		return $this->mRow->user_token;
	}

	public function getEmailToken() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_email_token . " \n" );
		return $this->mRow->user_token;
	}

	public function getEmailTokenExpires() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_email_token_expires . " \n" );
		return $this->mRow->user_email_token_expires;
	}

	public function getRegistration() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_registration . " \n" );
		return $this->mRow->user_registration;
	}

	public function getNewpassTime() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_newpass_time . " \n" );
		return $this->mRow->user_newpass_time;
	}

	public function getEditCount() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_editcount . " \n" );
		return $this->mRow->user_editcount;
	}

	public function getBirthdate() {
		wfDebug( __METHOD__ . ": " . $this->mRow->user_birthdate . " \n" );
		return $this->mRow->user_birthdate;
	}

	public function authenticate( $password ) {
		# This might be wrong if anyone actually uses the UserComparePasswords hook
		# (on either end), so don't use this if you those are incompatible.
		wfDebug( __METHOD__ . ": " . $this->getId() . " \n" );
		return User::comparePasswords( $this->getPassword(), $password, $this->getId() );
	}

	public function getPref( $pref ) {
		# we are using user_properties table - so no action is needed here
		wfDebug( __METHOD__ . " \n" );
		return null;
	}

	protected function addToDatabase( $User, $password, $email, $realname ) {
		global $wgExternalSharedDB;

		wfDebug( __METHOD__ . ": add user to the $wgExternalSharedDB database: " . $User->getName() . " \n" );

		$dbw = wfGetDB( DB_MASTER, array(), $wgExternalSharedDB );
		$seqVal = $dbw->nextSequenceValue( 'user_user_id_seq' );

		$User->setPassword( $password );
		$User->setToken();

		$dbw->insert( '`user`',
			array(
				'user_id' => $seqVal,
				'user_name' => $User->mName,
				'user_password' => $User->mPassword,
				'user_newpassword' => $User->mNewpassword,
				'user_newpass_time' => $dbw->timestamp( $User->mNewpassTime ),
				'user_email' => $email,
				'user_email_authenticated' => $dbw->timestampOrNull( $User->mEmailAuthenticated ),
				'user_real_name' => $realname,
				'user_options' => '',
				'user_token' => $User->mToken,
				'user_registration' => $dbw->timestamp( $User->mRegistration ),
				'user_editcount' => 0,
				'user_birthdate' => $User->mBirthDate
			), __METHOD__
		);
		$User->mId = $dbw->insertId();

		// Clear instance cache other than user table data, which is already accurate
		$User->clearInstanceCache();

		return $User;
	}

	/**
	 * linkToLocal -- link central account to local account on every cluster
	 *
	 * @param Integer $id -- user identifier in user table on central database
	 *
	 * @author Piotr Molski (moli) <moli@wikia-inc.com>
	 */
	public function linkToLocal( $id ) {

		if( is_array( $this->mRow ) ) {

			wfProfileIn( __METHOD__ );

			wfDebug( __METHOD__ . ": update local user table: $id \n" );
			$dbw = wfGetDB( DB_MASTER );

			$where = array();
			foreach ( $this->mRow as $field => $value ) {
				$where[ $field ] = $value;
			}

			$dbw->replace(
				'user',
				array_keys( (array)$this->mRow ),
				$where,
				__METHOD__
			);

			wfProfileOut( __METHOD__ );
		}
	}

	public function getLocalUser() {
		$uid = $this->getId();
		wfDebug( __METHOD__ . ": get local user: $uid \n" );

		$dbr = wfGetDb( DB_SLAVE );
		$row = $dbr->selectRow(
			'user',
			'*',
			array( 'user_id' => $uid )
		);
		return $row ? User::newFromId( $row->user_id ) : null;
	}

	public function updateUser() {
		global $wgExternalSharedDB;
		wfDebug( __METHOD__ . ": update central user data \n" );

		$dbw = wfGetDB( DB_MASTER, array(), $wgExternalSharedDB );
		$this->mUser->mTouched = User::newTouchedTimestamp();
		$dbw->update( '`user`',
			array( /* SET */
				'user_name' => $this->mUser->mName,
				'user_password' => $this->mUser->mPassword,
				'user_newpassword' => $this->mUser->mNewpassword,
				'user_newpass_time' => $dbw->timestampOrNull( $this->mUser->mNewpassTime ),
				'user_real_name' => $this->mUser->mRealName,
		 		'user_email' => $this->mUser->mEmail,
		 		'user_email_authenticated' => $dbw->timestampOrNull( $this->mUser->mEmailAuthenticated ),
				'user_options' => '',
				'user_touched' => $dbw->timestamp( $this->mUser->mTouched ),
				'user_token' => $this->mUser->mToken,
				'user_email_token' => $this->mUser->mEmailToken,
				'user_email_token_expires' => $dbw->timestampOrNull( $this->mUser->mEmailTokenExpires ),
			), array( /* WHERE */
				'user_id' => $this->mUser->mId
			), __METHOD__
		);
	}
}
