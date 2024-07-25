# Natas2
```
Username: natas2
Password: TguMNxKo1DSa1tujBLuZJnDUlCcUAPlI
URL:      http://natas2.natas.labs.overthewire.org
```

F12でDevtoolを開くと`<img src="files/pixel.png">`という怪しいpngを見つけられる。  
http://natas2.natas.labs.overthewire.org/files/pixel.png  
アクセスしてみると1x1のpng画像が表示されるだけだが、`files`というディレクトリがあることが分かる。

なので、http://natas2.natas.labs.overthewire.org/files/ へアクセスする。  
すると、/filesの中のファイルが一覧で確認でき、`users.txt`という怪しいファイルを見つけられる。  
中身を確認すると
```
username:password
alice:BYNdCesZqW
bob:jw2ueICLvT
charlie:G5vCxkVV3m
natas3:3gqisGdR0pjm6tpkDKdIWO2hSvchLeYH
eve:zo4mJWyNj2
mallory:9urtcpzBmH
```
と書いてあり、Natas3のパスワードが分かる。  
```
3gqisGdR0pjm6tpkDKdIWO2hSvchLeYH
```