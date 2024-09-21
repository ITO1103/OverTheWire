# Natas11
```
Username: natas11
Password: UJdqkK1pTu6VLt9UHWAgRZz6sVUZ3lEk
URL:      http://natas11.natas.labs.overthewire.org
```

色を指定すると背景色が変更できる楽しいサイトである。  

ご丁寧に
`Cookies are protected with XOR encryption`と書いてあるが、とりあえず`View sourcecode`。  
```
<?

$defaultdata = array( "showpassword"=>"no", "bgcolor"=>"#ffffff");

function xor_encrypt($in) {
    $key = '<censored>';
    $text = $in;
    $outText = '';

    // Iterate through each character
    for($i=0;$i<strlen($text);$i++) {
    $outText .= $text[$i] ^ $key[$i % strlen($key)];
    }

    return $outText;
}

function loadData($def) {
    global $_COOKIE;
    $mydata = $def;
    if(array_key_exists("data", $_COOKIE)) {
    $tempdata = json_decode(xor_encrypt(base64_decode($_COOKIE["data"])), true);
    if(is_array($tempdata) && array_key_exists("showpassword", $tempdata) && array_key_exists("bgcolor", $tempdata)) {
        if (preg_match('/^#(?:[a-f\d]{6})$/i', $tempdata['bgcolor'])) {
        $mydata['showpassword'] = $tempdata['showpassword'];
        $mydata['bgcolor'] = $tempdata['bgcolor'];
        }
    }
    }
    return $mydata;
}

function saveData($d) {
    setcookie("data", base64_encode(xor_encrypt(json_encode($d))));
}

$data = loadData($defaultdata);

if(array_key_exists("bgcolor",$_REQUEST)) {
    if (preg_match('/^#(?:[a-f\d]{6})$/i', $_REQUEST['bgcolor'])) {
        $data['bgcolor'] = $_REQUEST['bgcolor'];
    }
}

saveData($data);



?>
```
```
<?
if($data["showpassword"] == "yes") {
    print "The password for natas12 is <censored><br>";
}

?>
```
下の部分から、`["showpassword"] == "yes"`となれば良いことがわかる。


この問題はXOR暗号化として
```
  cookie
　  key
↓-XOR暗号化-↓
  outText
```
が行われている。


今回のcookieは、Devtoolから`HmYkBwozJw4WNyAAFyB1VUcqOE1JZjUIBis7ABdmbU1GdGdfVXRnTRg%3D`だとわかる。

現在わかっているのは、`cookie`とXOR暗号化のプログラムだけだが、自分で実行することで、`outText`が分かる。  

php実行できるサイト  
https://onlinephp.io/  

```
<?
$defaultdata = array( "showpassword"=>"yes", "bgcolor"=>"#ffffff");

function xor_encrypt($in) {
    $key = base64_decode('HmYkBwozJw4WNyAAFyB1VUcqOE1JZjUIBis7ABdmbU1GdGdfVXRnTRg%3D');
    $text = $in;
    $outText = '';

    // Iterate through each character
    for($i=0;$i<strlen($text);$i++) {
    $outText .= $text[$i] ^ $key[$i % strlen($key)];
    }

    return $outText;
}
print xor_encrypt(json_encode($defaultdata));
print "\n";
?>
```
を実行する。

すると、`eDWoeDWoeDWoeDWoeS]>kJjaHTlxOwdW93+:J`という出力結果が得られる。  
<!-- ここにeDWoとわかる理由を入れる -->
`eDWo`が出力結果だとわかるので。

key`eDWo`を使ってcookieをデコードする。
```
<?php
$cookie=base64_decode('HmYkBwozJw4WNyAAFyB1VUcqOE1JZjUIBis7ABdmbU1GIjEJAyIxTRg%3D');

$defaultdata = array( "showpassword"=>"yes", "bgcolor"=>"#ffffff");

function xor_encrypt($in) {
    $key = 'eDWo';
    $text = $in;
    $outText = '';

    // Iterate through each character
    for($i=0;$i<strlen($text);$i++) {
    $outText .= $text[$i] ^ $key[$i % strlen($key)];
    }

    return $outText;
}

//print xor_encrypt($cookie);
print(base64_encode(xor_encrypt(json_encode($defaultdata))));
print "\n";
?>
```

cookieを`HmYkBwozJw4WNyAAFyB1VUc9MhxHaHUNAic4Awo2dVVHZzEJAyIxCUc5`と書き換え、setcolorボタンを押す。  

すると、答えを得られる。

```
yZdkjAYZRd3R7tq7T5kXMjMJlOIkzDeB
```