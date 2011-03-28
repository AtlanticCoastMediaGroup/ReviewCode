<?php

class MenuController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        	$mdlMenu = new Model_Menu();
        	$this->view->menus = $mdlMenu->getMenus();
    }

    public function createAction()
    {
        // action body
                        	$frmMenu = new Form_Menu();
                        	if ($this->getRequest()->isPost()) {
                        		if ($frmMenu->isValid($_POST)) {
                        			$menuName = $frmMenu->getValue('name');
                        			$mdlMenu = new Model_Menu();
                        			$result = $mdlMenu->createMenu($menuName);
                        			if ($result) {
                        				$this->_redirect('/menu/index');
                        			}
                        		}
                        	}
                        	$frmMenu->setAction(APPLICATION_URL.'/public/menu/create');
                        	$this->view->form = $frmMenu;
    }

    public function editAction()
    {
        // action body
                	$id = $this->_request->getParam('id');
                	$mdlMenu = new Model_Menu();
                	$frmMenu = new Form_Menu();
                	if ($this->getRequest()->isPost()) {
                		if ($frmMenu->isValid($_POST)) {
                			$menuName = $frmMenu->getValue('name');
                			$mdlMenu = new Model_Menu();
                			$result = $mdlMenu->updateMenu($id, $menuName);
                			if ($result) {
                				return $this->_forward('index');
                			}
                		}
                	} else {
                		$currentMenu = $mdlMenu->find($id)->current();
                		$frmMenu->populate($currentMenu->toArray());
                		
                	}
                	$frmMenu->setAction(APPLICATION_URL.'/public/menu/edit');
                	$this->view->form = $frmMenu;
    }

    public function deleteAction()
    {
        // action body
        	$id = $this->_request->getParam('id');
        	$mdlMenu = new Model_Menu();
        	$mdlMenu->deleteMenu($id);
        	$this->_forward('index');
    }

    public function renderAction()
    {
        // action body
	$menu = $this->_request->getParam ( 'menu' );
	$mdlMenuItems = new Model_MenuItem ( );
	$menuItems = $mdlMenuItems->getItemsByMenu ( $menu );
	if(count($menuItems) > 0) {
		foreach ($menuItems as $item) {
			$label = $item->label;
			if(!empty($item->link)) {
				$uri = $item->link;
			}else{
			$uri = '/page/open/id/' . $item->page_id;
		}
		$itemArray[] = array('label' => $label, 'uri' => $uri);
	}
	$container = new Zend_Navigation($itemArray);
	$this->view->navigation()->setContainer($container);
}

    }


}









