<?php
/**
 * Created by PhpStorm.
 * User: killua
 * Date: 16/11/18
 * Time: 10:48
 */
require_once 'TestJob.php';

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->set('i', 0);

$is_start = false;
$start_time = time();//记录开始时间

//直接调用
//while (true) {
//    $item = $redis->lPop('queue');
//    if (!$item) {
//        //没有数据时,如果已开始停止脚本,打印结果
//        if ($is_start) {
//            $end_time = time();
//            var_dump($end_time - $start_time);
//            var_dump($redis->get('i'));
//            break;
//        }
//        continue;
//    }
//    $item = unserialize($item);
//    $service = new $item['cls'];
//    $service->$item['action']($item['arg']);
//    $is_start = true;
//}

//Gearman
$gmClient = new \GearmanClient();
$gmClient->addServers();
while (true) {
    $item = $redis->lPop('queue');
    if (!$item) {
        //没有数据时,如果已开始停止脚本,打印结果
        if ($is_start) {
            $end_time = time();
            var_dump($end_time - $start_time);
            var_dump($redis->get('i'));
            break;
        }
        continue;
    }
    $item = unserialize($item);
    $service = new $item['cls'];
    $gmClient->doBackground('handle', serialize($item));//原来直接处理的,现在交给gearman worker
    $is_start = true;
}

