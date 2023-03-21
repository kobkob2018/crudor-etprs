<?php
  function call($controller, $action) {
	
    
	$controller_class = ucfirst($controller)."Controller";
	
	$controller = new $controller_class();
	
    $controller->print_layout($action);
	
    //$controller->{ $action }();
  }
  
  function print_view($controller,$action){
		// we're adding an entry for the new controller and its actions
		
		if(system_file_exists('controllers/' . $controller . '_controller.php')){
			
			system_require_once('controllers/' . $controller . '_controller.php');
			if(method_exists(ucfirst($controller)."Controller",$action)){
				call($controller, $action);
			}
			else{
				if($action != get_config('error_action')){ //prevent loop if the error action not exists
					print_view(get_config('error_controller'), get_config('error_action'));
				}
				else{
					echo "the error method not exist";
				}
			}
		}
		else{
			if($controller != get_config('error_controller')){ //prevent loop if the error controller file not exists

				print_view(get_config('error_controller'), get_config('error_action'));
			}
			else{
				echo "the pages controller for error not exist";
			}
		}
	}


	if (isset($init_request['controller']) && isset($init_request['action'])) {
		$controller = $init_request['controller'];
		$action     = $init_request['action'];
	} else {
		$controller = get_config('home_controller');
		$action     = get_config('home_action');
	}
	
	print_view($controller,$action);

?>