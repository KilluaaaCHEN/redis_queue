<?php
/**
 * Created by PhpStorm.
 * User: killua
 * Date: 16/11/24
 * Time: 10:59
 */
require_once 'TestJob.php';

$gmWorker = new GearmanWorker();
$gmWorker->addServers();
$gmWorker->addFunction('handle', function (\GearmanJob $job) {
    $job->handle();
    $item = $job->workload();
    if (!empty($item)) {
        $item = unserialize($item);
        if (is_array($item)) {
            (new $item['cls']())->handle($item['arg']);
        }
    }
    $job->sendComplete('complete');
});
while ($gmWorker->work()) {
    if ($gmWorker->returnCode() != GEARMAN_SUCCESS) {
        echo "return_code: " . $gmWorker->returnCode() . "\n";
    }
}
