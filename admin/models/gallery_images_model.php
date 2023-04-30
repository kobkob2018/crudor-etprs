<?php
  class Gallery_images extends TableModel{

    protected static $main_table = 'gallery_images';


    public static $fields_collection = array(

        'priority'=>array(
            'label'=>'מיקום',
            'type'=>'text',
            'default'=>'10',
            'validation'=>'required, int'
        ),

        'label'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>'required'
        ),       

        'description'=>array(
            'label'=>'תיאור קצר',
            'type'=>'textbox',
            'default'=>'1',
            'css_class'=>'small-text'
        ),

        'small_image'=>array(
            'label'=>'תמונה קטנה',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'gallery',
            'name_file'=>'s_img_{{row_id}}.{{ext}}'
        ),

        'image'=>array(
            'label'=>'תמונה גדולה',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'gallery',
            'name_file'=>'img_{{row_id}}.{{ext}}'
        ),

    );
}
?>