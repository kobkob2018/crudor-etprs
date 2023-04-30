<?php
  class Product_images extends TableModel{

    protected static $main_table = 'product_images';
    protected static $select_options = false;

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

        'small_image'=>array(
            'label'=>'תמונה קטנה',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'product/images',
            'name_file'=>'s_img_{{row_id}}.{{ext}}'
        ),

        'image'=>array(
            'label'=>'תמונה גדולה',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'product/images',
            'name_file'=>'img_{{row_id}}.{{ext}}'
        ),

    );
  
}
?>