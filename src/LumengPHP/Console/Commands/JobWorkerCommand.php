<?php

namespace LumengPHP\Console\Commands;

use LumengPHP\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LumengPHP\Job\JobWorker;

/**
 * JobWorker 命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class JobWorkerCommand extends Command {

    protected function configure() {
        $this->setName('job:work')
                ->setDescription('Execute jobs repeatedly.')
                ->addArgument(
                        'channel-name', InputArgument::REQUIRED, 'The channel name'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $channelName = $input->getArgument('channel-name');
        $appContext = $this->getAppContext();
        $channelConfig = $appContext->getConfig("job.channels.{$channelName}");

        $jobWorker = new JobWorker($channelName, $channelConfig, $appContext);
        $jobWorker->execute();
    }

}
