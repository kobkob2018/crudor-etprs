<?php
  class Portal_user extends TableModel{

    protected static $main_table = 'portal_user';


    public static $fields_collection = array(

        'label'=>array(
            'label'=>'כותרת ראשית לדפי הפורטל',
            'type'=>'text',
            'validation'=>'required'
        ),       
        'status'=>array(
            'label'=>'פורטל פעיל',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),
        'logo'=>array(
            'label'=>'תמונה',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'100000',
            'upload_to'=>'portal',
            'assets_dir'=>'master',
            'name_file'=>'logo_{{row_id}}.{{ext}}'
        ),
        'link'=>array(
            'label'=>'כתובת קישור לדף הלקוח',
            'type'=>'text'
        ),
        'phone'=>array(
            'label'=>'טלפון שיופיע בהצעות המחיר',
            'type'=>'text'
        ),
        'city_name'=>array(
            'label'=>'שם עיר',
            'type'=>'text'
        ),
        
    );
}
?>