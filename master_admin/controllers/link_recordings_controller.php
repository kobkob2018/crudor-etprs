<?php
  class Link_recordingsController extends CrudController{

    public function download(){
        $this->set_layout("blank");
        // Download the file
        $link_records_url = get_config("link_records_url");
        $outputName = $link_records_url.$_GET['filename'].".mp3";
        header('Content-Disposition: attachment; filename="call_recording.mp3"');
        header("Content-Type: audio/mpeg3");
        header("Content-Length: " . filesize($outputName));
        echo (file_get_contents($outputName));
        unlink($outputName);
    }
  }

?>