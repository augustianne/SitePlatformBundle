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
class GenerateControllerCommand extends GeneratorCommand
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
            ->addArgument('app', InputArgument::OPTIONAL, 'Application where the controller should be added', 'backend')
        ;
    }

    private function getModels()
    {
        return array(
            'SpCategory',
            // 'SpFeature',
            // 'SpLink',
            // 'SpPage',
            // 'SpSetting',
            // 'SpSubscriber',
            // 'SpTestimonial'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // var_dump($this->getContainer()->get('yan.twig.extension.english'));exit;
        $bundle = $input->getArgument('bundle');
        $app = $input->getArgument('app');
        
        if (is_string($bundle)) {
            $bundle = Validators::validateBundleName($bundle);

            try {
                $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);
            } catch (\Exception $e) {
                $output->writeln(sprintf('<bg=red>Bundle "%s" does not exist.</>', $bundle));
            }
        }
        // var_dump($bundle);exit;
        $models = $this->getModels();

        $dir = $bundle->getPath();
        foreach ($models as $model) {
            // $controllerFile = $dir.'/Controller/'.ucfirst($app).'/'.$model.'Controller.php';
            $dir = $bundle->getPath().'/Controller/'.ucfirst($app);
            // $peer = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";

            // $parameters = array(
            //     'model' => $model,
            //     'table' => $peer::TABLE_NAME,
            //     'extension_alias' => $bundle->getContainerExtension()->getAlias(),
            //     'app' => $app
            // );

            // var_dump($parameters);
            $generator = $this->getGenerator($bundle);
            // var_dump($generator);
            // exit;
            // var_dump($controllerFile);
            $generator->generate($bundle, $model, $dir, $app);
            
        }
        // Create controller
        // Create templates
        // Create queries
        // Create forms


        // var_dump($bundle);
        // $controllers = $this->getModels();



        // foreach ($models as $model) {
            
        // }
        
        

        // $dialog->writeSection($output, 'Controller generation');

        // $generator = $this->getGenerator($bundle);
        // $generator->generate($bundle, $controller, $input->getOption('route-format'), $input->getOption('template-format'), $this->parseActions($input->getOption('actions')));

        // $output->writeln('Generating the bundle code: <info>OK</info>');

        // $dialog->writeGeneratorSummary($output, array());
    }

    // public function interact(InputInterface $input, OutputInterface $output)
    // {
    //     $dialog = $this->getDialogHelper();
    //     $dialog->writeSection($output, 'Welcome to the Symfony2 controller generator');

    //     // namespace
    //     $output->writeln(array(
    //         '',
    //         'Every page, and even sections of a page, are rendered by a <comment>controller</comment>.',
    //         'This command helps you generate them easily.',
    //         '',
    //         'First, you need to give the controller name you want to generate.',
    //         'You must use the shortcut notation like <comment>AcmeBlogBundle:Post</comment>',
    //         '',
    //     ));

    //     while (true) {
    //         $controller = $dialog->askAndValidate($output, $dialog->getQuestion('Controller name', $input->getOption('controller')), array('Sensio\Bundle\GeneratorBundle\Command\Validators', 'validateControllerName'), false, $input->getOption('controller'));
    //         list($bundle, $controller) = $this->parseShortcutNotation($controller);

    //         try {
    //             $b = $this->getContainer()->get('kernel')->getBundle($bundle);

    //             if (!file_exists($b->getPath().'/Controller/'.$controller.'Controller.php')) {
    //                 break;
    //             }

    //             $output->writeln(sprintf('<bg=red>Controller "%s:%s" already exists.</>', $bundle, $controller));
    //         } catch (\Exception $e) {
    //             $output->writeln(sprintf('<bg=red>Bundle "%s" does not exist.</>', $bundle));
    //         }
    //     }
    //     $input->setOption('controller', $bundle.':'.$controller);

    //     // routing format
    //     $defaultFormat = (null !== $input->getOption('route-format') ? $input->getOption('route-format') : 'annotation');
    //     $output->writeln(array(
    //         '',
    //         'Determine the format to use for the routing.',
    //         '',
    //     ));
    //     $routeFormat = $dialog->askAndValidate($output, $dialog->getQuestion('Routing format (php, xml, yml, annotation)', $defaultFormat), array('Sensio\Bundle\GeneratorBundle\Command\Validators', 'validateFormat'), false, $defaultFormat);
    //     $input->setOption('route-format', $routeFormat);

    //     // templating format
    //     $validateTemplateFormat = function($format) {
    //         if (!in_array($format, array('twig', 'php'))) {
    //             throw new \InvalidArgumentException(sprintf('The template format must be twig or php, "%s" given', $format));
    //         }

    //         return $format;
    //     };

    //     $defaultFormat = (null !== $input->getOption('template-format') ? $input->getOption('template-format') : 'twig');
    //     $output->writeln(array(
    //         '',
    //         'Determine the format to use for templating.',
    //         '',
    //     ));
    //     $templateFormat = $dialog->askAndValidate($output, $dialog->getQuestion('Template format (twig, php)', $defaultFormat), $validateTemplateFormat, false, $defaultFormat);
    //     $input->setOption('template-format', $templateFormat);

    //     // actions
    //     $input->setOption('actions', $this->addActions($input, $output, $dialog));

    //     // summary
    //     $output->writeln(array(
    //         '',
    //         $this->getHelper('formatter')->formatBlock('Summary before generation', 'bg=blue;fg-white', true),
    //         '',
    //         sprintf('You are going to generate a "<info>%s:%s</info>" controller', $bundle, $controller),
    //         sprintf('using the "<info>%s</info>" format for the routing and the "<info>%s</info>" format', $routeFormat, $templateFormat),
    //         'for templating',
    //     ));
    // }

    // public function addActions(InputInterface $input, OutputInterface $output, DialogHelper $dialog)
    // {
    //     $output->writeln(array(
    //         '',
    //         'Instead of starting with a blank controller, you can add some actions now. An action',
    //         'is a PHP function or method that executes, for example, when a given route is matched.',
    //         'Actions should be suffixed by <comment>Action</comment>.',
    //         '',
    //     ));

    //     $templateNameValidator = function($name) {
    //         if ('default' == $name) {
    //             return $name;
    //         }

    //         if (2 != substr_count($name, ':')) {
    //             throw new \InvalidArgumentException(sprintf('Template name "%s" does not have 2 colons', $name));
    //         }

    //         return $name;
    //     };

    //     $actions = $this->parseActions($input->getOption('actions'));

    //     while (true) {
    //         // name
    //         $output->writeln('');
    //         $actionName = $dialog->askAndValidate($output, $dialog->getQuestion('New action name (press <return> to stop adding actions)', null), function ($name) use ($actions) {
    //             if (null == $name) {
    //                 return $name;
    //             }

    //             if (isset($actions[$name])) {
    //                 throw new \InvalidArgumentException(sprintf('Action "%s" is already defined', $name));
    //             }

    //             if ('Action' != substr($name, -6)) {
    //                 throw new \InvalidArgumentException(sprintf('Name "%s" is not suffixed by Action', $name));
    //             }

    //             return $name;
    //         });
    //         if (!$actionName) {
    //             break;
    //         }

    //         // route
    //         $route = $dialog->ask($output, $dialog->getQuestion('Action route', '/'.substr($actionName, 0, -6)), '/'.substr($actionName, 0, -6));
    //         $placeholders = $this->getPlaceholdersFromRoute($route);

    //         // template
    //         $defaultTemplate = $input->getOption('controller').':'.substr($actionName, 0, -6).'.html.'.$input->getOption('template-format');
    //         $template = $dialog->askAndValidate($output, $dialog->getQuestion('Templatename (optional)', $defaultTemplate), $templateNameValidator, false, 'default');

    //         // adding action
    //         $actions[$actionName] = array(
    //             'name'         => $actionName,
    //             'route'        => $route,
    //             'placeholders' => $placeholders,
    //             'template'     => $template,
    //         );
    //     }

    //     return $actions;
    // }

    // public function parseActions($actions)
    // {
    //     if (is_array($actions)) {
    //         return $actions;
    //     }

    //     $newActions = array();

    //     foreach (explode(' ', $actions) as $action) {
    //         $data = explode(':', $action);

    //         // name
    //         if (!isset($data[0])) {
    //             throw new \InvalidArgumentException('An action must have a name');
    //         }
    //         $name = array_shift($data);

    //         // route
    //         $route = (isset($data[0]) && '' != $data[0]) ? array_shift($data) : '/'.substr($name, 0, -6);
    //         if ($route) {
    //             $placeholders = $this->getPlaceholdersFromRoute($route);
    //         } else {
    //             $placeholders = array();
    //         }

    //         // template
    //         $template = (0 < count($data) && '' != $data[0]) ? implode(':', $data) : 'default';

    //         $newActions[$name] = array(
    //             'name'         => $name,
    //             'route'        => $route,
    //             'placeholders' => $placeholders,
    //             'template'     => $template,
    //         );
    //     }

    //     return $newActions;
    // }

    // public function getPlaceholdersFromRoute($route)
    // {
    //     preg_match_all('/{(.*?)}/', $route, $placeholders);
    //     $placeholders = $placeholders[1];

    //     return $placeholders;
    // }

    public function parseShortcutNotation($shortcut)
    {
        $entity = str_replace('/', '\\', $shortcut);

        if (false === $pos = strpos($entity, ':')) {
            throw new \InvalidArgumentException(sprintf('The controller name must contain a : ("%s" given, expecting something like AcmeBlogBundle:Post)', $entity));
        }

        return array(substr($entity, 0, $pos), substr($entity, $pos + 1));
    }

    protected function createGenerator()
    {
        return new ControllerGenerator($this->getContainer()->get('filesystem'));
    }
}
