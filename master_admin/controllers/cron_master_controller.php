<?php
class Cron_masterController extends CrudController{

  protected $scedualed_module_tasks = array(
    'each_minute'=>array(
      array('label'=>'send_pending_emails', 
            'module'=>'cron_emails',
            'action'=>'send_pending_emails'
      ),

      array('label'=>'cron_user_phone_calls', 
      'module'=>'cron_user_phone_calls',
      'action'=>'update_new_calls'
    ),

    ),
    'hourly'=>array(),
    'daily_mornings'=>array(),
    'daily_midnight'=>array(),
    'weekly_night_time'=>array(
      'Sun'=>array(),
      'Mon'=>array(),
      'Tue'=>array(),
      'Wed'=>array(),
      'Thu'=>array(),
      'Fri'=>array(),
      'Sat'=>array(),
    ),
    'weekly_day_time'=>array(
      'Sun'=>array(),
      'Mon'=>array(),
      'Tue'=>array(),
      'Wed'=>array(),
      'Thu'=>array(),
      'Fri'=>array(),
      'Sat'=>array(),
    ),
    'monthly'=>array(
      array('label'=>'pending_emails_monthly_cleanup', 
      'module'=>'cron_emails',
      'action'=>'monthly_cleanup'
),
    ),
  );

  protected function handle_access($action){

    if($action == 'map_cron_actions'){
      return true;
    }
    return parent::handle_access($action);
	}

  protected function map_cron_actions(){
    $this->set_layout("blank");
    foreach($this->scedualed_module_tasks['each_minute'] as $task_label=>$task){
      $this->call_module($task['module'],$task['action']);
    }

    $now_timestamp = time();
    if(isset($_REQUEST['checktime'])){
      $now_timestamp = strtotime($_REQUEST['checktime']);
      
    }    
    $now_time_str = date("m/d/Y H:i", $now_timestamp);



    $now_round_hour_str = date("m/d/Y H:00", $now_timestamp);
    $now_round_day_morning_str = date("m/d/Y 07:00", $now_timestamp);
    $now_round_day_midnight_str = date("m/d/Y 00:00", $now_timestamp);
    $today_is = date('D',$now_timestamp);
    $now_round_monthly_midnight_str = date("m/01/Y 00:00", $now_timestamp);
    

    print_help($now_time_str,'now_time_str');
    print_help($now_round_hour_str,'now_round_hour_str');
    print_help($now_round_day_morning_str,'now_round_day_morning_str');
    print_help($now_round_day_midnight_str,'now_round_day_midnight_str');
    print_help($today_is,'today_is');
    print_help($now_round_monthly_midnight_str,'now_round_monthly_midnight_str');

    if($now_time_str == $now_round_hour_str){
      foreach($this->scedualed_module_tasks['hourly'] as $task_label=>$task){
        $this->call_module($task['module'],$task['action']);
      }
    }

    if($now_time_str == $now_round_day_morning_str){
      foreach($this->scedualed_module_tasks['daily_mornings'] as $task_label=>$task){
        $this->call_module($task['module'],$task['action']);
      }

      foreach($this->scedualed_module_tasks['weekly_night_time'][$today_is] as $task_label=>$task){
        $this->call_module($task['module'],$task['action']);
      }
    }

    if($now_time_str == $now_round_day_midnight_str){
      foreach($this->scedualed_module_tasks['daily_midnight'] as $task_label=>$task){
        $this->call_module($task['module'],$task['action']);
      }
      
      foreach($this->scedualed_module_tasks['weekly_day_time'][$today_is] as $task_label=>$task){
        $this->call_module($task['module'],$task['action']);
      }
    }

    if($now_time_str == $now_round_monthly_midnight_str){
      foreach($this->scedualed_module_tasks['monthly'] as $task_label=>$task){
        $this->call_module($task['module'],$task['action']);
      }
    }

  }

}
?>