<?php
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class BugControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
	private $pdo;
	
    public function setUp()
    {
        /* Setup Routine */
        $this->bootstrap = array($this, 'appBootstrap');
        require_once 'Phactory/lib/Phactory.php';
		$this->pdo = new PDO('mysql:host=localhost; dbname=zf_cms', 'root', 'root');
		parent::setUp();
    }
    
    public function appBootstrap()
	{
	  	$this->application = new Zend_Application(APPLICATION_ENV,APPLICATION_PATH . '/configs/application.ini');
	  	$this->application->bootstrap();
	}
	
	public function testSubmitAction()
    {
		$arrInput = array(
			'author' 	=> 'Amit Gharat 1', 
			'email' 	=> 'amit1.2006.it@gmail.com', 
			'date' 		=> '13-23-2011', 
			'url'		=> 'http://amitgharat1.wordpress.com', 
			'priority' 	=> 'low',
			'status'	=> 'old',
		);
		$this->getRequest()
        	 ->setParams($arrInput)
        	->setMethod('POST');
        $this->dispatch('bug/submit');
        $this->assertController('bug');
        $this->assertAction('submit');       
        
        Phactory::setConnection($this->pdo);
		Phactory::define('bug', array('status' => 'A'));
        $new_bug = Phactory::create('bug', $arrInput);
        $this->assertGreaterThanOrEqual(1, $new_bug->id);
        
        //require './TestConfiguration.php';
		//require '../application/models/Bug.php';  
        //$bugModel = new Model_Bug();
		
    }
    
    public function testListAction()
    {
        // get the filter form
		$listToolsForm = new Form_BugReportListToolsForm();
		//$listToolsForm->setAction(APPLICATION_URL.'/public/bug/list');
		//$listToolsForm->setMethod('post');
		//$this->view->listToolsForm = $listToolsForm;
		
		$sort = $this->_request->getParam('sort', 'priority');
		$filterField = $this->_request->getParam('filter_field', 'priority');
		$filterValue = $this->_request->getParam('filter', 'Amit');
		if(!empty($filterField)) {
			$filter[$filterField] = $filterValue;
			$this->assertEquals(1, count($filter));
		}else{
			$filter = null;
			$this->assertNull($filter);
		}

		//$listToolsForm->getElement('sort')->setValue($sort);
		//$listToolsForm->getElement('filter_field')->setValue($filterField);
		//$listToolsForm->getElement('filter')->setValue($filterValue);

		//$bugModels = new Model_Bug();
		//$adapter = $bugModels->fetchPaginatorAdapter($filter, $sort);
		//$paginator = new Zend_Paginator($adapter);

		//$paginator->setItemCountPerPage(2);

		$page = $this->_request->getParam('page', 1);
		$this->assertGreaterThanOrEqual(1, $page);
		//$paginator->setCurrentPageNumber($page);
		//$this->view->paginator = $paginator;
    }
	
	public function testEditAction()
    {
		$arrInput = array(
			'author' 	=> 'Amit Gharat', 
			'email' 	=> 'amit.2006.it@gmail.com', 
			'date' 		=> '12-23-2011', 
			'url'		=> 'http://amitgharat.wordpress.com', 
			'priority' 	=> 'high',
			'status'	=> 'new',
		);
		$this->getRequest()
        	 ->setParams($arrInput)
        	->setMethod('POST');
		$this->getRequest()->setParam('id', 1);
		
		//$bugModel = new Model_Bug();
		$bugReportForm = new Form_BugReportForm();
		//$bugReportForm->setAction(APPLICATION_URL.'/public/bug/edit/');
		//$bugReportForm->setMethod('post');
		$this->assertTrue($this->getRequest()->isPost());
		if ($this->getRequest()->isPost()) {
			$this->assertTrue($bugReportForm->isValid($arrInput));
			if ($bugReportForm->isValid($arrInput)) {
				//$result = $bugModel->updateBug($bugReportForm->getValues());
				//return $this->_forward('list');
			}
		} elseif ($this->_request->getParam('id')) {
			$this->assertGreaterThan(0, $this->_request->getParam('id'));
			
			$id = $this->_request->getParam('id');
			//$bug = $bugModel->find($id)->current();
			//$bugReportForm->populate($bug->toArray());
			//$bugReportForm->getElement('date')->setValue(date('m-d-Y', $bug->date));
			//$this->view->bug = $bug;
			//$this->view->form = $bugReportForm;
		} else {
			//$this->_redirect('/bug/list');
		}
    }
	
    public function tearDown()
    {
        /* Tear Down Routine */
    }


}

