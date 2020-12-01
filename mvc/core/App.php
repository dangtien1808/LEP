<?php
class App{

    protected $controller="UploadImagesController";
    protected $action="index";
    protected $params=[];

    function __construct(){
 
        if(isset($_GET['url'])) {
            switch ($_GET['url']) {
                case 'upload':
                    $this->controller = 'UploadImagesController';
                    $this->action = 'index';
                break;
                case 'review':
                    $this->controller = 'UploadImagesController';
                    $this->action = 'review';
                break;
                case 'images/fetch':
                    $this->controller = 'UploadImagesController';
                    $this->action = 'fetch';
                break;
                case 'upload/handle':
                    $this->controller = 'UploadImagesController';
                    $this->action = 'upload';
                    break;
                case 'images/delete':
                    $this->controller = 'UploadImagesController';
                    $this->action = 'delete';
                break;
            }
        }
        $pathFile = PATH_APPLICATION . "controllers". DS . $this->controller . ".php";
        if(file_exists($pathFile)) {
            require_once $pathFile;
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->params = array_merge($_POST, $_FILES);
            } else {
                $this->params = $_GET;
            }
            $controller = new $this->controller;
            if (method_exists($controller, $this->action) == true) {
                $result = $controller->{$this->action}($this->params);
                if(!empty($result)) {
                    echo json_encode($result);
                }
            }
        }
    }
}
?>