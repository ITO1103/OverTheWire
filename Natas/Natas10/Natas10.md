# Natas10
```
Username: natas10
Password: t7I5VHvpa14sJTUGV0cbEsbYfFP2dmOu
URL:      http://natas10.natas.labs.overthewire.org
```

Natas9と同じような内容だが`For security reasons, we now filter on certain characters`「セキュリティ上の理由から、ある記号をフィルターしました」と書かれている  

とりあえず`View sourcecode`。  
```
<?
$key = "";

if(array_key_exists("needle", $_REQUEST)) {
    $key = $_REQUEST["needle"];
}

if($key != "") {
    if(preg_match('/[;|&]/',$key)) {
        print "Input contains an illegal character!";
    } else {
        passthru("grep -i $key dictionary.txt");
    }
}
?>
```

```
if(preg_match('/[;|&]/',$key))
```
で `;` `|` `&` が文字列に含まれているか判定している。  

文字列に上記3つの記号が使用されていた場合、`Input contains an illegal character!`と怒られるのでこれを回避する。  

`grep`コマンドは、一度に複数のファイルを検索できる。  
それを利用してdictionary.txtだけでなく、$keyに調べて欲しいファイルを追加する。  
しかし、
```
/etc/natas_webpass/natas11
```
だけでは、調べたい文字列が指定されていないので注意。  
調べたい文字は、`-i`オプションで大文字小文字関係なくなっており、パスワードに含まれていれば良いので、適当に試す

```
a /etc/natas_webpass/natas11
```
実行すると答えが得られる。
```
UJdqkK1pTu6VLt9UHWAgRZz6sVUZ3lEk
```