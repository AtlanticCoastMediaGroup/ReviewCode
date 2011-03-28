<?php

class PageController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
		$pageModel = new Model_Page();
		$recentPages = $pageModel->getRecentPages();
		if (is_array($recentPages)) {
			for ($i = 1; $i < 4; $i++) {
				if (count($recentPages) > 0) {
					$featuredItems[] = array_shift($recentPages);
				}
			}
			$this->view->featuredItems = $featuredItems;
			if (count($recentPages) > 0) {
				$this->view->recentPages = $recentPages;
			} else {
				$this->view->recentPages = null;
			}
		}
    }

    public function createAction()
    {
        // action body
                		$pageForm = new Form_PageForm();
                		if ($this->getRequest()->isPost()) {
                			if ($pageForm->isValid($_POST)) {
                				$itemPage = new CMS_Content_Item_Page();
                				$itemPage->name = $pageForm->getValue('name');
                				$itemPage->headline = $pageForm->getValue('headline');
                				$itemPage->description = $pageForm->getValue('description');
                				$itemPage->content = $pageForm->getValue('content');
                				if ($pageForm->image->isUploaded()) {
                					$pageForm->image->receive();
                					$itemPage->image = '/images/upload/'.basename($pageForm->image->getFileName());
                				}
                				$itemPage->save();
                				return $this->_forward('list');
                			}
                		}
                		$pageForm->setAction(APPLICATION_URL.'/public/page/create');
                		$this->view->form = $pageForm;
    }

    public function listAction()
    {
        // action body
		$pageModel = new Model_Page();
		$select = $pageModel->select();
		$select->order('name');
		$currentPages = $pageModel->fetchAll($select);
		if ($currentPages->count() > 0) {
			$this->view->pages = $currentPages;
		} else {
			$this->view->pages = null;
		}
    }

    public function editAction()
    {
        // action body
        		$id = $this->_request->getParam('id');
        		$itemPage = new CMS_Content_Item_Page($id);
        		$pageForm = new Form_PageForm();
        		$pageForm->setAction(APPLICATION_URL.'/public/page/edit');
        		if($this->getRequest()->isPost()) {
        			if($pageForm->isValid($_POST)) {
        				$itemPage->name = $pageForm->getValue('name');
        				$itemPage->headline = $pageForm->getValue('headline');
        				$itemPage->description = $pageForm->getValue('description');
        				$itemPage->content = $pageForm->getValue('content');
        				if($pageForm->image->isUploaded()){
        					$pageForm->image->receive();
        					$itemPage->image = '/images/upload/'.basename($pageForm->image->getFileName());
        				}
        				$itemPage->save();
        				return $this->_forward('list');
        			}
        		}
        		$pageForm->populate($itemPage->toArray());
        
        		$imagePreview = $pageForm->creatElement('image', 'image_preview');
        		$imagePreview->setLabel('Preview Image: ');
        		$imagePreview->setAttrib('style', 'width:200px;height:auto;');
        		$imagePreview->setOrder(4);
        		$imagePreview->setImage($itemPage->image);
        		$pageForm->addElement($imagePreview);
        		
        		$this->view->form = $pageForm;
    }

    public function deleteAction()
    {
        // action body
        		$id = $this->_request->getParam('id');
        		$itemPage = new CMS_Content_Item_Page($id);
        		$itemPage->delete();
        		return $this->_forward('list');
    }

    public function openAction()
    {
        // action body
		$id = $this->_request->getParam('id');
		$pageModel = new Model_Page();
		if (!$pageModel->find($id)->current()) {
			throw new Zend_Controller_Action_Exception('The page you requested was not found', 404);
		} else {
			$this->view->page = new CMS_Content_Item_Page($id);
		}
    }


}








