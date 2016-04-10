<?php

namespace LumengPHP\Console\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 命令创建的命令，用于创建一个"LumengPHP\Kernel\Command\CommandInterface"的子类
 *
 * @author Lumeng <zhengb302@163.com>
 */
class CommandCreateCommand extends ClassCreateCommand {

    protected function configure() {
        $this->setName('create:command');
        $this->setDescription('Create a new command');
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
        $namespace = $nsRoot . '\\Commands';
        if (strpos($name, '/')) {
            $namespace = $namespace . '\\' . str_replace('/', '\\', dirname($name));
        }

        $nsRootDir = trim($this->getNamespaceRootDir(), '/');
        if ($nsRootDir) {
            $filePath = $this->getAppRootDir() . '/' . $nsRootDir . "/Commands/{$name}.php";
        } else {
            $filePath = $this->getAppRootDir() . "/Commands/{$name}.php";
        }

        $stub = file_get_contents($this->getStubDir() . '/command.stub');

        $content = str_replace(
                array('{{namespace}}', '{{CommandName}}'), array($namespace, $commandName), $stub
        );

        $commandDir = dirname($filePath);
        if (!is_dir($commandDir)) {
            mkdir($commandDir, 0755, true);
        }

        file_put_contents($filePath, $content);

        $output->writeln('创建成功！');
    }

}
