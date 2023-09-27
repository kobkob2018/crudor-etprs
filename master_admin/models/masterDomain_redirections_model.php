<?php
  class MasterDomain_redirections extends TableModel{

    protected static $main_table = 'domain_redirections_301';


    public static $fields_collection = array(

        'label'=>array(
            'label'=>'שם לזיהוי',
            'type'=>'text',
            'validation'=>'required',
            'css_class'=>'big-text'
        ),
        
        'domain'=>array(
            'label'=>'דומיין',
            'type'=>'text',
            'validation'=>'required'
        ),

        'url'=>array(
            'label'=>'כתובת הפנייה',
            'type'=>'text',
            'validation'=>'required'
        ),

    );
}
?>