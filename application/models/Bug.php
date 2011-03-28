<?php
	class Model_Bug extends Zend_Db_Table_Abstract
	{
		protected $_name = 'bugs';
		
		public function createBug($arrBugDetails)
		{
			$row = $this->createRow();
			$row->author = $arrBugDetails['author'];
			$row->email = $arrBugDetails['email'];
			$dateObject = new Zend_Date($arrBugDetails['date']);
			$row->date = $dateObject->get(Zend_Date::TIMESTAMP);
			$row->url = $arrBugDetails['url'];
			$row->description = $arrBugDetails['description'];
			$row->priority = $arrBugDetails['priority'];
			$row->status = $arrBugDetails['status'];
			$row->save();
			$id = $this->_db->lastInsertId();
			return $id;
		}
		
		public function updateBug($arrBugDetails)
		{
			$row = $this->find($arrBugDetails['id'])->current();
			if ($row) {
				$row->author = $arrBugDetails['author'];
				$row->email = $arrBugDetails['email'];
				$dateObject = new Zend_Date($arrBugDetails['date']);
				$row->date = $dateObject->get(Zend_Date::TIMESTAMP);
				$row->url = $arrBugDetails['url'];
				$row->description = $arrBugDetails['description'];
				$row->priority = $arrBugDetails['priority'];
				$row->status = $arrBugDetails['status'];
				$row->save();
				return true;
			} else {
				throw new Zend_Exception('Update function failed; could not find row!');
			}
		}

		/*public function fetchBugs($filters = array(), $sortField = null, $limit = null, $page = 1)
		{
			$select = $this->select();
			if (count($filters) > 0) {
				foreach ($filters as $field => $filter) {
					$select->where($field.' = ?', $filter);
				}
			}

			if ($sortField != null) {
				$select->order($sortField);
			}
			return $this->fetchAll($select);
		}*/
		
		public function fetchPaginatorAdapter($filters = array(), $sortField = null)
		{
			$select = $this->select();
			if (count($filters) > 0) {
				foreach ($filters as $field => $filter) {
					$select->where($field.' = ?', $filter);
				}
			}
			if (null != $sortField) {
				$select->order($sortField);
			}
			$adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
			return $adapter;
		}

		public function deleteBug($id)
		{
			$row = $this->find($id)->current();
			if ($row) {
				$row->delete();
				return true;
			} else {
				throw new Zend_Exception('Delete function failed; could not find row!');
			}
		}
	}
