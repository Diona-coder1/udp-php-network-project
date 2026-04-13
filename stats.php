<?php
header('Content-Type: application/json');

function read_file($file) {
    if (!file_exists($file)) return [];
    return file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

?>