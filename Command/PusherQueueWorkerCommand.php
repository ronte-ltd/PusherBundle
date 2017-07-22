<?php

namespace RonteLtd\PusherBundle\Command;

use RonteLtd\PusherBundle\Pusher\Pusher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PushQueueWorkerCommand
 * @package RonteLtd\PusherBundle\Command
 */
class PusherQueueWorkerCommand extends Command
{
    /**
     * @var Pusher
     */
    private $pusher;

    /**
     * @var string
     */
    private $bgWorkerId;

    /**
     * PusherQueueWorkerCommand constructor.
     * @param Pusher $pusher
     * @param string $bgWorkerId
     */
    public function __construct(Pusher $pusher, string $bgWorkerId)
    {
        $this->pusher = $pusher;
        $this->bgWorkerId = $bgWorkerId;

        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $prefix = $this->bgWorkerId;
        $worker = new \GearmanWorker();
        $worker->addServer(
            $this->pusher->getGearmanServer(),
            $this->pusher->getGearmanPort()
        );
        $worker->addFunction($prefix . 'SendPush', [$this, 'sendPush']);

        while (1) {
            $worker->work();
            if ($worker->returnCode() != GEARMAN_SUCCESS) {
                break;
            }
        }
        return true;
    }

    /**
     * @param $job
     */
    public function sendPush($job)
    {
        $workload = $job->workload();
        $data = json_decode($workload, true);
        $this->pusher->trigger($data['pusherChannels'], $data['pusherEvent'], $data['data']);
    }

    /**
     * Command name and description
     */
    protected function configure()
    {
        $this
            ->setName('pusher:worker:run')
            ->setDescription('Run pusher(web sockets) queue worker')
        ;
    }
}
