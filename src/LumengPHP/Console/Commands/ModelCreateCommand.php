<?php

namespace LumengPHP\Console\Commands;

use LumengPHP\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Model创建命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ModelCreateCommand extends Command {

    protected function configure() {
        $this->setName('create:model');
        $this->setDescription('Create a new model class.');
        $this->addArgument(
                'name', InputArgument::REQUIRED, 'The model name you want to create.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello ' . $name;
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
    }

}
