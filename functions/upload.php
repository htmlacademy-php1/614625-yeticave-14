<?php
function uploadFile(array $file){
    $destination = 'img/' . $file['img']['name'];
    move_uploaded_file($file['img']['tmp_name'], $destination );
    return $destination;
}
