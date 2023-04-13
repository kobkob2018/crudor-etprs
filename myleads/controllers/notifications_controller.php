<?php
	class NotificationsController extends CrudController{

		public $add_models = array('myleads_lounch_fee','user_cc_token','cities','myleads_pay_by_cc_log');

		public function get_list(){
            $this->set_layout('blank');
			$billing_messege_exists = $this->check_fee_exist();
			if($billing_messege_exists){
				echo "<div id='billing_note_wrap'>
					<b style='font-size: 18px;color:red;line-height: 25px;'>קיימים חיובים באתר<br/>
						<a href='payments/lounch_fee_list/'>לחץ כאן לצפייה בחיובים</a>
					</b>
				</div>";
			}
			
			$user = Users::get_loged_in_user();
			// הודעות מערכת
            $sql = "SELECT msg.*, u_read.read_status FROM net_messages msg  
            LEFT JOIN net_message_user_read u_read 
            ON u_read.message_id = msg.id 
            WHERE u_read.user_id = :user_id 
            AND u_read.read_status != '3' 
            AND msg.deleted = '0'";
            $execute_arr = array('user_id'=>$user['id']);
            $db = Db::getInstance();
            $req = $db->prepare($sql);
            $req->execute($execute_arr);
            $messages = $req->fetchAll();

			$count = 0;
			$msgStr = "";
            if($messages){

                foreach($messages as $dataMassg)
                {
                    /*
                    $dataMassg['date_sent']
                    $dataMassg['subject']
                    $dataMassg['read']
                    $dataMassg['content']
                    */
				$date_time = $dataMassg['last_time_sent'];
				
				$row_style = "4B8DF1";
				$moreDateScript = "class='msg-title msg_read_".$dataMassg['read_status']."' id='line1_".$dataMassg['id']."' onclick='moreDate(\"".$dataMassg['id']."\")' style='cursor : pointer;' onMouseover='this.style.backgroundColor=\"D0E1FB\"; this.style.color=\"000000\";' onMouseout='this.style.backgroundColor=\"".$row_style."\"; this.style.color=\"ffffff\";' ";
				$msgStr .= "<div ".$moreDateScript.">";
					$msgStr .= "<div class='msg-icon'><img src='style/image/small_msg_icon.png'></div>";
					
					$msgStr .= "<div class='msg-time'>".$date_time."</div>";
					$msgStr .= "<div class='msg-subject'>".$dataMassg['title']."</div>";
					$msgStr .= "<div class='clear'></div>";
				$msgStr .= "</div>";
				
				$msgStr .= "<div class='msg-content-wrap' style='display:none' id='line_".$dataMassg['id']."' style='background-color: #4B8DF1;'>
                
                <div class='msg-content'>".$dataMassg['msg']."</div>";
                $count++;
                }
			}
			if( $count > 0 )
			{
				echo "<div id='notifications_content'>";

						echo $msgStr;
											
				echo "</div>";
			}
			else{
				if(!$billing_messege_exists){
					echo "<div style='color:red;white-space: nowrap;'>אין התראות</div>";
				}
			}
		}
			
		public function check_list(){
			
			$messege_exists = false;
			$this->set_layout('blank');
			$user = Users::get_loged_in_user();			
			$billing_messege_exists = $this->check_fee_exist();
			if($billing_messege_exists){
				$messege_exists = true;
			}
			else{
				$sql = "SELECT count(msg.id) as msg_count FROM net_messages msg  
						LEFT JOIN net_message_user_read u_read 
						ON u_read.message_id = msg.id 
						WHERE u_read.user_id = :user_id 
						AND u_read.read_status = '0' 
						AND msg.deleted = '0'";
				$execute_arr = array('user_id'=>$user['id']);
				$db = Db::getInstance();
				$req = $db->prepare($sql);
				$req->execute($execute_arr);
				$count_result = $req->fetch();
				if( $count_result && $count_result['msg_count'] != '0' ){
					$messege_exists = true;
				}
			}
			if($messege_exists){
				echo "true";
			}
			else{
				echo "false";
			}
		}

		public function mark_as_read(){
			$this->set_layout('blank');
			$user = Users::get_loged_in_user();			
			$message_id = $_GET['message_id'];
			$execute_arr = array('user_id'=>$user['id'], 'message_id'=>$message_id);
			$sql = "UPDATE net_message_user_read SET read_status = '1' WHERE user_id = :user_id AND message_id = :message_id";
			$db = Db::getInstance();
			$req = $db->prepare($sql);
			$req->execute($execute_arr);
		}
			
		public function check_fee_exist(){
			$filter_arr = array(
				'user_id'=>$this->user['id'],
				'pay_status'=>'0'
			);
			$fee_list = Myleads_lounch_fee::get_list($filter_arr,"id");
			if($fee_list){
				return true;
			}
			return false;
		}

	}	

?>