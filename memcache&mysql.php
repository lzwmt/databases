<?php
//实例化memcache类
$memcache = new Memcache();
$memcache->connect('localhost',11211);
$sql = 'select * from users';
//设置一个键名
$key = md5($sql);
if(!$memcache->get($key)){
    $flag = 'mysql';
    //如果$result不存在，说明memcache没有数据，需要连接数据库查询
    $dsn = "mysql:host=localhost;dbname=men";
    $username = 'root';
    $psswd = 'admin';
    $pdo = new PDO($dsn, $username, $psswd);
    //var_dump($pdo);
    $pdo->exec('set name utf8');
    $stmt = $pdo->prepare($sql);
    $stmt -> execute();
    $result  =  $stmt ->fetchALL(PDO::FETCH_ASSOC);
    //var_dump($result);
    //将查找的结果存入memcacha缓存
    $memcache->add($key,$result);
}else{
    //如果$result存在，数据从memcache中取出
    $flag = 'memcache';
    $result = $memcache->get($key);
}
echo $flag.'<br />';
echo $key.'<br />';
print_r($result);

