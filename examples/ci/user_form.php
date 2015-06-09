<?php
class User_Form
{
    public $CI;
    public $errors;
    public $name;
    public $class;
    public $lang;
    public $status;

    public function validation()
    {
         $this->CI->form_validation->set_rules('data[id]', 'id', 'trim|required|xss_clean');

         $this->CI->form_validation->set_rules('data[first_name]', 'First Name', 'trim|required|xss_clean');
         
         $this->CI->form_validation->set_rules('data[last_name]', 'Last Name', 'trim|required|xss_clean');
         
         $this->CI->form_validation->set_rules('data[username]', 'Username', 'trim|xss_clean');
         
         $this->CI->form_validation->set_rules('data[password]', 'Password', 'trim|xss_clean');

         $this->CI->form_validation->set_rules('data[id_role]', 'Role', 'trim|required|xss_clean');

         $this->CI->form_validation->set_rules('data[email]', 'Email', 'trim|required|xss_clean');

         $this->CI->form_validation->set_rules('data[web]', 'Web', 'trim|xss_clean');

         $this->CI->form_validation->set_rules('data[lang]', 'Lang', 'trim|required|xss_clean');

         $this->CI->form_validation->set_rules('data[status]', 'Status', 'trim|required|xss_clean');

         $this->CI->form_validation->set_message('required', 'Unesite ovo polje');

         $this->CI->form_validation->set_error_delimiters('<span class="help-inline"><em>', '</em></span>');

         if($this->CI->form_validation->run() === TRUE)
         {      
             return true;                    
         }
         else{
             if(form_error("data[first_name]")!='')
                $this->errors["error"]["data\\[first_name\\]"] = form_error("data[first_name]");
             
             if(form_error("data[last_name]")!='')
                $this->errors["error"]["data\\[last_name\\]"] = form_error("data[last_name]");

             if(form_error("data[username]")!='')
                $this->errors["error"]["data\\[username\\]"] = form_error("data[username]");

             if(form_error("data[role]")!='')
                $this->errors["error"]["data\\[role\\]"] = form_error("data[role]");

             if(form_error("data[email]")!='')
                $this->errors["error"]["data\\[email\\]"] = form_error("data[email]");

             if(form_error("data[web]")!='')
                $this->errors["error"]["data\\[web\\]"] = form_error("data[web]");
             
             if(form_error("data[lang]")!='')
                $this->errors["error"]["data\\[lang\\]"] = form_error("data[lang]");
             
             if(form_error("data[status]")!='')
                $this->errors["error"]["data\\[status\\]"] = form_error("data[status]");

             //insert
             if(form_error("data[id]")!='')
             {
                if(count($this->errors["error"])==0)
                {
                    if(form_error("data[password]")!=''){
                        $this->errors["error"]["data\\[password\\]"] = form_error("data[password]");
                        return false;
                    }
                    else
                        return true;
                }
             }             
             
             return false;
         }        
    }    
}
?>