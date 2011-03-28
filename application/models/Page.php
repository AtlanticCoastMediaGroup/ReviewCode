<?php
require_once 'Zend/Db/Table/Abstract.php';
require_once APPLICATION_PATH.'/models/ContentNode.php';
class Model_Page extends Zend_Db_Table_Abstract
{
	protected $_name = 'pages';
	
	protected $_dependentTables = array('Model_ContentNode');

	protected $_referenceMap = array(
		'Page' =>  array(
			'columns' 	=> array('parent_id'), 
			'refTableClass' => 'Model_Page', 
			'refColumns'	=> array('id'), 
			'onDelete'	=> self::CASCADE, 
			'onUpdate'	=> self::RESTRICT,
		),
	);
	public function createPage($name, $namespace, $parentId = 0, $headline, $image = '', $description, $content)
	{
		$row = $this->createRow();
		$row->name = $name;
		$row->namespace = $namespace;
		$row->parent_id = $parentId;
		$row->headline = $headline;
		$row->image = $image;
		$row->description = $description;
		$row->content = $content;
		$row->date_created = time();
		$row->save();
		$id = $this->_db->lastInsertId();
		return $id;
	}

	public function deletePage($id)
	{
		$row = $this->find($id)->current();
		if ($row) {
			$row->delete();
			return true;
		} else {
			throw new Zend_Exception('Delete function failed; could not find page!');
		}
	}

	public function getRecentPages($count = 10, $namespace = 'page')
	{
		$select = $this->select();
		$select->order = 'date_created DESC';
		$select->where('namespace = ?', $namespace);
		$select->limit($count);
		$results = $this->fetchAll($select);
		if ($results->count() > 0) {
			$pages = array();
			foreach ($results as $result) {
				$pages[$result->id] = new CMS_Content_Item_Page($result->id);
			}
			return $pages;
		} else {
			return null;
		}
	}
}

