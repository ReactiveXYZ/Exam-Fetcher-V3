<?php
require_once ('../../../vendor/autoload.php');
require_once ('../../../classes/helper.php');
require_once ('../ExamFetcherSettings.php');

use VCAA\db\DatabaseRequest;

// Retrieve action variable
$action = get_post('action');

switch ($action) {

	case 'post-announcement': {

		$announcement_conn = new DatabaseRequest('posts');

		$result = $announcement_conn->add_post(get_post('post-content'));

		if ($result === true) {

			echo "success";

		}else{

			echo "failure";

		}

		$announcement_conn->getConnection()->close();

		exit();

	}
		
		break;
	
	case 'refresh':{

		ExamFetcherSettings::refresh_home_cache();

		echo "Success";

		exit();

	}
		break;

	case 'enter-maintanence':{

		$site_option_conn = new DatabaseRequest('site_options');

		if (ExamFetcherSettings::enter_maintenance_mode($site_option_conn)) {
			
			echo "Success";

		}else{

			echo "Failure";

		}

	    $site_option_conn->getConnection()->close();

	    exit();

	}
	
		break;

	default:
		
		echo "503 Forbbiden";

		break;
}
