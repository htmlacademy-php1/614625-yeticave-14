<?php
function uploadFile(array $file){
    print_r('<pre>');
    var_dump($file['img']['name']);
    print_r('</pre>');
    $filename = $file['img']['name'];
    validateFileName($filename);
    exit();
    $destination = 'img/' . $file['img']['name'];
    $moveResult = move_uploaded_file($file['img']['tmp_name'], $destination );
    //var_dump($moveResult);
    //exit();
    return $destination;
}
