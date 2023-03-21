<?php
  class Validation_handler {

    protected $error_messages = array(
        'required'=>'{{label}} הוא שדה חובה',
        'phone'=>'שדה {{label}} לא תקין',
        'email'=>'שדה {{label}} לא תקין',
        'img_format'=>'שדה {{label}} חייב להיות קובץ מסוג תמונה',
        'video_format' => 'שדה {{label}} חייב להיות קובץ מסוג וידאו',
        'int'=>'שדה {{label}} חייב להיות מספר',
        'date'=>'שדה {{label}} חייב להיות תאריך תקין',
        'img_max'=>'שדה {{label}} - תמונה גדולה מידיי( מקסימום מותר - {{img_max}} ביט)',
        'vid_max'=>'שדה {{label}} - וידאו גדול מידיי( מקסימום מותר - {{vid_max}} ביט)',
        'default'=>'שדה {{label}} לא תקין',
    );

    public function validate_by($method_name, $value, $validate_payload = array()){
        $method_name = "validate_by_".trim($method_name);
        if(!method_exists($this,$method_name)){
            $method_name = "validate_by_success";
        }
        $validation_result = $this->$method_name($value, $validate_payload);
        return $validation_result;
    }

    public function validate_by_custom($controller_interface, $method_name, $value, $validate_payload){
        $method_name = trim($method_name);
        if(!method_exists($controller_interface,$method_name)){
            return $this->validate_by('success',$value);
        }
        $validation_result = $controller_interface->$method_name($value, $validate_payload);
        return $validation_result;
    }

    protected function validate_by_success($value, $validate_payload){
        return array(
            'success'=>true,
            'fixed_value'=>$value
        );
    }

    protected function validate_by_required($value, $validate_payload){
        $return_array =  array(
            'success'=>true,
            'fixed_value'=>$value
        );

        //prevent file required to return false when there is allready an uploaded file
        if($validate_payload['field']['type'] == 'file' && $value == ''){
            $value = $validate_payload['db_value'];
            /*
            if($value != ''){
                $return_array['fixed_value'] = $value;
                $return_array['pass'] = true;
            }
            */
        }
        if($value == '' || $value == null){
            $return_array['success'] = false;
            $return_array['message'] = $this->error_messages['required'];
        }
       
        return $return_array;
    }

    protected function validate_by_email($value, $validate_payload){
        $value = trim($value);
        $return_array =  array(
            'success'=>true,
            'fixed_value'=>$value
        );

        $is_valid = filter_var($value, FILTER_VALIDATE_EMAIL);

        if(!$is_valid){
            $return_array['success'] = false;
            $return_array['message'] = $this->error_messages['email'];
        }
        return $return_array;
    }   

    protected function validate_by_full_name($value, $validate_payload){
        $value = trim($value);
        $return_array =  array(
            'success'=>true,
            'fixed_value'=>$value
        );


        $is_valid = strlen($value) > 3;

        if(!$is_valid){
            $return_array['success'] = false;
            $return_array['message'] = $this->error_messages['default'];
        }
        return $return_array;
    } 

    protected function validate_by_phone($value, $validate_payload){
        $value = trim($value);
        $return_array =  array(
            'success'=>true,
            'fixed_value'=>$value
        );

        $pattern="/^(?=\d)(?=.{6,})(?!.*(\d)\1{4})((0[23489]{1}[5-9]{1})|(0[5]{1}[01234578]{1}[2-9]{1})|0[7]{1}[2-9]{1}[2-9]{1})?(\d{2}?\d{4})$/" ;

        $is_valid = preg_match($pattern, $value);

        if(!$is_valid){
            $is_valid = ($value == "123123123");
        }

        if(!$is_valid){
            $return_array['success'] = false;
            $return_array['message'] = $this->error_messages['phone'];
        }
        return $return_array;
    }

    protected function validate_by_int($value, $validate_payload){
        $value = trim($value);
        $return_array =  array(
            'success'=>true,
            'fixed_value'=>$value
        );

        $number = filter_var($value, FILTER_VALIDATE_INT);
        $is_valid =  ($number !== FALSE);

        if(!$is_valid){
            $return_array['success'] = false;
            $return_array['message'] = $this->error_messages['int'];
        }
        return $return_array;
    }

    protected function validate_by_float($value, $validate_payload){
        $value = trim($value);
        $return_array =  array(
            'success'=>true,
            'fixed_value'=>$value
        );

        $number = filter_var($value, FILTER_VALIDATE_FLOAT);
        $is_valid =  ($number !== FALSE);

        if(!$is_valid){
            $return_array['success'] = false;
            $return_array['message'] = $this->error_messages['int'];
        }
        return $return_array;
    }


    protected function validate_by_date($value){
        $value = trim($value);
        $return_array =  array(
            'success'=>true,
            'fixed_value'=>$value
        );
        $formats = array('d-m-Y','d/m/Y');
        $is_valid = true;
        if($value != ""){
            $is_valid = false;
            foreach($formats as $format){
                if(!$is_valid){
                    $d = DateTime::createFromFormat($format, $value);
                    $is_valid = $d && $d->format($format) === $value;
                    if($is_valid){
                        $return_array['fixed_value_for_db'] = $d->format('Y-m-d');
                    }
                }
            }
        }
        if(!$is_valid){
            $return_array['success'] = false;
            $return_array['message'] = $this->error_messages['date'];
            $return_array['fixed_value'] = "";
        }
        return $return_array;
    }

    protected function validate_by_img($value, $validate_payload){
        $return_array =  array(
            'success'=>true,
            'fixed_value'=>$value
        );

        $is_valid = true;
        $field = $validate_payload['field'];
        $file = $validate_payload['user_file'];
        $file_name = $file["name"];
        $check = getimagesize($file["tmp_name"]);

        if(!$check){
            $is_valid = false;
            $return_array['success'] = false;
            $return_array['message'] = $this->error_messages['img_format'];
            $return_array['fixed_value'] = $validate_payload['db_value'];
        }
        if(!$is_valid){
            return $return_array;
        }
        if(isset($field['img_max'])){
            $size = $file["size"];
            if($size > $field['img_max']){
                $is_valid = false;
                $return_array['success'] = false;
                $return_array['message'] = str_replace('{{img_max}}',$field['img_max'],$this->error_messages['img_max']);
                $return_array['fixed_value'] = $validate_payload['db_value'];
            }
        }

        if(!$is_valid){
            return $return_array;
        }   
        $ext = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
        $fixed_value = $field['name_file'];
        if(isset($field['name_file'])){
            $fixed_value = str_replace('{{ext}}',$ext,$field['name_file']);
        }
        $return_array['fixed_value'] = $fixed_value;
        return $return_array;
    }


    protected function validate_by_video($value, $validate_payload){
        $return_array =  array(
            'success'=>true,
            'fixed_value'=>$value
        );

        $is_valid = true;
        $field = $validate_payload['field'];
        $file = $validate_payload['user_file'];
        $file_name = $file["name"];
        $file_type = $file['type'];

        $allowed_extensions = array("webm", "mp4", "ogv", "ogg");
        $ext = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));

        if(!in_array($ext,$allowed_extensions)){
            $is_valid = false;
            $return_array['success'] = false;
            $return_array['message'] = $this->error_messages['video_format'];
            $return_array['fixed_value'] = $validate_payload['db_value'];
        }
        if(!$is_valid){
            return $return_array;
        }
        if(isset($field['vid_max'])){
            $file_size_max = $field['vid_max'];
            $size = $file["size"];
            if($size > $field['vid_max']){
                $is_valid = false;
                $return_array['success'] = false;
                $return_array['message'] = str_replace('{{vid_max}}',$field['vid_max'],$this->error_messages['vid_max']);
                $return_array['fixed_value'] = $validate_payload['db_value'];
            }
        }

        if(!$is_valid){
            return $return_array;
        }   
        $ext = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
        $fixed_value = $field['name_file'];
        if(isset($field['name_file'])){
            $fixed_value = str_replace('{{ext}}',$ext,$field['name_file']);
        }
        $return_array['fixed_value'] = $fixed_value;
        return $return_array;
    }


  }
?>
