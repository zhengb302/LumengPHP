<?php

namespace LumengPHP\Console\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 命令测试(用例)创建命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class CommandTestCreateCommand extends ClassCreateCommand {

    protected function configure() {
        $this->setName('create:command-test');
        $this->setDescription('Create a new command test');
        $this->addArgument(
                'test-name', InputArgument::REQUIRED, 'The test name you want to create.'
        );
        $this->addArgument(
                'command-name', InputArgument::REQUIRED, 'The command you want to test.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // eg. GetUserInfoCommandTest、ShowOrderCommandTest
        $testName = $input->getArgument('test-name');

        $nsRoot = trim($this->getNamespaceRoot(), '\\');
        // eg. HomeCommand、User/UserRegisterCommand
        $commandName = $input->getArgument('command-name');
        // eg. Apache\Blog\Commands\User\UserRegisterCommand
        $command = $nsRoot . '\\Commands\\' . str_replace('/', '\\', $commandName);

        $filePath = $this->getAppRootDir() . "/tests/TestCases/{$testName}.php";

        $stub = file_get_contents($this->getStubDir() . '/test.stub');

        $content = str_replace(
                array('{{TestName}}', '{{command}}'), array($testName, $command), $stub
        );

        file_put_contents($filePath, $content);

        $output->writeln('创建成功！');
    }

}
