<?php

/**
 * Admin controller
 */
class Download_AdminController extends HCMS_Controller_Action_Admin {

    /**
     *
     * @var HCMS_Controller_Action_Helper_AjaxForm
     */
    protected $_formHelper = null;

    const PAGE_TYPE_CODE = 'download';
    const PAGE_TYPE_CODE_PRIVATE = 'download_private';

    /**
     * @var Cms_Model_PageType
     */
    protected $_pageType = null;

    public function init(){
        parent::init();
        $this->_formHelper = $this->getHelper('ajaxForm');
        $this->_pageType = new Cms_Model_PageType();
        Cms_Model_PageTypeMapper::getInstance()->findByCode(self::PAGE_TYPE_CODE, $this->_pageType);        
    }

    public function pageEditPrivateAction(){        
     
        Cms_Model_PageTypeMapper::getInstance()->findByCode(self::PAGE_TYPE_CODE_PRIVATE, $this->_pageType);
        $this->pageEditAction();        
    }

    public function pageEditAction(){
        $data = $this->getRequest()->getPost('data');
        $submit = $this->getRequest()->getPost('submit');
        $id = $this->_getParam('id');
        $typeId = $this->_getParam('type_id');
        $langFilter = $this->_getParam('langFilter');

        //check if cancel button is pressed
        if($this->_formHelper->isCancel()) {
            //cancel form
            return $this->_formHelper->returnCancel($this->view->url(array('module'=>'cms','controller'=>'admin','action' => 'page')), $this->translate('Action canceled'));
        }        
        
        //create form object
        $form = new Download_Form_Page($data);        

        //postback - save?
        if ($this->_formHelper->isSave()) {            
            //check if valid
            if($form->isValid()) {                
                $values = $form->getValues();                
                //create entity object from submitted values, and save
                $page = new Cms_Model_Page($values);
                //new entity
                if(!isset ($data['id']) || $data['id'] <= 0){
                    $page   ->set_application_id($this->_applicationId)
                            ->set_user_id($this->_admin->get_id())
                            ->set_posted(HCMS_Utils_Time::timeTs2Mysql(time()))
                            ->set_type_id($this->_pageType->get_id())
                            ->set_format('html');
                }
                else{                    
                    $existingPage = new Cms_Model_Page();
                    if(!Cms_Model_PageMapper::getInstance()->find($data['id'], $existingPage)){
                        throw new Exception("Download not found");
                    }
                    if((int)$existingPage->get_application_id() != $this->_applicationId){
                        throw new Exception("Cannot edit this Download.");
                    }                    
                }

                $bootstrap = $this->getInvokeArg('bootstrap');
                $options = $bootstrap->getOptions();

                $data = $page->get_data();
                $data["filesize"] = filesize(realpath($options["fileserver"]["root"]. '/1/' . $page->get_data("file")));
                $page->set_data($data);
                Cms_Model_PageMapper::getInstance()->save($page,$langFilter);
                //save categories
                if(isset ($data['categories'])){
                    Cms_Model_PageMapper::getInstance()->saveCategories($page, $data['categories']);
                }
                //save done, return success
                return $this->_formHelper->returnSuccess($this->view->url(array('action' => 'page')), $this->translate('Page saved.'));
            }
            else {
                //we have errors - return json or continue
                $this->_formHelper->returnError($form->getMessages());
            }
        }
        //first run of the form - grab data from mapper
        elseif(!$this->_formHelper->getRequest()->isPost()) {
            //edit action
            if(isset ($id) && $id > 0) {
                $page = new Cms_Model_Page();
                if(!Cms_Model_PageMapper::getInstance()->find($id, $page, $langFilter)){
                    throw new Exception("Download not found");
                }
                //fetch data
                $data = $page->toArray();
                //populate form with data
                $form->setData($data);
            }
            else{
                $this->view->typeId = $this->_pageType->get_id();                
            }
        }
        $this->view->typeName = $this->_pageType->get_name();
        $this->view->data = $data;
        $this->view->types = Cms_Model_PageTypeMapper::getInstance()->fetchAll();
        $this->view->typeId = $this->_pageType->get_id();
    }   

}