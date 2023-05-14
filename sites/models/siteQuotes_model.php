<?php
  class SiteQuotes extends TableModel{

    protected static $main_table = 'quotes';

    public static function get_cat_quotes($cat_id){
        $db = Db::getInstance();
        $execute_arr = array('cat_id'=>$cat_id);
		$sql = "SELECT * FROM quotes WHERE id IN (SELECT quote_id FROM quote_cat_assign WHERE cat_id = :cat_id)";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
		$quots_arr = $req->fetchAll();
		return $quots_arr;
    }

}
?>