<?php
class Language_messagesController extends CrudController{
  public $add_models = array("languages","language_messages");


  protected function init_setup($action){
      if(!isset($_GET['language_id'])){
          SystemMessages::add_err_message("לא נבחרה מערכת");
          return $this->redirect_to(inner_url("languages/list/"));
      }
      $language = Languages::get_by_id($_GET['language_id']);
      $this->data['current_language'] = $language;
      $this->data['system_id'] = $language['system_id'];
      return parent::init_setup($action);
  }

  public function list(){
    $system_options = array(
        'master_admin'=>'ניהול ראשי',
        'admin'=>'ניהול אתרים',
        'sites'=>'אתרים',
        'myleads'=>'ניהול לידים'
    );
    $info = array(
        'system_options'=>$system_options
    );

    $info['system_id'] = $this->data['system_id'];
    $info['system_id_label'] = $system_options[$info['system_id']];

    $filter_arr = $this->get_base_filter();
    $payload = array(
        'order_by'=>'msgid'
    );
    $message_list = Language_messages::get_list($filter_arr,"*", $payload);
    $this->data['message_list'] = $this->prepare_forms_for_all_list($message_list);

    $this->include_view('language_messages/list.php',$info);
  }

  protected function get_base_filter(){
    $filter_arr = array();
    if(isset($this->data['current_language'])){
        $filter_arr['language_id'] = $this->data['current_language']['id'];
    }
    if(isset($this->data['system_id'])){
        $filter_arr['system_id'] = $this->data['current_language']['system_id'];
    }  
    return $filter_arr;     
  }

  public function edit(){
    return parent::edit();
  }

  public function updateSend(){
    return parent::updateSend();
  }

  public function add(){
    return parent::add();
  }       

  public function createSend(){
    return parent::createSend();
  }

  public function delete(){
    return parent::delete();      
  }

  public function include_edit_view(){
    $this->include_view('language_messages/edit.php');
  }

  public function include_add_view(){
    $this->include_view('language_messages/add.php');
  }   

  protected function update_success_message(){
    SystemMessages::add_success_message("התרגום עודכן בהצלחה");

  }

  protected function create_success_message(){
    SystemMessages::add_success_message("התרגום נוצר בהצלחה");

  }

  protected function delete_success_message(){
    SystemMessages::add_success_message("התרגום נמחק");
  }

  protected function row_error_message(){
    SystemMessages::add_err_message("לא נבחר תרגום");
  }   

  protected function delete_item($row_id){
    return Language_messages::delete($row_id);
  }

  protected function get_item_info($row_id){
    return Language_messages::get_by_id($row_id);
  }

  protected function after_delete_redirect(){
    return $this->eject_redirect();
  }

  protected function after_add_redirect($new_row_id){
      return $this->redirect_to(inner_url("language_messages/list/?language_id=".$this->data['current_language']['id']));
  }

  public function eject_url(){
    if(isset($this->data['current_language'])){
        return inner_url('language_messages/list/?language_id='.$this->data['current_language']['id']);
    }
    return inner_url('languages/list/');
  }

  public function url_back_to_item($item_info){
    if(isset($this->data['current_language'])){
        return inner_url('language_messages/list/?language_id='.$this->data['current_language']['id']);
    }
    return inner_url('languages/list/');
  }

  public function delete_url($item_info){
    return inner_url("language_messages/delete/?language_id=".$this->data['current_language']['id']."&row_id=".$item_info['id']);
  }

  protected function get_fields_collection(){
    return Language_messages::setup_field_collection();
  }

  protected function update_item($item_id,$update_values){
    $return_val = Language_messages::update($item_id,$update_values);
    $this->export_language_to_json();
    return $return_val;
  }

  protected function create_item($fixed_values){
    if(!isset($this->data['current_language'])){
        SystemMessages::add_err_message("לא נבחרה שפה");
        return $this->redirect_to(inner_url("languages/list/"));
    }
    $fixed_values['system_id'] = $this->data['current_language']['system_id'];
    $fixed_values['language_id'] = $this->data['current_language']['id'];
    $fixed_values['iso_code'] = $this->data['current_language']['iso_code'];

    $msg_find_arr = array(
      'system_id'=>$fixed_values['system_id'],
      'language_id'=>$fixed_values['language_id'],
      'iso_code'=>$fixed_values['iso_code'],
      'msgid'=>$fixed_values['msgid'],
    );
    $msg_find = Language_messages::find($msg_find_arr);

    if($msg_find){
      SystemMessages::add_err_message("המשפט הזה כבר קיים בתרגומים");
      return $this->redirect_to(current_url());
    }

    $return_val =  Language_messages::create($fixed_values);
    $this->export_language_to_json();
    return $return_val;
  }

  function export_language_to_json(){
    $system_id = $this->data['current_language']['system_id'];
    $iso_code = $this->data['current_language']['iso_code'];
    $filter_arr = array('system_id'=>$system_id,'iso_code'=>$iso_code);
    $message_list = Language_messages::get_list($filter_arr);
    $message_json_arr = array();
    foreach($message_list as $message){
        $message_json_arr[$message['msgid']] = $message['msgstr'];
    }
    $message_json = json_encode($message_json_arr);
    if(!is_dir('locale')){
        $oldumask = umask(0) ;
        $mkdir = @mkdir( 'locale', 0755 ) ;
        umask( $oldumask ) ;
        exit("ok not");
    }
    if(!is_dir('locale/'.$system_id)){
        $oldumask = umask(0) ;
        $mkdir = @mkdir( 'locale/'.$system_id, 0755 ) ;
        umask( $oldumask ) ;
    }
    $language_file = 'locale/'.$system_id."/".$iso_code.".json";
    if(file_exists($language_file)){
        unlink($language_file);
    }
    file_put_contents($language_file, $message_json);
    exit("ok");
  }
}
?>