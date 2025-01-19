<?php

namespace App\Command;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(
    name: 'app:load-books',
    description: 'Add a short description for your command',
)]
class LoadBooksCommand extends Command
{
    public function __construct(
        private KernelInterface $kernel,
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $projectDir = $this->kernel->getProjectDir();

        $handler = fopen($projectDir . '/var/BooksDataset.csv', 'r');
        $header = fgetcsv($handler);
        $stopwatch = new Stopwatch();

        $stopwatch->start('load-books');
        $i = 1; $temp = [];
        while ($row = fgetcsv($handler)) {
            $book = $this->em->getRepository(Book::class)->find($i);
            if ($book === null) {
                $book = new Book();
                $book->setId($i);
            }
            $price = floatval(preg_replace('/[^\d.]+/', '', $row[5]));
            $date = new \DateTimeImmutable(sprintf('%s %s', $row[7], $row[6]));

            $book->setTitle(substr($row[0], 0, 255))
                ->setAuthors(explode(',', $row[1]))
                ->setDescription($row[2])
                ->setCategory($row[3])
                ->setPublisher($row[4])
                ->setPublishDate($date)
                ->setPrice(intval($price * 100));

            $this->em->persist($book);
            $temp[] = $book;
            if ($i % 100 === 0) {
                $this->em->flush();

                foreach($temp as $obj) {
                  $this->em->detach($obj);
                }
                $temp = [];
                gc_enable();
                gc_collect_cycles();

                $event = $stopwatch->lap('load-books');
                $lap = count($event->getPeriods());
                $io->writeln(sprintf('Lap %d: %d ms %d books', $lap, $event->getPeriods()[$lap - 1]->getDuration(), $i));
            }
            $i++;
        }
        $this->em->flush();
        $lap = count($event->getPeriods());
        $io->writeln(sprintf('Lap %d: %d ms %d books', $lap, $event->getPeriods()[$lap - 1]->getDuration(), $i));
        $event = $stopwatch->stop('load-books');
        $io->writeln(sprintf('Total duration: %d ms', $event->getDuration()));

        $io->success('All books imported!');

        return Command::SUCCESS;
    }
}
