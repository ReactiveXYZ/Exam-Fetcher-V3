<?php
namespace VCAA;
class VCAAExamDownloader{

    //Download to zip
    static function downloadToZip($data){
        // set cookie
        setcookie('fileLoading',true,time()+10,'/');

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
}