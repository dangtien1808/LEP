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
        
        <link href="<?= PATH_PUBLIC ?>css/review.css" rel="stylesheet" type="text/css" >

        <script src='<?= PATH_PUBLIC ?>plugins/js/jquery.js'></script>
        <script src='<?= PATH_PUBLIC ?>plugins/js/bootstrap.js'></script>
        <script src='<?= PATH_PUBLIC ?>plugins/js/dropzone.js'></script>
    </head>
    <body class="review">
    </body>

    <script type="text/javascript">
        var url_images_fetch = '<?= BASE_NAME . 'images/fetch'?>';
        var url_images_delete = '<?= BASE_NAME . 'images/delete'?>';
        var url_store = '<?= PATH_PUBLIC ?>';
    </script>
    <script src='<?= PATH_PUBLIC ?>js/review.js'></script>
</html>

