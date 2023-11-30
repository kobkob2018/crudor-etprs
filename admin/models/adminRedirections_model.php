<?php
  class AdminRedirections extends TableModel{

    protected static $main_table = 'redirections_301';


    public static $fields_collection = array(

        'label'=>array(
            'label'=>'שם לזיהוי',
            'type'=>'text',
            'validation'=>'required',
            'css_class'=>'big-text'
        ),

        'm_param'=>array(
            'label'=>'סוג הדף (פרמטר ראשי - m)',
            'type'=>'select',
            'options'=>array(
                array('value'=>'ga', 'title'=>'(ga) גלריה או תיקיית גלריות'),
                array('value'=>'pr', 'title'=>'(pr) תיקיית מוצרים'),
                array('value'=>'products', 'title'=>'(products) קטגוריית מוצרים'),               
                array('value'=>'s.pr', 'title'=>'(s.pr) דף מוצר'),
                array('value'=>'link', 'title'=>'(טקסט) דף תוכן\נחיתה'),
                array('value'=>'co', 'title'=>'(co) דף צור קשר'),
                array('value'=>'landing', 'title'=>'דף נחיתה במערכת ישנה (landing.php)'),
                array('value'=>'home', 'title'=>'דף הבית וברירת מחדל'),
            ),
            'validation'=>'required'
        ),

        'id_param'=>array(
            'label'=>'שם הפרמטר המזהה',
            'type'=>'select',
            'options'=>array(
                array('value'=>'sub', 'title'=>'(sub) תיקיית מוצרים\גלריות ,לפי הבחירה בתיבת בחירה'),
                array('value'=>'cat', 'title'=>'(cat) הגלרייה עצמה או קטגוריית מוצרים, לפי הבחירה בכפתורים'),             
                array('value'=>'ud', 'title'=>'(ud) דף מוצר'),               
                array('value'=>'link', 'title'=>'דף תוכן\נחיתה (טקסט)'),
                array('value'=>'ld', 'title'=>'דף נחיתה במערכת ישנה (ld)'),
                array('value'=>'0', 'title'=>'ללא פרמטר נוסף, יש לשים 0 במזהה הפריט'),
            ),
            'validation'=>'required'
        ),
        
        'item_id'=>array(
            'label'=>'מזהה הפריט',
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