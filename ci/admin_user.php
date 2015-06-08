<?php
class Admin_User extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

     public function edit_user(){
        $data = array();
        $errors = array();
        $this->load->library('user/user_form');
        $this->load->model("model_user", "model_user");
        $this->load->model("role/model_role", "model_role");
        $this->load->model("site/model_site", "model_site");
        $this->user_form->CI =& $this;

        $id = $this->input->get("id");

        $submit = $this->input->get("submit");
        if($submit!=''){
            $data = $this->input->post("data");

            if($this->user_form->validation() == TRUE){
                $this->model_user->save($data);
                $this->user_form->errors["success"] = 1;
            }
            else{
                $this->user_form->errors["success"] = 0;
            }

            echo json_encode($this->user_form->errors);
            die();

        } else if($id!=''){
            $data = $this->model_user->fetch_user_by_id($id);
        }

        $langs = $this->model_site->get_langs();
        $roles = $this->model_role->fetch_all_roles();
        $this->load->view("admin/edit_user", array("data" => $data, "langs"=>$langs,"roles"=>$roles));
    }

    public function list_user(){

       $this->load->model("model_user");
       $data = array( "total" => "",
                        "page" => "",
                        "records"=> "",
                        "rows" => array()
                    );

        $where = array();

        if($this->input->post("lang"))
            $where["lang"] = $this->input->post("lang");

        if($this->input->post("sidx"))
            $data["sidx"] = $this->input->post("sidx");
        else
            $data["sidx"] = "id";

        if($this->input->post("sord"))
            $data["sord"] = $this->input->post("sord");
        else
            $data["sord"] = "ASC";

        if($this->input->post("page"))
            $data["page"] = $this->input->post("page");
        else
            $data["page"] = 1;

         if($this->input->post("rows"))
            $data["item_per_page"] = $this->input->post("rows");
        else
            $data["item_per_page"] = 10;


        $list_user = $this->model_user->list_user($data, $where);

        foreach($list_user as $user)
        {
            $data["rows"][] = array("id" => $user["id"], "cell"=>array("",$user["id"], $user["first_name"], $user["last_name"], $user["username"], $user["role"], $user["email"], $user["web"], $user["lang"], $user["status"]));
        }

        echo json_encode($data);
        die();
    }

    public function index_user(){
        $module_content = modules::run('grid/grid/init', array(
                                                               'title_table' => "Users",
                                                               'title_edit_dialog' => "Edit User",
                                                               'url' => 'user/admin_user/list_user',
                                                               'edit_url' => 'user/admin_user/edit_user',
                                                               'delete_url' => 'user/admin_user/delete_user',
                                                               'warning_message_delete' => "Da li zelite da obrisete user-a",
                                                               'dialog_width' => '470',
                                                               'dialog_height' => '600',
                                                               'colnames' => "'ID','First Name','Last Name','Username','Role','Email','Web','Lang','Status'",
                                                               'colmodel' => ("{name:'act', index:'act', width:\"30px\",sortable:false,align:\"center\"},\r\n" .
                                                                             "{name:'ID', index:'id', width:\"30px\",sortable:true,align:\"center\"},\r\n" .                                                                            
                                                                             "{name:'first_name', index:'first_name', width:\"30px\",sortable:true,align:\"center\"},\r\n" .
                                                                             "{name:'last_name', index:'last_name', width:\"30px\",sortable:true,align:\"center\"},\r\n" .
                                                                             "{name:'username', index:'username', width:\"30px\",sortable:true,align:\"center\"},".
                                                                             "{name:'role', index:'role', width:\"30px\",sortable:true,align:\"center\"},".
                                                                             "{name:'email', index:'email', width:\"30px\",sortable:true,align:\"center\"},".
                                                                             "{name:'web', index:'web', width:\"30px\",sortable:true,align:\"center\"},".                                                                             
                                                                             "{name:'lang', index:'lang', width:\"30px\",sortable:true,align:\"center\"},".
                                                                             "{name:'status', index:'status', width:\"30px\",sortable:true,align:\"center\", formatter:statusFmatter}"
                                                                            )
                                                                ));
         $this->load->view("admin/list_user", array("content" => $module_content));
    }

    public function delete_user(){
         $this->load->model("model_user", "model_user");
         $chks = $this->input->post("chk");
         $this->model_user->delete_user($chks);
    }
}
?>