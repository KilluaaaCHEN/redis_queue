<?php

/**
 * Created by PhpStorm.
 * User: killua
 * Date: 16/11/18
 * Time: 10:51
 */
class TestJob
{
    public function handle($arg)
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->incr('i');
    }
}