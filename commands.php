<?php

// ================== CONFIG ==================
$storagePath = __DIR__ . "/files/";
if (!file_exists($storagePath)) mkdir($storagePath, 0777, true);

// ================== ADMIN ==================
function isAdmin($role) {
    return $role === "admin"; 
}
// ================== SECURITY ==================
function sanitizePath($basePath, $filename) {
    $realBase = realpath($basePath);
    $realPath = realpath($basePath . $filename);
    if ($realPath === false || strpos($realPath, $realBase) !== 0) {
        return false;
    }
    return $realPath;
}
// ================== FILE SYSTEM ==================
function listFiles($path) {
    $files = array_diff(scandir($path), array('.', '..'));
    return empty($files) ? "No files found" : implode("\n", $files);
}

function readFileContent($path, $filename) {
    $file = sanitizePath($path, $filename);
    if (!$file || !file_exists($file)) return " File not found";
    return file_get_contents($file);
}
function uploadFile($path, $filename, $content) {
    if (empty($filename)) return " Filename required";

    $file = $path . basename($filename);

    if (file_exists($file)) {
        return " File already exists";
    }

    file_put_contents($file, $content);
    return " File uploaded successfully";
}
function downloadFile($path, $filename) {
    $file = sanitizePath($path, $filename);
    if (!$file || !file_exists($file)) return " File not found";
    return "FILE|" . file_get_contents($file);
}

function deleteFile($path, $filename) {
    $file = sanitizePath($path, $filename);
    if (!$file || !file_exists($file)) return " File not found";
    unlink($file);
    return " File deleted";
}

function searchFiles($path, $keyword) {
    if (empty($keyword)) return " Keyword required";

    $files = array_diff(scandir($path), array('.', '..'));
    $results = array_filter($files, fn($f) => stripos($f, $keyword) !== false);

    return empty($results) ? "No matches" : implode("\n", $results);
}
function fileInfo($path, $filename) {
    $file = sanitizePath($path, $filename);
    if (!$file || !file_exists($file)) return "File not found";

    $size = filesize($file);
    $created = date("Y-m-d H:i:s", filectime($file));
    $modified = date("Y-m-d H:i:s", filemtime($file));

    return " File: $filename\n Size: $size bytes\n Created: $created\n Modified: $modified";
}

// ================== COMMAND HANDLER ==================
function handleCommand($input, $role, $path) {
    $parts = explode(" ", trim($input));
    $command = strtolower($parts[0]);
    $args = array_slice($parts, 1);

    $admin = isAdmin($role);

    switch ($command) {

        case "/list":
            return listFiles($path);

        case "/read":
            if (empty($args[0])) return "Filename required";
            return readFileContent($path, $args[0]);
        case "/upload":
            if (!$admin) return "Permission denied";

            $raw=trim(substr($input , 7));
            $data=explode("|", $raw, 2);

            if (count($data) < 2) return " Format: /upload file|content";
            return uploadFile($path, trim($data[0]),$data[1]);

        case "/download":
            if (empty($args[0])) return " Filename required";
            return downloadFile($path, $args[0]);

        case "/delete":
            if (!$admin) return " Permission denied";
            if (empty($args[0])) return "Filename required";
            return deleteFile($path, $args[0]);

        case "/search":
            if (empty($args[0])) return " Keyword required";
            return searchFiles($path, $args[0]);

        case "/info":
            if (empty($args[0])) return "Filename required";
            return fileInfo($path, $args[0]);

        default:
            return " Unknown command"; 
    }    
}

?>

    


