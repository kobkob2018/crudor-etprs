<?php
  class Biz_categoriesController extends CrudController{


    public $add_models = array("biz_categories");

    public function ajax_get_select_options(){
        if(!isset($_REQUEST['cat_id'])){
            $print_result = array(
                'fail_reason'=>'no_cat_selected',
                'err_message'=>'No category was selected',
                'success'=>false
            );
            return $this->print_json_page($print_result);
        }

        $cat_id = $_REQUEST['cat_id'];
        if($cat_id == '0'){
            $cat_info = array(
                'id'=>'0',
                'label'=>'main',
                'parent'=>'-1'
            );
        }
        else{
            $cat_info = Biz_categories::get_by_id($cat_id,'id,label,parent');
        }
        $cat_info['has_children'] = true;
        $cat_cildren = Biz_categories::get_children_list_of($cat_id,'id,label,parent');
        if(!$cat_cildren){
            $cat_cildren = Biz_categories::get_children_list_of($cat_info['parent'],'id,label,parent');
            $cat_info['has_children'] = false;
        }
        foreach($cat_cildren as $child_key => $child){
            $selected = false;
            if($cat_info['id'] == $child['id']){
                $selected = true;
            }
            $cat_cildren[$child_key]['selected'] = $selected;
        }
        $cat_info['children'] = $cat_cildren;

        $print_result = array(
            'cat_info'=>$cat_info,
            'success'=>true
        );
        return $this->print_json_page($print_result);
    }

  }

?>