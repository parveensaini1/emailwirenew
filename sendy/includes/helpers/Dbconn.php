<?php

class Dbconn {

	function connect() {
        $mysqli = new mysqli('localhost', 'devsite_emailwr', '}qPUu]rTYE5V', 'devsite_email_wire_gp');
        return $mysqli;
	}
}