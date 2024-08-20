# Natas8
```
Username: natas8
Password: xcoXLmzMkoIP9D7hlgPlh9XD7OgLAe5Q
URL:      http://natas8.natas.labs.overthewire.org
```

とりあえず`View sourcecode`。
```
function encodeSecret($secret) {
    return bin2hex(strrev(base64_encode($secret)));
}
```
この部分がエンコード処理の部分である。

これの逆(デコード)をして答えを探る。  
https://onlinephp.io/  
↑PHPを実行できるサイト。  

```
<?php
function decodeSecret($secret){
  return base64_decode(strrev(hex2bin($secret)));
  }
print decodeSecret("3d3d516343746d4d6d6c315669563362");
print "\n";
?>
```
↑デコードするプログラム  
実行結果は`oubWYf2kBq`なので、これを入力して送信すると答えが得られる  

```
ZE1ck82lmdGIoErlhQgWND6j2Wzz6b6t
```