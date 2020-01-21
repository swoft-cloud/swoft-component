
```php
// php run.php -c src/tcp-server/phpunit.xml
// SWOFT_TEST_TCP_SERVER=1
if (1 === (int)getenv('SWOFT_TEST_SERVER')) {
    // Output: "php is /usr/local/bin/php"
    [$ok, $ret,] = Sys::run('type php');
    if (0 !== $ok) {
        exit('php not found');
    }

    $type = 'ws';
    $php  = substr(trim($ret), 7);
    $proc = new Process(function (Process $proc) use ($php, $type) {
        // $proc->exec($php, [ $dir . '/test/bin/swoft', 'ws:start');
        $proc->exec($php, ['test/bin/swoft', $type . ':start']);
    });
    $pid  = $proc->start();
    echo "Swoft test server started, PID $pid\n";

    // wait server starting...
    sleep(2);
    echo file_get_contents('http://127.0.0.1:28308/hi');
}

// run tests ...

if (isset($pid) && $pid > 0) {
    echo "Stop server on tests end. PID $pid";
    $ok = Process::kill($pid, 15);
    echo $ok ? " OK\n" : " FAIL\n";
}
```
