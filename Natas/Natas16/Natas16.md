# Natas16
```
Username: natas16
Password: hPkjKYviLQctEW33QmuXL6eDVfMW4sGo
URL:      http://natas16.natas.labs.overthewire.org
```

`For security reasons, we now filter even more on certain characters`と、特定の文字を禁止されたらしい。

とりあえず`View sourcecode`
```
<?
$key = "";

if(array_key_exists("needle", $_REQUEST)) {
    $key = $_REQUEST["needle"];
}

if($key != "") {
    if(preg_match('/[;|&`\'"]/',$key)) {
        print "Input contains an illegal character!";
    } else {
        passthru("grep -i \"$key\" dictionary.txt");
    }
}
?>
```

