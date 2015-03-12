<?php

namespace Coreproc\Gcm;

use Coreproc\Gcm\Classes\Response;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GcmClient
{

    /**
     * @var string
     */
    private $apiKey;

    protected $gcmUrl = 'https://android.googleapis.com/gcm/send';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param array $data
     * @return \Coreproc\Gcm\Classes\Response
     * @throws Exception
     */
    public function send(array $data)
    {
        if (empty($data['registration_ids']) && empty($data['notification_key'])) {
            $exception = new Exception('A registration ID or a notification key is required to send a GCM notification.', 400);
            throw $exception;
        }

        if ( ! empty($data['registration_ids']) && count($data['registration_ids']) > 1000) {
            $exception = new Exception('Your registration IDs can not exceed over 1000.', 400);
            throw $exception;
        }

        // we send the data
        $client = new Client();

        try {
            $response = $client->post($this->gcmUrl, [
                'headers' => [
                    'Authorization' => 'key=' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'body'    => json_encode($data)
            ]);
        } catch (RequestException $e) {
            throw $e;
        }

        return new Response($response->json());
    }

}