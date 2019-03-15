# ab test

## ENV

machine:

```text
CPU: 2.7 GHz Intel Core i7
MEM: 16 GB 1600 MHz DDR3
```

php & swoole:

```text
php version: 7.3.2
swoole version: 4.3.1
worker_num: 1
```

ab: `ab -n 20000 -c 500 -k http://127.0.0.1:88/`

## Results

Position | QPS | Description
-----------|-----------------|-----------
raw demo server | `45000+` | run demo swoole http server
RequestListener.OnRequest | `45000` | entry onRequest method
Message\Request.new | `42000` | create request by `new Request()`
Message\Request.new | `40000` | create request by `bean(request)`
Message\Request.new | `35000` | after merge SERVER data(`array_merge`), will change key to upper
Message\Request.new | `37000` | after merge SERVER data(`array_merge`), not change key to upper
Message\Request.new | `38000` | after merge SERVER data(`array_merge`), not change key to upper, not check empty
Message\Request.new | `34000` | after init headers, use `setHeadersFromSwoole`
Message\Request.new | `28000` | after init headers, use `setHeaders`
Message\Request.new | `24500` | after create uri, use `newUriByCoRequest`


