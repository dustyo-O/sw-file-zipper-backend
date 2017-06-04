<?php
require_once "init.php";

function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function makeHash() {
    do {
      $hash = generateRandomString();
    } while(is_dir($hash));

    mkdir($hash);
    return $hash;
}

$directory_hash = isset($_POST['hash']) ? $_POST['hash'] : makeHash();

move_uploaded_file($_FILES['file']['tmp_name'], $directory_hash . '/' .$_FILES['file']['name']);
?>{
  "status": "ok",
  "hash": "<?= $directory_hash ?>"
}
