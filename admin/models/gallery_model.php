<?php
  class Gallery extends TableModel{

    protected static $main_table = 'gallery';
    protected static $select_options = false;

    protected static $auto_delete_from_attached_tables = array(
        'gallery_cat_assign'=>array(
            'table'=>'gallery_cat_assign',
            'id_key'=>'gallery_id'
        )
    ); 

    public static $fields_collection = array(
        'label'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>'required'
        ),
        'active'=>array(
            'label'=>'סטטוס פעיל',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),
    );

    public static $gallery_delete_fields_collection = array(
        'move_images_to'=>array(
            'label'=>'העבר הצעות מחיר לתיקייה',
            'type'=>'select',
            'default'=>'0',
            'options_method'=>array('model'=>'gallery','method'=>'get_select_gallery_options'),
            'validation'=>'required'
        )
    );

    public static function get_fields_collection_for_gallery_delete($delete_gallery_id,$site_id){
        $gallery_options = $gallery_options = array();
        $execute_arr = array(
            'delete_gallery_id'=>$delete_gallery_id,
            'site_id'=>$site_id
        );
        $sql = "SELECT id, label FROM gallery WHERE id != :delete_gallery_id AND site_id = :site_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $gallery_list = $req->fetchAll();
        foreach($gallery_list as $gallery){
            $gallery_options[] = array('value'=>$gallery['id'],'title'=>$gallery['label']);
        }
        $gallery_delete_fields_collection = array(
            'move_images_to'=>array(
                'label'=>'העבר תמונות לגלרייה',
                'type'=>'select',
                'default'=>'0',
                'options'=>$gallery_options,
                'validation'=>'required'
            )
        );
        return $gallery_delete_fields_collection;
    }

    public static function get_select_gallery_options(){
        if(self::$select_options){
            return self::$select_options;
        }
        $gallery_list = self::get_list(array(),'id, label');
        $return_list = array();
        foreach($gallery_list as $gallery){
            $return_list[] = array('value'=>$gallery['id'],'title'=>$gallery['label']);
        }
        self::$select_options = $return_list;
        return self::$select_options;
    }

    public static function get_select_gallery_options_without($gallery_id){
        if(self::$select_options){
            return self::$select_options;
        }
        $gallery_list = self::get_list(array(),'id, label');
        $return_list = array();
        foreach($gallery_list as $gallery){
            $return_list[] = array('value'=>$gallery['id'],'title'=>$gallery['label']);
        }
        self::$select_options = $return_list;
        return self::$select_options;
    }    

    public static function move_images_from_gallery_to($gallery_from,$gallery_to){
        $execute_arr = array(
            'gallery_from'=>$gallery_from,
            'gallery_to'=>$gallery_to
        );
        $sql = "UPDATE gallery_images SET gallery_id = :gallery_to WHERE gallery_id = :gallery_from";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
    }
}
?>