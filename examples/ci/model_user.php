<?php
class Model_User extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function list_user(&$data=array(), $where=array())
    {
        $query = $this->db->select("u.id,u.first_name, u.last_name, u.username, u.password, r.name as role, u.web,email, u.lang, u.status")
                 ->from("user u")
                 ->join("role r","r.id=u.id_role","inner");
        
        foreach($where as $key=>$val){
            $query->where($key, $val);
        }

        if(isset($data["sidx"]))
            $query->order_by($data["sidx"], $data["sord"]);
        
        $query = $this->db->get();       
        
        if( isset($data["item_per_page"]) && $query->num_rows() > 0 && $data["item_per_page"] > 0) {
            $data["total"] = ceil($query->num_rows() / $data["item_per_page"]);
        }

        $result = $query->result_array();
        
        if(empty($data["page"])) return $result; 

        $list_user_per_page = array();

        for($i = $data["item_per_page"] * ($data["page"]-1); $i < $data["item_per_page"] * $data["page"] && $i < $query->num_rows(); $i++){

            $list_user_per_page[] = $result[$i];
        }
        return $list_user_per_page;
    }   

    public function fetch_user_by_id($id)
    {
        $this->db->select("u.id, u.first_name, u.last_name,u.username, u.password, u.id_role, u.web,email, u.lang, u.status")
                 ->from("user u")
                 ->where("u.id", $id);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function fetch_user_by_username($username)
    {
        $this->db->select("u.id, u.first_name, u.last_name,u.username, u.password, u.id_role, u.web,email, u.lang, u.status")
                 ->from("user u")
                 ->where("u.username", $username);
        $result = $this->db->get()->row_array();
        return $result;
    }
   
    public function fetch_all_user($lang='')
    {
          $query = $this->db->select("u.id, u.first_name, u.last_name, u.username, u.password, u.id_role, u.web,email, u.lang, u.status")
                 ->from("user u");
          if($lang!='')
              $query->where("u.lang", $lang);
        $result = $this->db->get()->result_array();
        return $result;
    }


    public function save($data = array())
    {
          if(isset($data["password"]) && $data["password"]!='')
            $data["password"] = md5($data["password"]);
          else
            unset($data["password"]);

        if(isset($data["id"]) && $data["id"]!=''){
            $this->db->where("id", $data["id"]);
            $this->db->update("user", $data);
                 
        }else{
            $data["status_dt"] = date("Y-m-d h:i:s");
            $this->db->insert('user', $data);
        }        
    }

    public function delete_user($data)
    {
         if(is_array($data)){
            $this->db->where_in('id',$data);
            $this->db->update('user',array("status"=>"D","status_dt"=>date("Y-m-d h:i:s")));
        }
        else{
             $this->db->where('id',$data);
             $this->db->update('user',array("status"=>"D","status_dt"=>date("Y-m-d h:i:s")));
        }
        return true;
    }
}
?>