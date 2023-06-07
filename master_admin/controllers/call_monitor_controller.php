<?php

  class Call_monitorController extends CrudController{
    public $add_models = array("user_current_phone_calls");

    public function get_current_phone_calls_ajax(){
        $this->set_layout("blank");
        $phone_list = User_current_phone_calls::get_current_phone_calls();
        $info = array(
            'phone_list'=>$phone_list
        );
        $this->include_view('user_calls/monitor_list.php',$info);
    }

    public function monitor(){
        $this->include_view("user_calls/monitor.php");
    }

    public function misscalls_comments(){
        $this->add_model("misscalls_comments");
        
        
        if(isset($_REQUEST['edit_misscall_comment'])){
            $this->set_layout("blank");
            $comment_comments = str_replace("'","''",$_REQUEST['comment']);
            $lead_id = $_REQUEST['edit_misscall_comment'];

            $comment = Misscalls_comments::find(array('lead_id'=>$lead_id));

            if(!$comment){
                Misscalls_comments::create(array(
                    'user_id'=>$_REQUEST['lead_user_id'],
                    'lead_id'=>$lead_id,
                    'comment'=>$comment_comments,
                    'last_comment_by_user'=>$this->user['id'],
                    'lead_by_phone'=>$_REQUEST['lead_by_phone'],
                    'mark_color'=>$_REQUEST['mark_color'],
                ));
            }

            else{
                Misscalls_comments::update($comment['id'],array(
                    'user_id'=>$_REQUEST['lead_user_id'],
                    'lead_id'=>$lead_id,
                    'comment'=>$comment_comments,
                    'last_comment_by_user'=>$this->user['id'],
                    'lead_by_phone'=>$_REQUEST['lead_by_phone'],
                    'mark_color'=>$_REQUEST['mark_color'],
                ));
            }

            $comment = Misscalls_comments::find(array('lead_id'=>$lead_id));
            $owners_colors = array(
                '1'=>'#e7e7ff',
                '9'=>'#acffac',
                '27'=>'#f5f5a7',
            );
            if($comment['mark_color'] == ""){
                $comment['mark_color'] = $owners_colors[$comment['last_comment_by_user']];
            }
            $last_comment_by_user = $comment['last_comment_by_user'];

            $owner = Users::get_by_id($comment['last_comment_by_user'],'full_name');
            $comment['owner_name'] = $owner['full_name'];
            $return_data = array("success"=>"1","data"=>$comment,"msg"=>"ההערה עודכנה בהצלחה");
            
            echo json_encode($return_data);
            exit();
        }

        $info = Misscalls_comments::get_comment_list();

        $info['owners_colors'] = array(
            '1'=>'#e7e7ff',
            '9'=>'#acffac',
            '27'=>'#f5f5a7',
        );
        $info['campaign_names'] = array(
            '0'=>'רגיל',
            '1'=>'גוגל',
            '2'=>'פייסבוק'
        );
        $info['campaign_colors'] = array(
            '0'=>'white',
            '1'=>'#ffdfdf',
            '2'=>'#c5c5ec'
        );	
        
        $this->add_curent_calls_monitor();
        $this->include_view('user_calls/misscalls_comments.php',$info);
        
    }

    protected function add_curent_calls_monitor(){
        $this->include_view("user_calls/monitor.php");
        
    }


  }

?>