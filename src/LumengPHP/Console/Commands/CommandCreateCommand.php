<?php

namespace LumengPHP\Console\Commands;

use LumengPHP\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 命令创建的命令，用于创建一个"LumengPHP\Kernel\Command\CommandInterface"的子类
 *
 * @author Lumeng <zhengb302@163.com>
 */
class CommandCreateCommand extends Command {

    protected function configure() {
        $this->setName('create:command');
        $this->setDescription('Create a new command class.');
        $this->addArgument(
                'name', InputArgument::REQUIRED, 'The command name you want to create.'
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
