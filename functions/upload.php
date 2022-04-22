<?php
function uploadFile(array $file){
    $destination = 'img/' . $file['img']['name'];
    while(file_exists($destination)){
        $destination = 'img/' . '1' . $file['img']['name'];
        $file['img']['name'] = '1' . $file['img']['name'];
    };
    move_uploaded_file($file['img']['tmp_name'], $destination );
    return $destination;
}
