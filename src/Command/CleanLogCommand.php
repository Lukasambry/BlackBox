<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CleanLogCommand extends Command
{
    protected static $defaultName = 'app:clean-log';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Nettoie le dossier var/log')
            ->setHelp('Cette commande supprime tous les fichiers .log plus anciens que X jours dans var/log.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $logPath = __DIR__ . '/../../var/log';
        $filesystem = new Filesystem();

        if (!$filesystem->exists($logPath)) {
            $output->writeln('<comment>Le dossier log n’existe pas : ' . $logPath . '</comment>');
            return Command::SUCCESS;
        }

        $daysThreshold = 7;
        $timeThreshold = time() - ($daysThreshold * 24 * 60 * 60);

        $filesDeleted = 0;
        $filesNotDeleted = 0;

        foreach (glob($logPath . '/*.log') as $file) {
            if (is_file($file) && filemtime($file) < $timeThreshold) {
                $filesystem->remove($file);
                $filesDeleted++;
            } else {
                $filesNotDeleted++;
            }
        }

        $output->writeln("<info>$filesDeleted fichiers supprimés.</info>");
        $output->writeln("<info>$filesNotDeleted fichiers conservés.</info>");

        return Command::SUCCESS;
    }
}
