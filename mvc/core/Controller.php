<?php
class Controller{

    public function model($model){
        require_once PATH_APPLICATION . "models" . DS. $model . ".php";

        return new $model;
    }

    public function view($view, $data=[]){
        extract($data);
        require_once PATH_APPLICATION . "views/".$view.".php";
    }

}
?>