<?php

class User extends fActiveRecord {
    // Return an iterable set of User objects that are active
    public static function findActive() {
        return fRecordSet::build (
            //'User'
            'User',
            array('active=' => '1'),
            array('date_registered' => 'desc')
        );
    }

	// Check password
	public static function checkPassword($login,$passwd) {
		return fRecordSet::build (
			'User',
			array(	'active=' => 'TRUE',
					'login=' => $login,
					'password='=> $passwd)
		);
	}

}

?>
