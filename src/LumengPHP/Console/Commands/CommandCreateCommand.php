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
        // eg. HomeCommand、User/UserRegisterCommand
        $name = $input->getArgument('name');

        // eg. HomeCommand、UserRegisterCommand
        $commandName = $name;
        if (strpos($name, '/')) {
            $commandName = basename($name);
        }

        $nsRoot = trim($this->getNamespaceRoot(), '\\');

        $nsRootDir = trim($this->getNamespaceRootDir(), '/');
        if ($nsRootDir) {
            $filePath = $this->getAppRootDir() . '/' . $nsRootDir . "/Commands/{$name}.php";
        } else {
            $filePath = $this->getAppRootDir() . "/Commands/{$name}.php";
        }


        $stub = file_get_contents($this->getStubDir() . '/command.stub');

        $content = str_replace(
                array('{NamespaceRoot}', '{CommandName}'), array($nsRoot, $commandName), $stub
        );

        file_put_contents($filePath, $content);

        $output->writeln('创建成功！');
    }

}
