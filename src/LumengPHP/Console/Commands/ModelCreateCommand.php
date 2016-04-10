<?php

namespace LumengPHP\Console\Commands;

use LumengPHP\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
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
        // eg. GoodsModel、UserModel
        $name = $input->getArgument('name');

        $nsRoot = trim($this->getNamespaceRoot(), '\\');
        $namespace = $nsRoot . '\\Models';

        $nsRootDir = trim($this->getNamespaceRootDir(), '/');
        if ($nsRootDir) {
            $filePath = $this->getAppRootDir() . '/' . $nsRootDir . "/Models/{$name}.php";
        } else {
            $filePath = $this->getAppRootDir() . "/Models/{$name}.php";
        }

        $stub = file_get_contents($this->getStubDir() . '/model.stub');

        $content = str_replace(
                array('{{namespace}}', '{{ModelName}}'), array($namespace, $name), $stub
        );

        file_put_contents($filePath, $content);

        $output->writeln('创建成功！');
    }

}
