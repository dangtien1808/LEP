<?php	

namespace app\controllers;

use app\controllers;

class UploadImagesController extends Controller{
    // Phương thức index
    public function index(){
        echo $this->view('dropzone');
    }
}
?>