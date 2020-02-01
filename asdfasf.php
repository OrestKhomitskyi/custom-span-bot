<?php

$filePath = 'file_6.jpg';
$fullPath = "temp/".$filePath;

$photo = file_get_contents("https://api.telegram.org/file/bot399359167:AAG77kgiiHyAjTt37Y-oi8sGI64w1X89FdU/photos/".$filePath);

file_put_contents($fullPath, $photo);
