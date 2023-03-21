<?php
  class MediaController extends CrudController{

    public function upload(){
        $accepted_origins = array("http://usites.com");

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // same-origin requests won't set an origin. If the origin is set, it must be valid.
            if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
              header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            } else {
              header("HTTP/1.1 403 Origin Denied");
              return;
            }
        }
        
        $assets_dir = $this->get_assets_dir();
        $assets_dir_path = $assets_dir['path'];
        $assets_dir_url = $assets_dir['url'];
        $upload_to = 'media/uploads';

        $media_dir_path = $assets_dir_path;
        $upload_to_arr = explode("/",$upload_to);
        
        foreach($upload_to_arr as $dirname){
          $media_dir_path.="/$dirname";
          if( !is_dir($media_dir_path) )
          {
            $oldumask = umask(0) ;
            mkdir( $media_dir_path, 0755 ) ;
            umask( $oldumask ) ;
          }
        }

        $media_dir_url = $assets_dir_url.$upload_to;
        

        
        reset ($_FILES);
        $temp = current($_FILES);
        if (!is_uploaded_file($temp['tmp_name'])){
            // Notify editor that the upload failed
            header("HTTP/1.1 500 Server Error");
            return;
        }

        /*
        If your script needs to receive cookies, set images_upload_credentials : true in
        the configuration and enable the following two headers.
        */
        // header('Access-Control-Allow-Credentials: true');
        // header('P3P: CP="There is no P3P policy."');

        // Sanitize input
        if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
            header("HTTP/1.1 400 Invalid file name.");
            return;
        }

        // Verify extension
        if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "jpeg", "png"))) {
            header("HTTP/1.1 400 Invalid extension.");
            return;
        }



        $file_name = $temp['name'];

        $ext = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));

        $file_pre_name = str_replace(".".$ext, '', $file_name);

        $final_file_name = $this->find_free_file_name($file_pre_name,$ext,$media_dir_path);

        $file_path = $media_dir_path."/".$final_file_name;
        $file_url = $media_dir_url."/".$final_file_name;

        move_uploaded_file($temp['tmp_name'], $file_path);
        echo json_encode(array('location' => $file_url));
        $this->set_layout('blank');

    }

    protected function find_free_file_name($file_pre_name,$ext,$media_dir_path, $try_index = 0){
        if($try_index == 0){
            $file_name = $file_pre_name;
        }
        else{
            $file_name = $file_pre_name."_".$try_index;
        }

        $file_name = $file_name.".".$ext;

        $file_path = $media_dir_path."/".$file_name;

        if(file_exists($file_path)){
            $try_index++;
            return $this->find_free_file_name($file_pre_name,$ext,$media_dir_path, $try_index);
        }
        return $file_name;
    }

    public function librarypopup(){
        $this->set_layout('blank');

        $this->data['meta_title'] = "ספריית המדיה";
        $upload_dir = 'media/uploads';
        

        $assets_dir = Sites::get_user_workon_site_asset_dir();
        $media_dir_path = $assets_dir['path'].$upload_dir;
        $media_dir_url = $assets_dir['url'].$upload_dir;
        $images_in_dir = array();
        if(is_dir($media_dir_path)){
            $files_in_dir = scandir($media_dir_path);
            foreach($files_in_dir as $file_name){
                $file_path = $media_dir_path."/".$file_name;
                if(!is_dir($file_path)){
                    $file_url = $media_dir_url."/".$file_name;
                    $images_in_dir[] = array('name'=>$file_name,'path'=>$file_path,'url'=>$file_url);

                }
            }
        }
        $this->data['library_images'] = $images_in_dir;
        $this->include_view("media_library/popup_window.php");

    }

  }
?>