<?php
/*
 * Copyright 2013 by Allen Tucker. 
 * This program is part of RMHC-Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */

/*
 * Created on Mar 28, 2008
 * @author Oliver Radwan <oradwan@bowdoin.edu>, Sam Roberts, Allen Tucker
 * @version 3/28/2008, revised 7/1/2015
 */

// ONLY REQUIRED FIELDS HAVE BEEN ADDED SO FAR.
class Person {

	private $access_level; // normal user = 1, admin = 2, superadmin = 3


	// REQUIRED FIELDS
	private $id; // (username)
	private $password;
	private $name;
	private $accessLevel;

	/*
	 * This is a temporary mini constructor for testing purposes. It will be expanded later.
	 */
	function __construct($id, $password,$name,$accessLevel){
		$this->id = $id;
		$this->password = $password;
		$this->name = $name;
		// Access level
		$this->access_level = $accessLevel;
	}



	function get_id() {
		return $this->id;
	}

	function get_password() {
		return $this->password;
	}


	function get_name() {
		return $this->name;
	}

	function get_access_level() {
		return $this->access_level;
	}



}
