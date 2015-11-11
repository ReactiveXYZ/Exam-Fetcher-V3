<?php

require ('vendor/autoload.php');

require_once ('classes/helper.php');
require_once ('classes/vcaa/controllers/config.php');

use VCAA\controllers\ExamFetchController;
use VCAA\controllers\ExamDownloaderController;

// ================ Post Request Receivers =================

$action = get_post('action');

switch ($action) {

	case 'fetch': {

		$mode_indicator = get_post('mode_indicator');


		if ($mode_indicator == "single") {
			
			$single_paper_checked = isset(get_post('single_paper_checked'));

			$single_report_checked = isset(get_post('single_report_checked'));

			$controller = new ExamFetchController($single_paper_checked,$singleReportChecked,$mode_indicator);

			echo $controller -> fetch_and_construct();

			exit("Single mode data returned");
		}

		if ($mode_indicator == "bulk") {
			
			$bulk_paper_checked = isset(get_post('bulk_paper_checked'));

			$bulk_report_checked = isset(get_post('bulk_report_checked'));

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

			exit('Bulk mode data returned');

		}

		if ($mode_indicator == "extraction") {
			
			$file = REPO_URL.get_post('ext_selected');

			$filename = get_post('ext_subject');

			ExamDownloaderController::download_file_from_server($file,$filename);

		}

	}
		
		break;

	case 'download-zip':{

		$data_to_download = json_decode(get_post('download'),true);

		ExamDownloaderController::download_to_zip($data_to_download);

	}
		
		break;

	case 'single-download': {

		$main_url = "http://www.vcaa.vic.edu.au";

		$url_to_download = $main_url.get_post('download');

		ExamDownloaderController::download_file($url_to_download);

	}
		break;



	default:

		break;
}