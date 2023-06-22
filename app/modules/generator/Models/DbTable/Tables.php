<?php

/**
*
*/

class Generator_Model_DbTable_Tables extends Db_Table
{
	protected $_name = 'user';
    protected $_id = 'user_id';

    public function getTables()
    {
        $res = $this->_conn->query('SHOW FULL TABLES')->fetchAsObject();
        return $res;
    }

    public function getCampos($table){
      if($table){
        $res = $this->_conn->query('SHOW FULL COLUMNS FROM '.$table)->fetchAsObject();
        return $res;
      }
    }


}