<?php declare(strict_types=1);

namespace Ihor\Frame2\Command;

use Ihor\Frame2\Service\ExampleFilesystemService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'ihor:test', description: 'Hello PhpStorm')]
class TestCommand extends Command
{
    public function __construct(
        private readonly ExampleFilesystemService $fileService,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $a = $this->fileService->listPrivateFiles();
        $c = $this->fileService->listPublicFiles();

        $b=1;
        return Command::SUCCESS;
    }
}
