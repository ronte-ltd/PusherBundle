<?php

namespace RonteLtd\PusherBundle\Pusher;

use Symfony\Component\Debug\Exception\ContextErrorException;

class Pusher
{
    /**
     * @var \Pusher
     */
    protected $pusher;

    /**
     * @var string
     */
    protected $gearmanServer;

    /**
     * @var string
     */
    protected $gearmanPort;

    /**
     * PusherWrapper constructor.
     * @param \Pusher $pusher
     */
    public function __construct(\Pusher $pusher, string $gearmanServer, string $gearmanPort)
    {
        $this->pusher = $pusher;
        $this->gearmanServer = $gearmanServer;
        $this->gearmanPort = $gearmanPort;
    }

    /**
     * @return string
     */
    public function getGearmanServer()
    {
        return $this->gearmanServer;
    }

    /**
     * @return string
     */
    public function getGearmanPort()
    {
        return $this->gearmanPort;
    }

    /**
     * @param mixed $pusherChannels
     * @param string $pusherEvent
     * @param array $data
     */
    public function trigger($pusherChannels, string $pusherEvent, array $data)
    {
        if (is_string($pusherChannels) === true) {
            $pusherChannels = array($pusherChannels);
        }

        $this->pusher->trigger($pusherChannels, $pusherEvent, $data);
    }

    /**
     * @param mixed $pusherChannels
     * @param string $pusherEvent
     * @param array $data
     * @return bool
     */
    public function addPush($pusherChannels, string $pusherEvent, array $data): bool
    {
        $client = $this->createClient();

        try {
            $client->doBackground('sendPush', json_encode([
                'pusherChannels' => $pusherChannels,
                'pusherEvent' => $pusherEvent,
                'data' => $data,
            ]));
        } catch (ContextErrorException $e) {
            return false;
        }

        return true;
    }

    /**
     * @return \GearmanClient
     */
    private function createClient(): \GearmanClient
    {
        $client = new \GearmanClient();
        $client->addServer(
            $this->gearmanServer,
            $this->gearmanPort
        );

        return $client;
    }
}