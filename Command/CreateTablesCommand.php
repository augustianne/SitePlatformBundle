<?php

/*
 * This file is part of SitePlatform bundle.
 *
 * Yan Barreta <augustianne.barreta@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yan\Bundle\SitePlatformBundle\Command;

use \RuntimeException;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Create tables that are building blocks to the platform
 *
 * @author  Yan Barreta
*/
class CreateTablesCommand extends ContainerAwareCommand
{
    
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('siteplatform:create-tables')
            ->setDescription('Create tables that are building blocks to the platform')
            ->addArgument('mysql-bin', InputArgument::OPTIONAL, 'Path to mysql binary', '/usr/bin/mysql')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $dbHost = $container->getParameter('site_platform.database_host');
        $dbUser = $container->getParameter('site_platform.database_user');
        $dbPass = $container->getParameter('site_platform.database_password');
        $dbName = $container->getParameter('site_platform.database_name');

        $output->write("Creating tables ... ");
        $filename = $container->get('kernel')->locateResource('@SitePlatformBundle/Resources/sql/init.sql');

        $cmdString = sprintf("%s -h%s -u%s -p%s %s < %s", 
            $input->getArgument('mysql-bin'),
            $dbHost,
            $dbUser,
            $dbPass,
            $dbName,
            $filename
         );

        $process = new Process($cmdString);
         
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        $output->writeln("done.");
    }

}