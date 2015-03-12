<?php

namespace Coreproc\Gcm\Classes;

class Response
{

    /**
     * @var string
     */
    protected $multicastId;

    /**
     * @var int
     */
    protected $success;

    /**
     * @var int
     */
    protected $failure;

    /**
     * @var int
     */
    protected $canonicalIds;

    /**
     * @var array
     */
    protected $results;

    public function __construct($response)
    {
        if (isset($response['multicast_id'])) $this->multicastId = $response['multicast_id'];
        if (isset($response['success'])) $this->success = $response['success'];
        if (isset($response['failure'])) $this->failure = $response['failure'];
        if (isset($response['canonical_ids'])) $this->canonicalIds = $response['canonical_ids'];
        if (isset($response['results'])) $this->results = $response['results'];
    }

    /**
     * @return string
     */
    public function getMulticastId()
    {
        return $this->multicastId;
    }

    /**
     * @return int
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @return int
     */
    public function getFailure()
    {
        return $this->failure;
    }

    /**
     * @return int
     */
    public function getCanonicalIds()
    {
        return $this->canonicalIds;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

}