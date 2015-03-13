<?php

require '../vendor/autoload.php';

$gcmClient = new Coreproc\Gcm\GcmClient('AIzaSyCbyvM8Hixg9vU5fI-N3_UmQWymQ3hi-TU');

$message = new \Coreproc\Gcm\Classes\Message($gcmClient);

$message->addRegistrationId('APA91bHu3OobTxje1AGgilL-VFV2YVY7Xjt9drLKfi-HXfj-MXZ02mNKToVtEm2Kflxsfbfk35V4DVtZTSe0634CSW-w8SmsyK00phwPPVJg9xYs00ZHq-dh95d7ckPHyoJ7DKRClrmTeY7bK7fYa0pRrlyXvkFNXGNVX046ixSP6gRSxx7Caq0');
$message->setData(['title' => 'Your idol has a new recording', 'message' => 'Tonette just released a new recording!']);

try {
    $response = $message->send();
    print_r($response);
} catch (Exception $exception) {
    echo 'uh-oh: ' . $exception->getMessage();
    return;
}



