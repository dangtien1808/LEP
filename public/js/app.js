$( document ).ready(function() {
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
        url:"images/fetch",
        success: function(res) {
            $('.list-images').html(res);
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
            $.ajax({
                url: "images/delete",
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