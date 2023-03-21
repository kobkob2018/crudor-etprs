<?php
	class testModule extends Module{

        public $add_models = array("test");
        public function help(){
            echo "<hr/>";
            echo "hi this is help function in test module";
            echo "<hr/>";
            echo "<a href='http://usites.com/usites/admin/'>למערכת הניהול</a>";
            echo "<hr/>";
            $current_url = get_config('base_url') . $_SERVER["REQUEST_URI"];
            echo "<p> here is the session:</p>";
           
           // session__clear();
            var_dump($_SESSION);
            echo "<hr/>";
            print_r($_REQUEST);
            echo "<hr/>";
        }

	}
?>