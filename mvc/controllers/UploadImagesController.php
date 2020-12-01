<?php	

class UploadImagesController extends Controller{
    protected $_responsive;
    protected $_imagesModel;
    public function __construct()
    {
        $this->_responsive = new Responsive();
        $this->_imagesModel = $this->model('ImagesModel');
    }
    // Phương thức index
    public function index($params){
        echo $this->view('dropzone');
    }

    public function fetch($params)
    {
        $images = $this->_imagesModel->getAll();
        echo $this->view('list_images', ['items' => $images]);
    }

    public function upload($params)
    {
        $files = isset($_FILES['file']) ? $_FILES['file'] : [];
        $arrFileName = isset($files['name']) ? $files['name']: [];
        $flagAllow = true;
        $arrData = [];
        foreach ($arrFileName as $fileName) {
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowExt = explode(',', FILE_EXTENSION);
            if (!in_array($ext, $allowExt)) {
                $flagAllow = false;
            }
        }
        if($flagAllow) {
            foreach ($arrFileName as $k => $fileName) {
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                $name = uniqid().'.'.$ext;
                $image_path = PATH_ROOT.'/public/uploads/'. $name;
                if(move_uploaded_file($files['tmp_name'][$k], $image_path)) {
                    $imageData = [
                        'name'          => $name,
                        'image_path'     => 'uploads/' . $name,
                        'created_at'    => date('yyyy-mm-dd'),
                        'updated_at'    => date('yyyy-mm-dd'),
                    ];
                    array_push($arrData, $imageData);
                }
            }
            foreach ($arrData as $k => $image) {
                try {
                    $this->_imagesModel->addImages($image);
                } catch (\Exception $th) {
                }
            }
            return $this->_responsive->success([]);
        } else {
            return $this->_responsive->error(400);
        }
    }

    public function delete($params)
    {
        $id_images = isset($params['images_id']) ? $params['images_id'] : '';
        try {
            $this->_imagesModel->deleteImage($id_images);
            return $this->_responsive->success([]);
        } catch (\Throwable $th) {
            return $this->_responsive->error(500);
        }
    }

    public function review()
    {
        $images = $this->_imagesModel->getAll();
        echo $this->view('review', ['images' => $images]);
    }
}
?>