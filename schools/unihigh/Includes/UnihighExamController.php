<?php

require_once "db_conn.php";

class UnihighExamController{

    private $conn;

    //Constructor
    public function __construct(){
        //establish mysql connection
        $this->conn = new mysqli(dbConfig::server,dbConfig::username,dbConfig::password,dbConfig::dbname);

        if ($this->conn->connect_error){
            die("Connection failed with error".$this->conn->connect_error);
        }
    }

    //Retrieve source for auto complete
    public function obtain_initial_data_list(){
        $result = $this->conn->query("SELECT subject FROM exam_records");
        if ($result->num_rows > 0){
            //source data
            $output = array();
            while ($row = $result->fetch_assoc()){
                array_push($output,$row['subject']);
            }
            return $output;
        }else{
            return ["No subjects available"];
        }
    }

    //Retrieve data for a specific item
    public function request_data_source_update($type,$selected){
        //Load based on type first and load list to the next unlocked field
        switch ($type){
            case "subject":{
                $query = "SELECT publisher FROM exam_records WHERE subject='$selected'";
                $result = $this->conn->query($query);
                if ($result->num_rows > 0){
                    $output = array();
                    while ($row = $result->fetch_assoc()){
                        array_push($output,$row['publisher']);
                    }
                    return $output;
                }else{
                    return ["No publisher for this subject available"];
                }
            }
                //unlock publisher
                break;
            case "publisher":{
                $selected_bundle_unpacked = json_decode($_POST['selected'],true);
                $current_subj = $selected_bundle_unpacked['subject'];
                $current_pub = $selected_bundle_unpacked['publisher'];
                $query = "SELECT year FROM exam_records WHERE subject='$current_subj' AND publisher='$current_pub'";
                $result = $this->conn->query($query);
                if ($result->num_rows > 0){
                    $output = array();
                    while ($row = $result->fetch_assoc()){
                        array_push($output,$row['year']);
                    }
                    return $output;
                }else{
                    return ["No year for this publisher available"];
                }
            }
                //unlock year
                break;
            case "year":{
                $selected_bundle_unpacked = json_decode($_POST['selected'],true);
                $current_subj = $selected_bundle_unpacked['subject'];
                $current_pub = $selected_bundle_unpacked['publisher'];
                $current_year = $selected_bundle_unpacked['year'];
                $query = "SELECT file_path FROM exam_records WHERE subject='$current_subj' AND publisher='$current_pub' AND year='$current_year'";
                $result = $this->conn->query($query);
                if ($result->num_rows == 1){
                    $row = $result->fetch_assoc();
                    UnihighExamDownloader::downloadFile($row['file_path']);
                }else{
                    return "error";
                }
            }
                //initialize download
                break;
            default:
                break;
        }
    }

    public function request_single_download_url(){
        $current_subj = $_POST['subject_text'];
        $current_pub = $_POST['publisher_text'];
        $current_year = $_POST['year_text'];
        $query = "SELECT file_path FROM exam_records WHERE subject='$current_subj' AND publisher='$current_pub' AND year='$current_year'";
        $result = $this->conn->query($query);
        if ($result->num_rows == 1){
            $row = $result->fetch_assoc();
            return $row['file_path'];
        }else{
            return "error";
        }
    }

    //Formulate data and invoke for download
    public function invoke_download($source){
        if (is_array($source)){
            //initialize bulk download
            UnihighExamDownloader::downloadToZip($source);
        }else{
            //just a single url
            UnihighExamDownloader::downloadFileFromServer($source);
        }

    }

}

class UnihighExamDownloader{
    //Download to zip
    static function downloadToZip($data){
        // set cookie
        setcookie('unihigh_download',true,time()+10,'/');

        //Create ZipArchive using Stream
        $zipStream = new ZipStream('exams.zip');
        foreach ($data as $item){
            //Add files
            $filesInDirectory = $item["value"];
            foreach ($filesInDirectory as $file){
                //Download file
                $downloaded_file = file_get_contents($file);
                //Add to zip
                $zipStream -> add_file($item["key"]."/".basename($file),$downloaded_file);
            }
        }
        $zipStream->finish();
        //Clean up
        ob_clean();
        flush();
    }

    //Download a single file
    static function downloadFile($url){
        set_time_limit(0);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $r = curl_exec($ch);
        curl_close($ch);
        header('Expires: 0'); // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
        header('Cache-Control: private', false);
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename="' . basename($url) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($r)); // provide file size
        header('Connection: close');
        echo $r;
    }

    static function downloadFileToServer($url,$filename,$fileDir){
        if (file_exists($fileDir.'/'.$filename)){
            return true;
        }
        $file = file_get_contents($url);
        $fo = fopen($fileDir.'/'.$filename,'w');
        fwrite($fo,$file);
        fclose($fo);
        return true;
    }

    static function downloadFileFromServer($file){
        $file = $_SERVER['DOCUMENT_ROOT']."/schools/unihigh/repository/".$file;

        if(!$file){ // file does not exist
            die('file not found');
        } else {
            chmod($file,0777);
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$file");
            header("Content-Type: application/force-download");
            header("Content-Transfer-Encoding: binary");
            // read the file from disk
            readfile($file);
        }
    }
}
