<?php

namespace PHPhinderBundle\Command;

use App\Entity\Book;
use PHPhinderBundle\Schema\SchemaGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'phphinder:schema:generate',
    description: 'Add a short description for your command',
)]
class SchemaGenerateCommand extends Command
{
    public function __construct(private SchemaGenerator $schemaGenerator)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $schema = $this->schemaGenerator->generate(Book::class);

        $output->writeln('Schema Generated:');
        $output->writeln(print_r($schema, true));


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
