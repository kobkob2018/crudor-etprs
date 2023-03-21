<?php
  class View {

    protected Controller $controller;
    public function __construct(Controller $controller_interface){
      $this->controller = $controller_interface;
    }

    public function a_class($relative_url){
        if($this->routing_is($relative_url)){
            return "a-selected";
        }
        return "a-simpel";
    }

    public function a_c_class($controllers_check){
      $controller_check_arr = explode(",",$controllers_check);
      foreach($controller_check_arr as $controller_check){
        $controller_check = trim($controller_check);
        if($this->controller_is($controller_check)){
          return "c-selected";
        }
      }
      return "c-simpel";
    }

    public function controller_is($controller_check){
      global $controller;
      if($controller_check == $controller){
          return true;
      }
      return false;
    }    

    public function list_url(){
      global $controller;
      return inner_url("$controller/list/");
    }

    public function action_url(){
      global $controller;
      global $action;
      return inner_url("$controller/$action/");
    }

    public function routing_is($relative_url){
        global $controller,$action;
        $current_relative_url = "$controller/$action/";
        if($relative_url == $current_relative_url){
            return true;
        }
        return false;
    }

    public function user_is($needed_roll){
      $user = $this->controller->user;
      $work_on_site = false;
      $data = $this->controller->data;
      if(isset($data['work_on_site'])){
        $work_on_site = $data['work_on_site'];
      }
      return Helper::user_is($needed_roll,$user,$work_on_site);
    }

    public function site_user_is($needed_roll){
      $work_on_site = false;
      $data = $this->controller->data;
      if(isset($data['work_on_site'])){
        $work_on_site = $data['work_on_site'];
      }
      if(!$work_on_site){
        return false;
      }
      return self::user_is($needed_roll);
    }


    public function get_video_embed_type($file_name){
      $video_types = array(
        "webm"=>"video/webm", 
        "mp4"=>"video/mp4", 
        "ogv"=>"video/ogg",
        "ogg"=>"video/ogg"
      );
      $ext = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
      if(isset($video_types[$ext])){
        return $video_types[$ext];
      }
      return $video_types['mp4'];
    }
}

?>