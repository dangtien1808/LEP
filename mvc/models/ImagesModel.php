<?php
class ImagesModel extends Model{
    public function __construct()
    {   
        parent::__construct();
        $this->setTable('images');
    }

    public function getAll(){
        return $this->query()->fetchAll();
    }
    public function addImages($params){
        return $this->query([
                                'table' => 'images',
                                'data' => [
                                    'name'          => $params['name'],
                                    'image_path'    => $params['image_path'],
                                    'created_at'    => $params['created_at'],
                                    'updated_at'    => $params['updated_at'],
                                ]
                            ])
                            ->insert();
    }

    public function deleteImage($id)
    {
        $this->where('id', '=', $id)->delete();
    }
}
?>