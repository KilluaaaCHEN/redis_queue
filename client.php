<?php
/**
 * Created by PhpStorm.
 * User: Killua chen
 * Date: 16/11/18
 * Time: 10:47
 */

//连接本地的 Redis 服务
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

for($i=0;$i<100000;$i++){
    $id = uniqid();
    $action = ['cls' => 'TestJob', 'action' => 'handle', 'arg' => ['id' => $id]];
    $redis->lPush('queue', serialize($action));
}

