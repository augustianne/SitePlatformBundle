<?php

/*
 * This file is part of SitePlatform bundle.
 *
 * Yan Barreta <augustianne.barreta@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yan\Bundle\SitePlatformBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Generates a controller.
 *
 * @author  Yan Barreta
 */
class ControllerGenerator extends Generator
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function generate(BundleInterface $bundle, $model, $dir, $app)
    {
        $controllerFile = $dir.'/'.$model.'Controller.php';
        if (file_exists($controllerFile)) {
            throw new \RuntimeException(sprintf('Controller "%s" already exists', $model));
        }
            
        $peer = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";
        $parameters = array(
            'app' => $app,
            'extension_alias' => $bundle->getContainerExtension()->getAlias(),
            'model' => $model,
            'modelVariable' => lcfirst($model),
            'name' => $bundle->getName(),
            'namespace' => $bundle->getNamespace(),
            'table' => $peer::TABLE_NAME
        );

        // foreach ($actions as $i => $action) {
        //     // get the actioname without the sufix Action (for the template logical name)
        //     $actions[$i]['basename'] = substr($action['name'], 0, -6);
        //     $params = $parameters;
        //     $params['action'] = $actions[$i];

        //     // create a template
        //     $template = $actions[$i]['template'];
        //     if ('default' == $template) {
        //         $template = $bundle->getName().':'.$controller.':'.substr($action['name'], 0, -6).'.html.'.$templateFormat;
        //     }

        //     if ('twig' == $templateFormat) {
        //         $this->renderFile('controller/Template.html.twig.twig', $dir.'/Resources/views/'.$this->parseTemplatePath($template), $params);
        //     } else {
        //         $this->renderFile('controller/Template.html.php.twig', $dir.'/Resources/views/'.$this->parseTemplatePath($template), $params);
        //     }

        //     $this->generateRouting($bundle, $controller, $actions[$i], $routeFormat);
        // }

        // $parameters['actions'] = $actions;

        $this->renderFile('controller/Controller.php.twig', $controllerFile, $parameters);
        // $this->renderFile('controller/ControllerTest.php.twig', $dir.'/Tests/Controller/'.$controller.'ControllerTest.php', $parameters);
    }

    // public function get
}
