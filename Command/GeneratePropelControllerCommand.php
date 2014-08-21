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

use Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Yan\Bundle\SitePlatformBundle\Command\GeneratorCommand;
use Yan\Bundle\SitePlatformBundle\Generator\ControllerGenerator;

/**
 * Generates backend controllers.
 *
 * @author  Yan Barreta
*/
class GeneratePropelControllerCommand extends GeneratorCommand
{
    /**
     * @see Command
     */
    public function configure()
    {
        $this
            ->setName('siteplatform:generate:controller')
            ->setDescription('Generates backend controller for a specific table.')
            ->addArgument('bundle', InputArgument::REQUIRED, 'Bundle name where the controller should be added')
            ->addArgument('model', InputArgument::REQUIRED, 'Propel model to create the controller for.')
            ->addArgument('app', InputArgument::OPTIONAL, 'Application where the controller should be added', 'backend')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $bundle = $input->getArgument('bundle');
        $app = $input->getArgument('app');
        $model = $input->getArgument('model');
        
        if (is_string($bundle)) {
            $bundle = Validators::validateBundleName($bundle);

            try {
                $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);
            } catch (\Exception $e) {
                $output->writeln(sprintf('<bg=red>Bundle "%s" does not exist.</>', $bundle));
            }
        }
        
        $class = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";
        if (!class_exists($class)) {
            $output->writeln(sprintf('<bg=red>No propel models found for %s.</>', $model));
            return;
        }

        $output->writeln('Generating controller for '.$model);

        $dir = $bundle->getPath();
        $generator = $this->getGenerator($bundle);
        $generator->generate($bundle, $model, $dir, $app);
    }

    protected function createGenerator()
    {
        return new ControllerGenerator($this->getContainer()->get('filesystem'));
    }
}
