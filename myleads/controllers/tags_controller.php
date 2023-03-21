<?php
	class TagsController extends CrudController{
		public $add_models = array("tags","leads");

		public function test(){
			$execute_arr = array('lead_id'=>'177');
			$db = Db::getInstance();
			$sql = "SELECT * FROM user_leads WHERE request_id = :lead_id";
			$req = $db->prepare($sql);
			$req->execute($execute_arr);
			$res = $req->fetch();
			print_r_help($res);
		}

		public function settings(){
			if(isset($_REQUEST['add_tag'])){
				
				Tags::add_tag($_REQUEST['tag_data']);
				SystemMessages::add_success_message("התיוג עודכן בהצלחה");
				
			}
			if(isset($_REQUEST['delete_tag'])){
				Tags::delete_tag($_REQUEST['tag_data']);
				SystemMessages::add_success_message("התיוג נמחק");
			}
			$this->data['tag_list'] = Tags::get_user_tag_list();
			$this->include_view('tags/tags_settings.php');
		}		
	}
?>