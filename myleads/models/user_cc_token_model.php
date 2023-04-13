<?php

class User_cc_token extends TableModel{

    protected static $main_table = 'user_cc_token';

	public static function getCCToken_data($unk,$token_id){
		$db = Db::getInstance();
		$sql = "SELECT * FROM userCCToken WHERE unk = :unk AND L4digit = :token_id";		
		$req = $db->prepare($sql);
		$req->execute(array('unk'=>$unk,'token_id'=>$token_id));
		return $req->fetch();
	}	

	public static function insertCClog($user_id,$new_p,$pro_decs_insert,$gotoUrlParamter,$full_name,$biz_name){
		$db = Db::getInstance();
		$insert_arr = array(
			"user_id"=>$user_id,
			"new_p"=>$new_p,
			"pro_decs_insert"=>$pro_decs_insert,
			"gotoUrlParamter"=>$gotoUrlParamter,
			"full_name"=>$full_name,
			"biz_name"=>$biz_name,
		);
		$sql = "INSERT INTO ilbizPayByCCLog(sumTotal, 
                                            payDate, 
                                            description, 
                                            payToType, 
                                            userId ,  
                                            gotoUrlParamter, 
                                            full_name, 
                                            biz_name ) 
			                        VALUES (
                                            :new_p,   
                                            NOW(),  
                                            :pro_decs_insert , 
                                            '9',      
                                            :user_id ,
                                            :gotoUrlParamter,
                                            :full_name,
                                            :biz_name
                                        )";
		$req = $db->prepare($sql);
		$req->execute($insert_arr);

		return $db->lastInsertId();
	}
}
?>