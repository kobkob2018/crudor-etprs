<?php
  class Tags extends TableModel{
    // we define 3 attributes
    // they are public so that we can access them using $post->author directly

	protected static $main_table = 'user_lead_tag';

    public static function add_tag($tag_data){
		$db = Db::getInstance();
		$sql = "INSERT INTO user_lead_tag(user_id,tag_name)VALUES(:uid,:tag_name)";
		$req = $db->prepare($sql);
		$user = Users::get_loged_in_user();
		$sql_arr = array(
			"uid"=>$user['id'],
			"tag_name"=>$tag_data['tag_name']
		);
		$req->execute($sql_arr);
    }

    public static function delete_tag($tag_data){
		$db = Db::getInstance();
		$sql = "DELETE FROM user_lead_tag WHERE id = :tag_id";
		$req = $db->prepare($sql);
		$sql_arr = array(
			"tag_id"=>$tag_data['tag_id']
		);
		$req->execute($sql_arr);
    }
	
    public static function get_user_tag_list(){
		$db = Db::getInstance();
		$sql = "SELECT * FROM user_lead_tag WHERE user_id = :uid";
		$req = $db->prepare($sql);
		$user = Users::get_loged_in_user();
		$sql_arr = array(
			"uid"=>$user['id']
		);
		$req->execute($sql_arr);
		$tag_list = array("0"=>array('id'=>'0','tag_name'=>"ללא תיוג",'color_id'=>'0'));
		foreach($req->fetchAll() as $tag) {
			$tag_list[$tag['id']] = $tag;
		}
		return $tag_list;
    }
	
  }
?>