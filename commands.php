<?php

// ================== CONFIG ==================
$storagePath = __DIR__ . "/files/";

// ================== ADMIN ==================
function isAdmin($clientId) {
    return $clientId === "admin"; // change later if needed
}
// ================== SECURITY ==================
function sanitizePath($basePath, $filename) {
    $realPath = realpath($basePath . $filename);
    if ($realPath === false || strpos($realPath, realpath($basePath)) !== 0) {
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
    if (!$file || !file_exists($file)) return "❌ File not found";
    return file_get_contents($file);
}
function uploadFile($path, $filename, $content) {
    if (empty($filename)) return "❌ Filename required";

    $file = $path . basename($filename);

    if (file_exists($file)) {
        return "❌ File already exists";
    }

    file_put_contents($file, $content);
    return "✅ File uploaded successfully";
}
function downloadFile($path, $filename) {
    $file = sanitizePath($path, $filename);
    if (!$file || !file_exists($file)) return "❌ File not found";
    return file_get_contents($file);
}

function deleteFile($path, $filename) {
    $file = sanitizePath($path, $filename);
    if (!$file || !file_exists($file)) return "❌ File not found";
    unlink($file);
    return "✅ File deleted";
}

function searchFiles($path, $keyword) {
    if (empty($keyword)) return "❌ Keyword required";

    $files = array_diff(scandir($path), array('.', '..'));
    $results = array_filter($files, fn($f) => stripos($f, $keyword) !== false);

    return empty($results) ? "No matches" : implode("\n", $results);
}
function fileInfo($path, $filename) {
    $file = sanitizePath($path, $filename);
    if (!$file || !file_exists($file)) return "❌ File not found";

    $size = filesize($file);
    $created = date("Y-m-d H:i:s", filectime($file));
    $modified = date("Y-m-d H:i:s", filemtime($file));

    return "📄 File: $filename\n📦 Size: $size bytes\n🕒 Created: $created\n✏️ Modified: $modified";
}


