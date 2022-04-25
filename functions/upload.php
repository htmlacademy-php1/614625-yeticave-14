<?php
/**
 * функция перемещает файл из временной папки загрузки на сервере, в папку с проектом и проверяет файл
 * на совпадение имени в перемещаемой директории, если имя совпадает, добавляется 1 в начало имени, пока имя не
 * перестанет совпадать
 * @param array $file входящий массив с файлом
 * @return string путь к файлу
 */
function uploadFile(array $file) :string
{
    $destination = 'img/' . $file['img']['name'];
    while(file_exists($destination)){
        $destination = 'img/' . '1' . $file['img']['name'];
        $file['img']['name'] = '1' . $file['img']['name'];
    };
    if(move_uploaded_file($file['img']['tmp_name'], $destination )){
        return $destination;
    };
    exit("Ошибка при загрузке файла");
}
