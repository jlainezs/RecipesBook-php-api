<?php
namespace Tools\Console;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

#[AsCommand(
    name: 'rb:create-bc',
    description: 'Create a bounded context',
    help: 'This command creates a new bounded context and creates the related directory structure.',
    usages: ['MyNewShinyBoundedContext']
)]
class RBCreateBCCommand
{
    private array $dirs = [
        'Application',
        'Application/Command',
        'Application/Command/%bcname%',
        'Application/Query',
        'Application/Query/%bcname%',
        'Application/Service',
        'Domain',
        'Domain/Exceptions',
        'Domain/Model',
        'Domain/Repository',
        'Infrastructure',
        'Infrastructure/DoctrineMapping',
        'Infrastructure/Repository',
        'Presentation',
        'Presentation/Http',
        'Presentation/Http/Controller',
        'Presentation/Http/Response',
    ];

    public function __invoke(
        #[Argument('The name of the bounded context')] string $bcName,
        SymfonyStyle $io,
        OutputInterface $output): int
    {
        $filesystem = new Filesystem();
        $bcDir = $this->initialDir($bcName);

        if ($filesystem->exists($bcDir)) {
            $io->error("Bounded context $bcName already exists");
            return Command::FAILURE;
        }

        $this->makeDirectories($bcDir, $bcName, $filesystem);

        return Command::SUCCESS;
    }

    private function initialDir(string $bcName): string {
        $d =  dirname(__DIR__);
        return Path::join($d, $bcName);
    }

    /**
     * @param string $bcDir
     * @param string $bcName
     * @param Filesystem $filesystem
     * @return void
     */
    public function makeDirectories(string $bcDir, string $bcName, Filesystem $filesystem): void
    {
        $filesystem->mkdir($bcDir);

        foreach ($this->dirs as $piece)
        {
            $dir = Path::join($bcDir, $piece);
            $dir = str_replace('%bcname%', $bcName, $dir);
            $filesystem->mkdir($dir);
        }
    }
}
