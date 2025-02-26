
<?php

class UserModel extends CI_Model {
    public function __construct() {}

    public function showAllProducts(){
        $sql = "SELECT * FROM products";
        $result = $this->db->query($sql);
        return $result->result();

    }
    public function insertOrder($data){
        $this->db->insert("orders",$data);
        return $this->db->insert_id();
    }
    public function showOrderHistory(){
        $sql = "Select * FROM orders";
        $result = $this->db->query($sql);
        return $result->result();
    }
    public function updateOrderStatus($data){
       
        for ($i= 0;$i<count($data);$i++){
            $id = $data[$i]["productId"];
            $selectSql = "SELECT * FROM products WHERE productId='$id'";
            $result = $this->db->query($selectSql);
            $product = $result->row();
            $productRemaing = $product->available - $data[$i]["quantity"];
            // echo $productRemaing;
            $sql = "UPDATE `products` SET`available`='$productRemaing' WHERE productId='$id'";
            $this->db->query($sql);
        }
    }
}

?>