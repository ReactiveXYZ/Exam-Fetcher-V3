<?php

require ('../vendor/autoload.php');

require_once ('../classes/helper.php');
require_once ('../classes/vcaa/controllers/config.php');

use VCAA\controllers\ExamFetchController;
use VCAA\controllers\ExamDownloaderController;

// ================ Post Request Receivers =================

$post_action = get_post('action');

switch ($post_action) {

	case 'fetch': {

		$mode_indicator = get_post('mode_indicator');

		if ($mode_indicator == "single") {

			if (isset($_POST['single_paper_checked'])) {

				$single_paper_checked = true;

			}else{

				$single_paper_checked = false;

			}

			if (isset($_POST['single_report_checked'])) {

				$single_report_checked = true;

			}else{

				$single_report_checked = false;
			}
			
			$controller = new ExamFetchController($single_paper_checked,$single_report_checked,$mode_indicator);

			echo $controller -> fetch_and_construct();

			exit();
		}

		if ($mode_indicator == "bulk") {
			
			if (isset($_POST['bulk_paper_checked'])) {

				$bulk_paper_checked = true;

			}else{

				$bulk_paper_checked = false;

			}

			if (isset($_POST['bulk_report_checked'])) {

				$bulk_report_checked = true;

			}else{

				$bulk_report_checked = false;
			}

			$controller = new ExamFetchController($bulk_paper_checked,$bulk_report_checked,$mode_indicator);

			$bulk_subject_textext_array = get_post('bulk_subject');

			$bulk_year_textext_array = get_post('bulk_year');

			$interval_isSet = isset($_POST['from-year']) && isset($_POST['to-year'])?true:false;

			if (interval_isSet) {
				
				$from = get_post('from-year'); $to = get_post('to-year');

				echo $controller->fetch_and_construct($bulk_subject_textext_array,$bulk_year_textext_array,$from,$to);

			}else{

				echo $controller->fetch_and_construct($bulk_subject_textext_array,$bulk_year_textext_array);

			}

			exit();

		}

		if ($mode_indicator == "extraction") {
			
			$file = REPO_URL.'appendices/'.get_post('ext_selected');

			$filename = get_post('ext_subject');

			ExamDownloaderController::download_file_from_server($file,$filename);

		}

	}
		
		break;

	case 'download-zip':{

		$data_to_download = json_decode(get_post('download'),true);

		ExamDownloaderController::download_to_zip($data_to_download);
		//error_log(print_r($data_to_download,true));

	}
		
		break;

	case 'single-download': {

		$main_url = "http://www.vcaa.vic.edu.au";

		$url_to_download = $main_url.get_post('download');

		ExamDownloaderController::download_file($url_to_download);

	}
		break;
    case 'print': {

    	$download_url = strrpos(get_post('url'), '/') + 1;

    	$title = substr(get_post('url'), $download_url,strlen(get_post('url')) - $download_url);

    	$is_copied = ExamDownloaderController::download_file_to_server($download_url,$title,'temp');

    	$response = array();

    	if ($is_copied) {
    		
    		$response = array(
    			'status' => "1",
    			'dir' => "/temp/".$title
    		);
    
    	}else{

    		$response = array(
    			'status' => "0",
    			'dir' => ""
    		);

    	}

    	echo json_encode($response);

    }
    	
    	break;


	default:

		echo "403 Forbidden"; 
		
		break;
}

// ================ Get Request Receivers =================

$get_remote = get_get('remote-download');

if (isset($get_remote)) {
	
	$data = json_decode(base64_decode($get_remote),true);

	ExamDownloaderController::download_to_zip($data);

}












