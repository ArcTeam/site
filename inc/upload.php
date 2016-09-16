<?php
$uploaddir = '../img/post/';
$file = $uploaddir . basename($_FILES['upload']['name']);
if (move_uploaded_file($_FILES["upload"]["tmp_name"], $file)) {chmod("$file",  0777);
    alert
} else {
    echo "error";
}
?>
