<?php
require_once "includes/UnihighExamController.php";

$fetcher = new UnihighExamController();

switch ($_POST["action"]){
    case "init_load":{
        echo json_encode($fetcher->obtain_initial_data_list());
    }

        break;
    case "publisher_load":{
        $data = $fetcher->request_data_source_update("subject",$_POST["selected"]);
        echo json_encode($data);
    }
        break;
    case "year_load":{
        $data = $fetcher->request_data_source_update("publisher",$_POST["selected"]);
        echo json_encode($data);
    }
        break;
    case "download_single":{
        $url = $fetcher->request_single_download_url();
        $fetcher->invoke_download($url);
        echo "success";
    }
        break;
    default:
        echo "Request Invalid";
        break;
}

