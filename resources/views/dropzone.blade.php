<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{csrf_token()}}" />

        <title>Dropzone Image</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ asset('dist/plugins/css/bootstrap.css') }}" rel="stylesheet" type="text/css" >
        <link href="{{ asset('dist/plugins/css/dropzone.css') }}" rel="stylesheet" type="text/css" >

        <script src="{{asset('dist/plugins/js/jquery.js')}}"></script>
        <script src="{{asset('dist/plugins/js/bootstrap.js')}}"></script>
        <script src="{{asset('dist/plugins/js/dropzone.js')}}"></script>

    </head>
    <body>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">upload Images</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 ">
                        <div class="col-auto dropzone-previews d-flex">

                        </div>
                        <div class="col-1">
                            <form action="{{route('upload.handle')}}" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload">
                                @csrf
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
            <div class="card">
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
</html>
<script type="text/javascript">
    $( document ).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        loadImages();
    });
  
    Dropzone.options.imageUpload = {
        autoProcessQueue: false,
        addRemoveLinks: true, 
        uploadMultiple: true,
        maxFiles: 5,
        parallelUploads: 5,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        previewsContainer: ".dropzone-previews",
        previewTemplate: document.getElementById('preview-template').innerHTML,
        dictRemoveFileConfirmation: "Are you sure you want to remove this File?",
        init: function() {
            let that = this;
            let submit_btn = $('#upload-image');
            submit_btn.click(() => {
                that.processQueue();
            });
            that.on("maxfilesexceeded", function(file) {
                that.removeFile(file);
            });
            that.on('successmultiple', function(file, response){
                that.removeAllFiles();
                console.log('success', file, response);

            });
            that.on('errormultiple', function(file, response){
                console.log('error', file, response);
            });
            that.on('completemultiple', function() {
                loadImages();
            })
        }
    }

    function loadImages() {
        $.ajax({
            url:"{{route('upload.fetch')}}",
            success: function(res) {
                $('.list-images').html(res.html);
                initDelete();
            },
            error: function(res) {
                console.log(res);
            }
        });
    }

    function initDelete() {
        $('.delete-images').each((k,v) => {
            let element = $(v);
            let id = element.data('id');
            element.click(() => {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('images.delete')}}",
                    method: "POST",
                    data: {
                        images_id: id
                    },
                    success: function(res) {
                        loadImages();
                    },
                    error: function(res) {
                        console.log(res);
                    }
                })
            });
        });
    }
</script>