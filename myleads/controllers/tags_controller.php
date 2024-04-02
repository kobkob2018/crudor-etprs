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
				$fixed_values = $_REQUEST['tag_data'];
				$fixed_values['user_id'] = $this->user['id'];	
				Tags::create($fixed_values);
				SystemMessages::add_success_message("התיוג עודכן בהצלחה");
				return $this->redirect_to(inner_url("tags/settings/"));
				
			}
			if(isset($_REQUEST['delete_tag'])){
				Tags::delete_tag($_REQUEST['tag_data']);
				SystemMessages::add_success_message("התיוג נמחק");
				return $this->redirect_to(inner_url("tags/settings/"));
			}
			if(isset($_REQUEST['edit_tag'])){
				Tags::update($_REQUEST['edit_tag'],$_REQUEST['tag_data']);
				SystemMessages::add_success_message("התיוג עודכן");
				return $this->redirect_to(inner_url("tags/settings/"));

			}
			$this->data['tag_list'] = Tags::get_user_tag_list();
			$info = array(
				'tag_color_list'=>array(
					array('id'=>'1','label'=>'כחול'),
					array('id'=>'2','label'=>'אדום'),
					array('id'=>'3','label'=>'צבע3'),
					array('id'=>'4','label'=>'צבע4'),
					array('id'=>'5','label'=>'צבע5'),
					array('id'=>'6','label'=>'צבע6'),
					array('id'=>'7','label'=>'צבע7'),
					array('id'=>'8','label'=>'צבע8'),
					array('id'=>'9','label'=>'צבע9'),
					array('id'=>'10','label'=>'צבע10'),
					array('id'=>'11','label'=>'צבע11'), 
				)
			);
			$this->include_view('tags/tags_settings.php',$info);
		}		
	}
?>