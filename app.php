<?php
    class App {
        function __construct()
        {

            if(file_exists('./controllers/UploadImagesController.php')) {
                require './controllers/UploadImagesController.php';
            }
            $this->urlProcess();
        }
        public function urlProcess()
        {
            if(isset($_GET['url'])) {
                switch ($_GET['url']) {
                    case 'upload':
                        if(method_exists('UploadImagesController', 'index')) {
                            call_user_func_array(['UploadImagesController', 'index'], []);
                        }
                        break;
                    
                    case 'images/fetch':
                        if(method_exists('UploadImagesController', 'fetch')) {
                            call_user_func_array(['UploadImagesController', 'fetch'], []);
                        }
                        break;
                
                    case 'upload/handle':
                        if(method_exists('UploadImagesController', 'upload')) {
                            call_user_func_array(['UploadImagesController', 'upload'], []);
                        }
                        break;
                    case 'upload/handle':
                        if(method_exists('UploadImagesController', 'upload')) {
                            call_user_func_array(['UploadImagesController', 'upload'], []);
                        }
                        break;
                }
            }
//             $route->get('upload_images', '/upload', 'UploadImages@index');
// $route->post('upload_images.handle', '/upload/handle', 'UploadImages@upload');
// $route->get('upload_images.fetch', '/images/fetch', 'UploadImages@fetch');
// $route->post('upload_images.delete', '/images/delete', 'UploadImages@delete');

        }
    }
?>