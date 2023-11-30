<?php
  class Site_users extends TableModel{

    protected static $main_table = 'user_sites';

    public static function get_site_users_list($site_id){
        $filter_arr = array('site_id'=>$site_id);
        return self::simple_get_list_by_table_name($filter_arr, 'user_sites');
    }

    public static $fields_collection = array(
        'user_id'=>array(
            'label'=>'שיוך למשתמש',
            'type'=>'select',
            'options_method'=>array('model'=>'site_users','method'=>'get_select_user_options'),
            'validation'=>'required',
        ),
        'status'=>array(
            'label'=>'סטטוס',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא פעיל'),
                array('value'=>'1', 'title'=>'פעיל')
            ),
            'validation'=>'required'
        ),
        'roll'=>array(
            'label'=>'תפקיד',
            'type'=>'select',
            'default'=>'admin',
            'options'=>array(
                array('value'=>'author', 'title'=>'כותב'),
                array('value'=>'admin', 'title'=>'מנהל'),
                array('value'=>'master_admin', 'title'=>'מנהל כל'),
            ),
            'validation'=>'required'
        ),       

        'user_can'=>array(
            'label'=>'הרשאות כותב',
            'type'=>'build_method',
            'build_method'=>'build_user_can_options',
            'default'=>'0'
        ), 

    ); 

    public static $user_can_options = array(
        array('value'=>'pages', 'title'=>'עמודים'),
        array('value'=>'products', 'title'=>'מוצרים'),
        array('value'=>'gallery', 'title'=>'גלריות'),
        array('value'=>'quotes', 'title'=>'הצעות מחיר'),
        array('value'=>'forms', 'title'=>'טופס בדף'),
        array('value'=>'menus', 'title'=>'תפריט פורטל'),
    );

    public static function get_user_can($site_id,$user_id){
        $user_can = self::simple_get_list_by_table_name(array('site_id'=>$site_id,'user_id'=>$user_id),'site_user_can');
        $user_can_permissions = array();
        if(!$user_can){
            $user_can = array();
        }
        foreach($user_can as $permission){
            $user_can_permissions[$permission['permission_to']] = '1';
        }
        return $user_can_permissions;
    }

    public static function update_user_can($site_user_id,$user_id,$site_id,$user_can_options){
        $sql = "DELETE FROM site_user_can WHERE user_id = :user_id AND site_id = :site_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute(array('user_id'=>$user_id,'site_id'=>$site_id));
        foreach($user_can_options as $key=>$option){
            $execute_arr = array(
                'site_user_id'=>$site_user_id,
                'user_id'=>$user_id,
                'site_id'=>$site_id,
                'permission_to'=>$key
            );
            $sql = "INSERT INTO site_user_can (site_user_id, user_id, site_id, permission_to) VALUES(:site_user_id, :user_id, :site_id, :permission_to)";		
            $req = $db->prepare($sql);
            $req->execute($execute_arr);
        }
        return;
    }


    public static function get_site_users_that_can($site_id,$permission_to){
        $filter_arr = array('site_id'=>$site_id,'status'=>'1');
        $site_users = self::get_list($filter_arr);
        $return_arr = array();
        foreach($site_users as $site_user){
            $user = Users::get_by_id($site_user['user_id']);
            $user['roll'] = $site_user['roll'];
            if($site_user['roll'] == 'admin' || $site_user['roll'] == 'master_admin'){       
                $return_arr[$user['id']] = $user;
            }
            elseif($site_user['roll'] == 'author'){
                $user_can_arr = array('site_id'=>$site_id,'user_id'=>$user['id'],'permission_to'=>$permission_to);
                $user_can = self::simple_find_by_table_name($user_can_arr,'site_user_can');
                if($user_can){       
                    $return_arr[$user['id']] = $user;
                }
            }
        }
        return $return_arr;
    }
}
?>