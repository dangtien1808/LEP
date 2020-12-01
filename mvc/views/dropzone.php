<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dropzone Image</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="<?= PATH_PUBLIC ?>plugins/css/bootstrap.css" rel="stylesheet" type="text/css" >
        <link href="<?= PATH_PUBLIC ?>plugins/css/dropzone.css" rel="stylesheet" type="text/css" >
        
        <link href="<?= PATH_PUBLIC ?>css/app.css" rel="stylesheet" type="text/css" >

        <script src='<?= PATH_PUBLIC ?>plugins/js/jquery.js'></script>
        <script src='<?= PATH_PUBLIC ?>plugins/js/bootstrap.js'></script>
        <script src='<?= PATH_PUBLIC ?>plugins/js/dropzone.js'></script>
    </head>
    <body>
        <div class="container-fluid mt-2">
            <div class="card mb-2">
                <div class="card-header">
                    <h5 class="card-title text-center">Upload Images</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 ">
                        <div class="col-auto dropzone-previews d-flex">
                        </div>
                        <div class="col-1">
                            <form action="<?= BASE_NAME . 'upload/handle'?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload">
                            </form>
                        </div>
                    </div>
                    <div class="row mb-3">
                    </div>
                    <div class="row mb-3 d-flex justify-content-center">
                        <input type="button" id="upload-image" value="Upload Images"/>
                    </div>
                    <div id="preview-template" style="display: none;">
                        <div class="dz-preview dz-file-preview mr-1 ml-1">
                            <div class="dz-details">
                                <div class="dz-image">
                                    <img data-dz-thumbnail />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header">
                    <h5 class="card-title text-center">List Images</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 list-images">
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        var url_images_fetch = '<?= BASE_NAME . 'images/fetch'?>';
        var url_images_delete = '<?= BASE_NAME . 'images/delete'?>';
    </script>
    <script src='<?= PATH_PUBLIC ?>js/app.js'></script>
</html>

