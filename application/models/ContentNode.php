<?php
require_once 'Zend/Db/Table/Abstract.php';
require_once APPLICATION_PATH.'/models/Page.php';
class Model_ContentNode extends Zend_Db_Table_Abstract
{
	protected $_name = 'content_nodes';
	
	protected $_referenceMap = array(
		'Page' => array(
			'columns' 	=> array('page_id'),
			'refTableClass' => 'Model_Page', 
			'refColumns'	=> array('id'), 
			'onDelete' 	=> self::CASCADE, 
			'onUpdate'	=> self::RESTRICT,
		),  
	);

	public function setNode($pageId, $node, $value)
	{
		$select = $this->select();
		$select->where('page_id = ?', $pageId);
		$row = $this->fetchRow($select);
		if (!$row) {
			$row = $this->createRow();
			$row->page_id = $pageId;
			$row->node = $node;
		}
		$row->content = $value;
		$row->save();
	}

	public function updatePage($id, $data)
	{
		$row = $this->find($id)->current();
		if ($row) {
			$row->name = $data['name'];
			$row->parent_id = $data['parent_id'];
			$row->save();
			unset($data['id'], $data['name'], $data['parent_id']);
			if (count($data) > 0) {
				$modelContentNode = new Model_ContentNode();
				foreach ($data as $key => $value) {
					$modelContentNode->setNode($id, $key, $value);
				}
			} else {
				throw new Zend_Exception('Could not open page to update!');
			}
		}
	}
}

