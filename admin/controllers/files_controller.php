<?php
  class FilesController extends CrudController{

    protected function handle_access($action){
        return $this->call_module('admin','handle_access_site_user_is','master_admin');
    }

    public function upload(){
        $accepted_origins = array("http://".$_SERVER['HTTP_HOST'],"https://".$_SERVER['HTTP_HOST']);

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
        $upload_to = 'files/uploads';

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
            SystemMessages::add_err_message("הקובץ לא תקין");
            return $this->redirect_to(inner_url("files/library"));
        }

        /*
        If your script needs to receive cookies, set images_upload_credentials : true in
        the configuration and enable the following two headers.
        */
        // header('Access-Control-Allow-Credentials: true');
        // header('P3P: CP="There is no P3P policy."');

        // Sanitize input
        if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
            SystemMessages::add_err_message("שם הקובץ לא תקין");
            return $this->redirect_to(inner_url("files/library"));
        }

        // Verify extension
        if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "jpeg", "png", "pdf", "mp4", "vid","doc","html"))) {
            SystemMessages::add_err_message("סוג הקובץ אינו נתמך");
            return $this->redirect_to(inner_url("files/library"));
        }



        $file_name = $temp['name'];

        $ext = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));

        $file_pre_name = str_replace(".".$ext, '', $file_name);

        $final_file_name = $this->find_free_file_name($file_pre_name,$ext,$media_dir_path);

        $file_path = $media_dir_path."/".$final_file_name;
        $file_url = $media_dir_url."/".$final_file_name;

        move_uploaded_file($temp['tmp_name'], $file_path);
        SystemMessages::add_success_message("הקובץ עלה בהצלחה: ".$file_url);
        return $this->redirect_to(inner_url("files/library"));
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

    public function library(){
       // $this->set_layout('blank');

        $this->data['meta_title'] = "העלאות קבצים";
        $upload_dir = 'files/uploads';
        

        $assets_dir = Sites::get_user_workon_site_asset_dir();
        $media_dir_path = $assets_dir['path'].$upload_dir;
        $media_dir_url = $assets_dir['url'].$upload_dir;
        $uploads_in_dir = array();
        if(is_dir($media_dir_path)){
            $files_in_dir = scandir($media_dir_path);
            foreach($files_in_dir as $file_name){
                $file_path = $media_dir_path."/".$file_name;
                if(!is_dir($file_path)){
                    $file_url = $media_dir_url."/".$file_name;
                    $uploads_in_dir[] = array('name'=>$file_name,'path'=>$file_path,'url'=>$file_url);

                }
            }
        }
        $this->data['library_files'] = $uploads_in_dir;
        $this->include_view("uploads_library/list.php");

    }


    public function delete_upload(){

        $this->set_layout("blank");
        $upload_url = $_REQUEST['upload'];
        $upload_url = str_replace("/assets_s","assets_s",$upload_url);
        if(file_exists($upload_url)){
            unlink($upload_url);
            SystemMessages::add_success_message("הקובץ נמחק בהצלחה");
        }
        else{
            SystemMessages::add_err_message("הקובץ אינו קיים");
        }
        return $this->redirect_to(inner_url("files/library"));
    }

  }
?>