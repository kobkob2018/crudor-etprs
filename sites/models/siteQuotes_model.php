<?php
  class SiteQuotes extends TableModel{

    protected static $main_table = 'quotes';

    public static function get_cat_quotes($cat_id){
      $db = Db::getInstance();
      $execute_arr = array('cat_id'=>$cat_id);
      $sql = "SELECT * FROM quote_cat WHERE id = :cat_id";
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      $quote_cat = $req->fetch();

      $sql = "SELECT * FROM quotes WHERE status = '1' AND id IN (SELECT quote_id FROM quote_cat_assign WHERE cat_id = :cat_id)";
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      $quotes_list = $req->fetchAll();
      return array(
        "list"=>$quotes_list,
        "cat"=>$quote_cat
      );
    }

    public static $asset_mapping = array(
      'quote_img'=>'quotes',
      'quotes_user_img'=>'quotes_user'
    );

}
?>