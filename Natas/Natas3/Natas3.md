# Natas3
```
Username: natas3
Password: 3gqisGdR0pjm6tpkDKdIWO2hSvchLeYH
URL:      http://natas3.natas.labs.overthewire.org
```

F12でDevtoolを開いても何もないことが分かる。  
`<!-- No more information leaks!! Not even Google will find it this time... -->`  
と、「Googleも見つけられないよ！」という感じでコメントが書かれている。  

Googleも見つけられないというのは、「検索エンジンにクロールされない」ということであり、`robots.txt`というファイルが鍵になる。
https://developers.google.com/search/docs/crawling-indexing/robots/intro  

ということで`robots.txt`を見てみる。  
http://natas3.natas.labs.overthewire.org/robots.txt  
すると
```
User-agent: *
Disallow: /s3cr3t/
```
と、`/s3cr3t/`というディレクトリがクロールされないように隠されていることが分かる。  

http://natas3.natas.labs.overthewire.org/s3cr3t/　へアクセスしてみる。  
するとまたもや`users.txt`という怪しいファイルを見つけることができる。  
中身を確認すると
```
natas4:QryZXc2e0zahULdHrtHxzyYkj59kUxLQ
```
と書かれており、Natas4のパスワードが分かる。
```
QryZXc2e0zahULdHrtHxzyYkj59kUxLQ
```