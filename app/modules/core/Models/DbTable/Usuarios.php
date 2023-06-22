<?php

/**
 *
 */

class Core_Model_DbTable_Usuarios extends Db_Table
{
    protected $_name = 'usuarios';
    protected $_id = 'id';

    public function changePassword($id, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
         $edit = "UPDATE " . $this->_name . " SET clave_principal = '" . $password . "'  WHERE id = '" . $id . "'";
        $this->_conn->query($edit);
    }

    public function searchUser($id)
    {
        $res = $this->_conn->query('SELECT * FROM ' . $this->_name . ' WHERE id = "' . $id . '"')->fetchAsObject();
        return $res;
    }

    public function searchUserByUser($user)
    {
        $res = $this->_conn->query('SELECT * FROM ' . $this->_name . ' WHERE usuario = "' . $user . '"')->fetchAsObject();
        if (isset($res[0])) {
            $res = $res[0];
        } else {
            $res = false;
        }
        return $res;
    }
    public function getById($id)
    {
        $res = $this->_conn->query('SELECT * FROM ' . $this->_name . ' WHERE ' . $this->_id . ' = "' . $id . '"')->fetchAsObject();
        if (isset($res[0])) {
            return $res[0];
        }
        return false;
    }

    public function autenticateUser($user, $password)
    {
        $resUser = $this->searchUserByUser($user);
        if ($resUser->id) {
            if(password_verify($password,$resUser->clave_principal)){
                return  true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function loginUser($user, $password)
    {
        $res = $this->_conn->query('SELECT * FROM ' . $this->_name . ' WHERE id = "' . $user . '"')->fetchAsObject();
        return $res[0];
    }

    public function editCode($id, $code)
    {
        $edit = "UPDATE " . $this->_name . " SET code = '" . $code . "' WHERE id = '" . $id . "'";
        $this->_conn->query($edit);
    }
}
