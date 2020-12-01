<?php
    $html = [];
    foreach ($items as $key => $item) {
        array_push($html, '<div class="col-1 mb-3">');
        array_push($html,   '<div class="card">');
        array_push($html,       '<img src="'. PATH_PUBLIC . $item['image_path'] .'" class="card-img-top img-thumbnail" alt="'.$item['name'].'" style="height: 140px">');
        array_push($html,       '<div class="card-body text-center p-2">');
        array_push($html,           '<input type="button" value="Delete" class="btn btn-danger btn-sm delete-images" data-id="' . $item['id'] . '">');
        array_push($html,       '</div>');
        array_push($html,   '</div>');
        array_push($html, '</div>');
    }
    echo join('', $html);
?>