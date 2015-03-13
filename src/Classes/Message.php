<?php

namespace Coreproc\Gcm\Classes;

use Coreproc\Gcm\GcmClient;
use Exception;

class Message
{

    /**
     * @var GcmClient
     */
    private $gcmClient;

    /**
     * @var array
     */
    private $registrationIds = [];

    /**
     * @var string
     */
    private $notificationKey;

    /**
     * @var string
     */
    private $collapseKey;

    /**
     * @var boolean
     */
    private $delayWhileIdle;

    /**
     * @var int
     */
    private $timeToLive;

    /**
     * @var string
     */
    private $restrictedPackageName;

    /**
     * @var boolean
     */
    private $dryRun;

    /**
     * @var array
     */
    private $data;

    public function __construct(GcmClient $gcmClient)
    {
        $this->gcmClient = $gcmClient;
    }

    /**
     * This parameter specifies the list of devices (registration IDs) receiving the
     * message. It must contain at least 1 and at most 1000 registration IDs.
     *
     * Required if notificationKey is not present
     *
     * @param string|array $registrationId
     */
    public function addRegistrationId($registrationId)
    {
        if (is_string($registrationId)) {
            $this->registrationIds[] = $registrationId;
        }

        if (is_array($registrationId)) {
            $this->registrationIds = array_merge($this->registrationIds, $registrationId);
        }

        // remove duplicate values in array
        $this->registrationIds = array_unique($this->registrationIds);

        // rebase array
        $this->registrationIds = array_values($this->registrationIds);

        if ( ! empty($this->notificationKey)) {
            // remove the notification key since only one of them will be accepted
            $this->notificationKey = null;
        }
    }

    /**
     * This parameter specifies the mapping of a single user to multiple registration IDs
     * associated with that user.
     *
     * This allows us to send a single message to multiple app instances (typically on
     * multiple devices) owned by a single user.
     *
     * We can use notification_key as the target for a message instead of an individual
     * registration ID (or array of registration IDs). The maximum number of members
     * allowed for a notification_key is 20.
     *
     * Required if registrationIds not present
     *
     * @param $notificationKey
     */
    public function setNotificationKey($notificationKey)
    {
        if ( ! empty($this->registrationIds)) {
            // remove the registration IDs since only one of them will be accepted
            $this->registrationIds = null;
        }

        $this->notificationKey = $notificationKey;
    }

    /**
     * This parameter identifies a group of messages (e.g., with collapse_key: "Updates
     * Available") that can be collapsed, so that only the last message gets sent when
     * delivery can be resumed. This is intended to avoid sending too many of the same
     * messages when the device comes back online or becomes active (see
     * delay_while_idle)
     *
     * Note that there is no guarantee of the order in which messages get sent.
     *
     * Messages with collapse key are also called send-to-sync messages messages.
     *
     * Note: A maximum of 4 different collapse keys is allowed at any given time. This
     * means a GCM connection server can simultaneously store 4 different send-to-sync
     * messages per client app. If you exceed this number, there is no guarantee which 4
     * collapse keys the GCM connection server will keep.
     *
     * Optional.
     *
     * @param string $collapseKey
     */
    public function setCollapseKey($collapseKey)
    {
        $this->collapseKey = $collapseKey;
    }

    /**
     * When this parameter is set to true, it indicates that the message should not be
     * sent until the device becomes active.
     *
     * The default value is false.
     *
     * Optional.
     *
     * @param boolean $delayWhileIdle
     */
    public function setDelayWhileIdle($delayWhileIdle)
    {
        $this->delayWhileIdle = $delayWhileIdle;
    }

    /**
     * This parameter specifies how long (in seconds) the message should be kept in GCM
     * storage if the device is offline. The maximum time to live supported is 4 weeks.
     *
     * The default value is 4 weeks.
     *
     * Optional.
     *
     * @param int $timeToLive
     */
    public function setTimeToLive($timeToLive)
    {
        $this->timeToLive = $timeToLive;
    }

    /**
     * This parameter specifies the package name of the application where the
     * registration IDs must match in order to receive the message.
     *
     * Optional.
     *
     * @param string $restrictedPackageName
     */
    public function setRestrictedPackageName($restrictedPackageName)
    {
        $this->restrictedPackageName = $restrictedPackageName;
    }

    /**
     * This parameter, when set to true, allows us to test a request without
     * actually sending a message.
     *
     * The default value is false.
     *
     * Optional.
     *
     * @param boolean $dryRun
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = $dryRun;
    }

    /**
     * This parameter specifies the key-value pairs of the message's payload.
     * There is no limit on the number of key-value pairs, but there is a total
     * message size limit of 4kb.
     *
     * For instance, in Android, data:{"score":"3x1"} would result in an intent
     * extra named score with the string value 3x1.
     *
     * The key should not be a reserved word (from or any word starting with google).
     * It is also not recommended to use words defined in this table (such as
     * collapse_key) because that could yield unpredictable outcomes.
     *
     * Values in string types are recommended. You have to convert values in objects
     * or other non-string data types (e.g., integers or booleans) to string.
     *
     * Optional.
     *
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Sends the message to GCM using the GCM client.
     *
     * @return Response
     * @throws Exception
     */
    public function send()
    {
        try {
            return $this->gcmClient->send($this->build());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Converts the contents of this object to an array expected by the GCM API.
     *
     * @return array
     * @throws Exception
     */
    private function build()
    {
        $data = [];

        if (empty($this->registrationIds) && empty($this->notificationKey)) {
            // error
            $exception = new Exception('A registration ID or a notification key is required to send a GCM notification.', 400);
            throw $exception;
        }

        // Set targets
        if ( ! empty($this->registrationIds)) $data['registration_ids'] = $this->registrationIds;
        if ( ! empty($this->notificationKey)) $data['notification_key'] = $this->notificationKey;

        // Set options
        if ( ! empty($this->collapseKey)) $data['collapse_key'] = $this->collapseKey;
        if ( ! empty($this->delayWhileIdle)) $data['delay_while_idle'] = $this->delayWhileIdle;
        if ( ! empty($this->timeToLive)) $data['time_to_live'] = $this->timeToLive;
        if ( ! empty($this->restrictedPackageName)) $data['restricted_package_name'] = $this->restrictedPackageName;
        if ( ! empty($this->dryRun)) $data['dry_run'] = $this->dryRun;

        // Set payload
        if ( ! empty($this->data)) $data['data'] = $this->data;

        return $data;
    }

}