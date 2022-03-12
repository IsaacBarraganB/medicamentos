<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Medicine extends BaseController
{
    protected $modelName = 'App\Models\Medicine';
    protected $format    = 'json';
    public $content = ['result' => false, 'message' => ['title' => 'Error!', 'content' => 'Internal Server Error.']];
    
    public function getMedicine()
    {
        $db = \Config\Database::connect();
        $query   = $db->query('SELECT * FROM medicine');
        $results = $query->getResult();
        $this->content['myData'] = $results;
        //$this->content['myData'] = $results;echo 'Total Results: ' . count($results);
        return $this->response->setJSON($this->content);
    }

    public function getMedicineById($id) {
        $db = \Config\Database::connect();
        $query   = $db->query("SELECT * FROM medicine where id = $id");
        $results = $query->getResult();
        $this->content['myData'] = $results;
        //$this->content['myData'] = $results;echo 'Total Results: ' . count($results);
        return $this->response->setJSON($this->content);
    }

    public function create(){
        $request = \Config\Services::request()->getPost();
        $db = \Config\Database::connect();
        $name = $request['name'];
        //$query   = $this->db->query("INSERT INTO medicine (name) VALUES ($name)");
        if ($db->simpleQuery("INSERT INTO medicine (name) VALUES ('".$name."')")) {
            $this->content['result'] = true;
            $this->content['message'] = 'Se registró correctamente el medicamento.';
        }else {
            $this->content['result'] = false;
            $this->content['message'] = 'No se pudo registrar el medicamento.';
        }

        return $this->response->setJSON($this->content);
    }

    public function update () {
        $request = \Config\Services::request()->getPost();
        $db = \Config\Database::connect();
        $name = $request['name'];
        $id = $request['id'];
        if ($db->simpleQuery("UPDATE medicine SET name = '".$name."' WHERE id = $id")) {
            $this->content['result'] = true;
            $this->content['message'] = 'Se actualizó correctamente el medicamento.';
        }else {
            $this->content['result'] = false;
            $this->content['message'] = 'No se pudo actualizar el medicamento.';
        }

        return $this->response->setJSON($this->content);
    }

    public function delete($id){
        $db = \Config\Database::connect();
        if ($db->simpleQuery("DELETE FROM medicine WHERE id = $id")) {
            $this->content['result'] = true;
            $this->content['message'] = 'Se registró correctamente el medicamento.';
        }else {
            $this->content['result'] = false;
            $this->content['message'] = 'No se pudo registrar el medicamento.';
        }

        return $this->response->setJSON($this->content);
    }
}