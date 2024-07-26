# Natas6
```
Username: natas6
Password: 0RoJwHdSKWFTYR5WuiAewauSuNaBXned
URL:      http://natas6.natas.labs.overthewire.org
```
`View sourcecode`と、ソースコードが見れるので見てみる。
```
<?

include "includes/secret.inc";

    if(array_key_exists("submit", $_POST)) {
        if($secret == $_POST['secret']) {
        print "Access granted. The password for natas7 is <censored>";
    } else {
        print "Wrong secret";
    }
    }
?>
```
入力された文字列が`include "includes/secret.inc";`にある`$secret`と同じかを判定している。  
なので`includes/secret.inc`へアクセスする。  
http://natas6.natas.labs.overthewire.org/includes/secret.inc  
すると、
```
<?
$secret = "FOEIUWGHFEEUHOFUOIU";
?>
```
とあり、secretが分かる。  
`FOEIUWGHFEEUHOFUOIU`を入力し、送信すると答えが得られる。

```
bmg8SvU1LizuWjx3y7xkNERkHxGre0GS
```