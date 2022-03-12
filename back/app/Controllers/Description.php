<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Description extends BaseController
{
    protected $modelName = 'App\Models\Description';
    protected $format    = 'json';
    public $content = ['result' => false, 'message' => ['title' => 'Error!', 'content' => 'Internal Server Error.']];
    
    
    public function getAllDescription()
    {
        $db = \Config\Database::connect();
        $query   = $db->query('SELECT * FROM descriptions');
        $results = $query->getResult();
        $this->content['myData'] = $results;
        //$this->content['myData'] = $results;echo 'Total Results: ' . count($results);
        return $this->response->setJSON($this->content);
    }

    public function getMedicineOfDescription($id)
    {
        $db = \Config\Database::connect();
        $query   = $db->query("SELECT * FROM medicine where id = $id");
        $results = $query->getResult();
        $this->content['myData'] = $results;
        //$this->content['myData'] = $results;echo 'Total Results: ' . count($results);
        return $this->response->setJSON($this->content);
    }

    public function getDescriptionById($id) {
        $db = \Config\Database::connect();
        $query   = $db->query("SELECT * FROM descriptions where medicine_id = $id");
        $results = $query->getResult();
        $this->content['myData'] = $results;
        //$this->content['myData'] = $results;echo 'Total Results: ' . count($results);
        return $this->response->setJSON($this->content);
    }

    public function create(){
        try {
            //code...
            $request = \Config\Services::request()->getPost();
            $db = \Config\Database::connect();
            $description = $request['description'];
            $id = $request['medicine_id'];
            if ($db->simpleQuery("INSERT INTO descriptions (descriptions, medicine_id) VALUES ('".$description."', $id)")) {
                $this->content['result'] = true;
                $this->content['message'] = 'Se registró correctamente la descripción.';
            }else {
                $this->content['result'] = false;
                $this->content['message'] = 'No se pudo registrar la descripción.';
            }
                
            } catch (\Exception $th) {
                //throw $th;
                $this->content['message'] = $th;
            }
        

        return $this->response->setJSON($this->content);
    }

    public function update () {
        $request = \Config\Services::request()->getPost();
        $db = \Config\Database::connect();
        $description = $request['description'];
        $id = $request['id'];
        if ($db->simpleQuery("UPDATE descriptions SET descriptions = '".$description."' WHERE id = $id")) {
            $this->content['result'] = true;
            $this->content['message'] = 'Se actualizó correctamente la descripción.';
        }else {
            $this->content['result'] = false;
            $this->content['message'] = 'No se pudo actualizar la descripción.';
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