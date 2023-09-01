<?php
namespace Passionweb\SymfonyCommand\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Exception;

class CreateFileCommand extends Command
{
    protected function configure()
    {
        $this->setDescription('Creates a text file within the entered path');
        $this->addOption('path', '', InputOption::VALUE_OPTIONAL, 'Path to directory in which the text file should be created.');
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());
        try {
            $io->writeln('Start execution');

            /** @var QuestionHelper $questionHelper */
            $questionHelper = $this->getHelper('question');
            if ($input->getOption('path')) {
                $path = $input->getOption('path');
            } else {
                $questionPath = new Question('Enter path to directory in which the text file should be created (leave empty for root): ');
                $path = $questionHelper->ask($input, $output, $questionPath);
            }

            // add additional path checks (e.g. for non linux based systems)

            if(!empty($path) && !str_ends_with($path, '/')) {
                $file = $path . '/example.txt';
            } else {
                $file = $path . 'example.txt';
            }

            file_put_contents($file, 'Example content');

            $io->success('File was successfully created.');
            return Command::SUCCESS;
        } catch (Exception $e) {
            $io->error('Creation of file failed with following output:');
            $io->writeln($e->getMessage());
            return Command::FAILURE;
        }
    }
}
