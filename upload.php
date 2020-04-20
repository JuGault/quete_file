<?php

$uploadDir = 'uploads/';
$uploaded = array();
$failed = array();

if (!empty($_FILES['avatar']['name'])) {
    $files = $_FILES['avatar'];
    $allowed = array('png','gif','jpg');

    foreach ($files['name'] as $position => $file_name) {
        $file_tmp = $files['tmp_name'][$position];
        $file_size = filesize($file_tmp);
        $file_error = $files['error'][$position];
        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));


        if (in_array($file_ext, $allowed, true)) {
            if($file_error === 0) {
               if($file_size <= 1000000 ) {
                   $file_name_new = uniqid('', true) . '.' . $file_ext;
                   $file_destination = $uploadDir . $file_name_new ;
                   move_uploaded_file($file_name_new, $file_destination);
                   if(move_uploaded_file($file_tmp, $file_destination)){
                       $uploaded[$position] = $file_destination ;
                   } else {
                       $failed[$position] = "[{$file_name}] failed to upload.";
                   }
               } else {
                   $failed[$position] = "[{$file_name}] is too large.";
               }
            } else {
                $failed[$position] = "[{$file_name}] errored with code {$file_error}";
            }
        } else {
            $failed[$position] = "[{$file_name}] file extension '{$file_ext}' is not allowed.";
        }
    }

}



?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Balance ton file</title>
</head>
<body>
<div>
    <form enctype="multipart/form-data" method="post" action="upload.php">

            <div><label for="image_file">Please select image file</label></div>
            <div><input type="file" name="avatar[]" id="image_file" multiple="multiple" /></div>

            <button>Upload</button>
        <?php if (!empty($failed)) { foreach ($failed as $errors => $error) : ?>
        <div  style="color: red"><?= $error ?></div>
        <?php endforeach; } ?>

    </form>
        <div id="fileinfo">
            <?php $it = new FilesystemIterator($uploadDir);
            foreach ($it as $files => $file) :?>
                <form action="upload.php">
                    <figure>
                        <img src="<?= $files ?>" alt="">
                        <figcaption><?= $files ?></figcaption>
                    </figure>
                    <button>Delete</button>
                </form>

            <?php endforeach; ?>

</body>
</html>
