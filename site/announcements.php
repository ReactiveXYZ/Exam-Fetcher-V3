<?php
//require ('../vendor/autoload.php');

use VCAA\db\DatabaseRequest;

/**
* Receive and trigger announcements if any
*/
class Announcements
{
	protected $announcement_connection;

	public function __construct()
	{
		$this->announcement_connection = new DatabaseRequest('posts');
	}

	/**
	 * Receive announcement as HTML
	 **/
	public function receive_announcement()
	{	
		if (isset($_COOKIE['latest_read'])) {
			
			$current_id = $_COOKIE['latest_read'];

			$latest_post = $this->announcement_connection->get_latest_post($current_id);

			return $latest_post;

		}else{

			setcookie('latest_read','-1',time() + (86400 * 365), "/");

			return $this->announcement_connection->get_latest_post(-1);

		}
	}


}

