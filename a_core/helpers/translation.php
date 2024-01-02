<?php
  class Translation {
    private $messages;
    private static $instances = array();

    public function __construct($system_id, $iso_code){
        $this->messages = $this->get_messages_from_json($system_id,$iso_code);
    }

    private function get_messages_from_json($system_id, $iso_code){
        if(!is_dir("languages")){
            return false;
        }
        if(!is_dir("languages/".$system_id)){
            return false;
        }
        $json_file_name = "languages/".$system_id."/".$iso_code.".json";
        if(!file_exists($json_file_name)){
            return false;
        }
        $json_str = file_get_contents($json_file_name);
        $messages = json_decode($json_str,true);
        return $messages;
    }

    public function get_msgstr($msgid){
        if(!$this->messages){
            return $msgid;
        }
        
        if(!isset($this->messages[$msgid])){
            return $msgid;
        }
        return $this->messages[$msgid];
    }

    public static function __translate($instance_id,$system_id,$iso_code,$msgid, $replace_params = array()){
        if(!isset(self::$instances[$instance_id])){
            self::$instances[$instance_id] = new Translation($system_id,$iso_code);
        }
        $translation_instance = self::$instances[$instance_id];

        $msgstr = $translation_instance->get_msgstr($msgid);
        $i = 0;
        foreach($replace_params as $replace){
            $i++;
            $search = '$'.$i;
            $msgstr = str_replace($search,$replace,$msgstr);
        }
        return $msgstr;
    }

  }


?>