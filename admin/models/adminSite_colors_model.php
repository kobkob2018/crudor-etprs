<?php
  class AdminSite_colors extends TableModel{

    protected static $main_table = 'site_colors';


    public static $fields_collection = array(

        'text-color'=>array(
            'label'=>'צבע טקסט כללי',
            'type'=>'text',
            'css_class'=>'color-text left-text',
        ),

        'a-color'=>array(
            'label'=>'צבע לינק',
            'type'=>'text',
            'css_class'=>'color-text left-text',
        ),

        'top-fix-bg'=>array(
            'label'=>'רקע תפריט עליון',
            'type'=>'text',
            'css_class'=>'color-text left-text',
        ),
        'top-fix-color'=>array(
            'label'=>'צבע לינק וטקסט תפריט עליון',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'footer-bg'=>array(
            'label'=>'רקע פוטר',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'footer-color'=>array(
            'label'=>'צבע לינק וטקסט בפוטר',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'top-fix-link-hover'=>array(
            'label'=>' צבע לינק תפריט עליון במעבר עכבר',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'header-mid-bg'=>array(
            'label'=>'רקע האדר',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'header-mid-color'=>array(
            'label'=>'צבע טקסט האדר',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'big-title-bg'=>array(
            'label'=>'רקע כותרות מודגשות',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),
        'big-title-color'=>array(
            'label'=>'צבע טקסט כותרות מודגשות',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'title-color'=>array(
            'label'=>'צבע כותרת בגוף הטקסט',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'b-color'=>array(
            'label'=>'צבע טקסט מודגש',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'button-bg'=>array(
            'label'=>'רקע כפתור',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'button-color'=>array(
            'label'=>'צבע כפתור',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'button-hover-bg'=>array(
            'label'=>'רקע כפתור במעבר עכבר',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'button-hover-color'=>array(
            'label'=>'צבע כפתור במעבר עכבר',
            'type'=>'text',
            'css_class'=>'color-text left-text'
        ),

        'site_css'=>array(
            'label'=>'css חפשי',
            'type'=>'textbox',
            'css_class'=>'left-text'
        ),

    );

    public static $asset_mapping = array(
        'colors_css_file'=>'style'
    );

    public static function get_site_colors($site_id){
        $execute_arr = array('site_id'=>$site_id);
        $sql = "SELECT * FROM site_colors WHERE site_id = :site_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetchAll();
        $colors_arr = array();
        if($result && !empty($result)){
            foreach($result as $color){
                $colors_arr[$color['label']] = $color['color'];
            }
        }
        $return_arr = array('id'=>$site_id);
        
        foreach(self::$fields_collection as $field_key=>$field){
            $color = "";
            if(isset($colors_arr[$field_key])){
                $color = $colors_arr[$field_key];
            }
            $return_arr[$field_key] = $color;
        }

        $site_css = "";
        $sql = "SELECT * FROM site_css WHERE site_id = :site_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetchAll();
        if($result && isset($result[0])){
            $site_css = $result[0]['css'];
        }
        $return_arr['site_css'] = $site_css;
        return $return_arr;
    }


    public static function update_site_colors($site_id,$update_values){
        if(empty($update_values)){
            return;
        }
        $db = Db::getInstance();
        $execute_arr = array('site_id'=>$site_id);
        $sql = "DELETE FROM site_colors WHERE site_id = :site_id";		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);


        $color_values_sql_arr = array();
        $site_css = false;
        foreach($update_values as $color_key=>$color){
            if($color_key != 'site_css'){

                $color_values_sql_arr[] = "(:site_id, '$color_key', '$color')";
            }
            else{
                $site_css = $color;
            }
        }


        $color_values_sql = implode(",",$color_values_sql_arr);
        $sql = "INSERT INTO site_colors (site_id, label, color) VALUES $color_values_sql";
        $req = $db->prepare($sql);
        $req->execute($execute_arr);


        if($site_css){

            $sql = "DELETE FROM site_css WHERE site_id = :site_id";		
            $req = $db->prepare($sql);
            $req->execute($execute_arr);
            
            $execute_arr['site_css'] = $site_css;
            
            $sql = "INSERT INTO site_css (site_id, css) VALUES (:site_id, :site_css)";
            $req = $db->prepare($sql);
            $req->execute($execute_arr);
        }

    }
}
?>