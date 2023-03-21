<?php

class User_cc extends TableModel{

    protected static $main_table = 'userCCToken';

	public static function getCCTokens_data($unk){
		$user_tokens = false;
		$user_biz_name = "";
		$user_full_name = "";
		$db = Db::getInstance();
		$sql = "SELECT L4digit,full_name,biz_name FROM userCCToken WHERE unk = :unk";		
		$req = $db->prepare($sql);
		$req->execute(array('unk'=>$unk));
		
		foreach($req->fetchAll() as $user_token_data) {
			if(!$user_tokens){
				$user_tokens = array();
			}
			$user_tokens[] = $user_token_data['L4digit'];
			if($user_token_data['biz_name'] != ""){
				$user_biz_name = iconv("Windows-1255","UTF-8",$user_token_data['biz_name']);
				$user_full_name = iconv("Windows-1255","UTF-8",$user_token_data['full_name']);		
			}
		}
		return array('biz_name'=>$user_biz_name,'full_name'=>$user_full_name,'tokens'=>$user_tokens);
	}


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