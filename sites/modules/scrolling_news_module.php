<?php
	class Scrolling_newsModule extends Module{

        public $add_models = array("siteNews");
        public function print(){
            //print_r_help(($this->controller->data));
            $news = SiteNews::get_site_news($this->controller->data['site']['id']);
            if(!$news){
                return;
            }
            $info = array(
                'news'=>$news
            );
            
            $this->include_view('scrolling/news.php',$info);
        }
	}
?>