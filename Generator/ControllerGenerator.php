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

use \BasePeer;

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
        $this->generateTemplates($bundle, $model, $dir, $app);
        $this->generateQueryFunction($bundle, $model, $dir, $app);
        $this->generateManager($bundle, $model, $dir, $app);
        $this->generateController($bundle, $model, $dir, $app);
    }

    public function addRoute(BundleInterface $bundle, $model, $app, $action, $route)
    {
        $file = $bundle->getPath().'/Resources/config/routing_'.$app.'.yml';
        if (file_exists($file)) {
            $content = file_get_contents($file);
        } elseif (!is_dir($dir = $bundle->getPath().'/Resources/config')) {
            mkdir($dir);
        }

        $alias = $bundle->getContainerExtension()->getAlias();
        $peer = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";
        $tableName = $peer::TABLE_NAME;

        $controller = sprintf("%s:%s/%s:%s", $bundle->getName(), ucfirst($app), $model, $action);
        $name = sprintf("%s_%s_%s_%s", $alias, $app, $tableName, $action);
        
        if (!isset($content)) {
            $content = '';
        }

        $content .= sprintf(
            "\n%s:\n    pattern: %s\n    defaults: { _controller: %s }\n",
            $name,
            $route,
            $controller
        );

        $flink = fopen($file, 'w');
        chmod($file, 0777);

        if ($flink) {
            $write = fwrite($flink, $content);

            if ($write) {
                fclose($flink);
            } else {
                throw new \RunTimeException(sprintf('We cannot write into file "%s", has that file the correct access level?', $file));
            }
        } else {
            throw new \RunTimeException(sprintf('Problems with generating file "%s", did you gave write access to that directory?', $file));
        }
    }

    public function addService(BundleInterface $bundle, $model, $dir, $app)
    {
        $file = $bundle->getPath().'/Resources/config/services.yml';
        if (file_exists($file)) {
            $content = file_get_contents($file);
        } elseif (!is_dir($dir = $bundle->getPath().'/Resources/config')) {
            mkdir($dir);
        }

        $alias = $bundle->getContainerExtension()->getAlias();
        $peer = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";
        $tableName = $peer::TABLE_NAME;

        $serviceName = sprintf("%s.model_manager.%s", $alias, $tableName);
        $class = sprintf("%s\Service\ModelManager\%sManager", $bundle->getNamespace(), $model);
        
        if (!isset($content)) {
            $content = '';
        }

        $content .= sprintf(
            "\n    %s:\n        class: %s\n        arguments:\n            dispatcher: @event_dispatcher\n",
            $serviceName,
            $class
        );

        $flink = fopen($file, 'w');
        chmod($file, 0777);

        if ($flink) {
            $write = fwrite($flink, $content);

            if ($write) {
                fclose($flink);
            } else {
                throw new \RunTimeException(sprintf('We cannot write into file "%s", has that file the correct access level?', $file));
            }
        } else {
            throw new \RunTimeException(sprintf('Problems with generating file "%s", did you gave write access to that directory?', $file));
        }
    }

    public function generateTemplates(BundleInterface $bundle, $model, $dir, $app)
    {
        $this->generateIndex($bundle, $model, $dir, $app);
        $this->generateAdd($bundle, $model, $dir, $app);
        $this->generateEdit($bundle, $model, $dir, $app);
    }

    public function generateIndex(BundleInterface $bundle, $model, $dir, $app)
    {
        $peer = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";
        $table = $peer::TABLE_NAME;

        $parameters = array(
            'app' => $app,
            'extension_alias' => $bundle->getContainerExtension()->getAlias(),
            'model' => $model,
            'modelVariable' => lcfirst($model),
            'name' => $bundle->getName(),
            'namespace' => $bundle->getNamespace(),
            'table' => $peer::TABLE_NAME,
            'columnHeaders' => $peer::getFieldNames(BasePeer::TYPE_RAW_COLNAME),
            'columnValues' => $peer::getFieldNames(BasePeer::TYPE_STUDLYPHPNAME)
        );

        $templateDir = $dir.'/Resources/views/'.ucfirst($app).'/'.$model;

        if (file_exists($templateDir.'/index.html.twig')) {
            return;
        }

        $this->renderFile('views/index.html.twig.twig', $templateDir.'/index.html.twig', $parameters);

        $route = sprintf("/%s/index", str_replace('_', '-', $table));
        $this->addRoute($bundle, $model, $app, 'index', $route);
    }

    public function generateAdd(BundleInterface $bundle, $model, $dir, $app)
    {
        $peer = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";
        $table = $peer::TABLE_NAME;

        $parameters = array(
            'app' => $app,
            'extension_alias' => $bundle->getContainerExtension()->getAlias(),
            'model' => $model,
            'modelVariable' => lcfirst($model),
            'name' => $bundle->getName(),
            'namespace' => $bundle->getNamespace(),
            'table' => $peer::TABLE_NAME,
            'columnHeaders' => $peer::getFieldNames(BasePeer::TYPE_RAW_COLNAME),
            'columnValues' => $peer::getFieldNames(BasePeer::TYPE_STUDLYPHPNAME)
        );

        $this->generateForm($bundle, $model, $dir, $app);

        $templateDir = $dir.'/Resources/views/'.ucfirst($app).'/'.$model;

        if (file_exists($templateDir.'/add.html.twig')) {
            return;
        }

        $this->renderFile('views/add.html.twig.twig', $templateDir.'/add.html.twig', $parameters);

        $route = sprintf("/%s/add", str_replace('_', '-', $table));
        $this->addRoute($bundle, $model, $app, 'add', $route);
    }

    public function generateEdit(BundleInterface $bundle, $model, $dir, $app)
    {
        $peer = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";
        $table = $peer::TABLE_NAME;

        $parameters = array(
            'app' => $app,
            'extension_alias' => $bundle->getContainerExtension()->getAlias(),
            'model' => $model,
            'modelVariable' => lcfirst($model),
            'name' => $bundle->getName(),
            'namespace' => $bundle->getNamespace(),
            'table' => $peer::TABLE_NAME,
            'columnHeaders' => $peer::getFieldNames(BasePeer::TYPE_RAW_COLNAME),
            'columnValues' => $peer::getFieldNames(BasePeer::TYPE_STUDLYPHPNAME)
        );

        $templateDir = $dir.'/Resources/views/'.ucfirst($app).'/'.$model;

        if (file_exists($templateDir.'/edit.html.twig')) {
            return;
        }

        $this->renderFile('views/edit.html.twig.twig', $templateDir.'/edit.html.twig', $parameters);

        $route = sprintf("/%s/edit/{id}", str_replace('_', '-', $table));
        $this->addRoute($bundle, $model, $app, 'edit', $route);

        $route = sprintf("/%s/delete/{id}", str_replace('_', '-', $table));
        $this->addRoute($bundle, $model, $app, 'delete', $route);
    }

    public function generateForm(BundleInterface $bundle, $model, $dir, $app)
    {
        $peer = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";
        $table = $peer::TABLE_NAME;

        $parameters = array(
            'app' => $app,
            'extension_alias' => $bundle->getContainerExtension()->getAlias(),
            'model' => $model,
            'modelVariable' => lcfirst($model),
            'name' => $bundle->getName(),
            'namespace' => $bundle->getNamespace(),
            'table' => $peer::TABLE_NAME,
            'columnNames' => $peer::getFieldNames(BasePeer::TYPE_RAW_COLNAME)
        );

        $templateDir = $dir.'/Form/Type';

        if (file_exists($templateDir.'/'.$model.'Type.php')) {
            return;
        }

        $this->renderFile('form/ModelType.php.twig', $templateDir.'/'.$model.'Type.php', $parameters);        

        $templateDir = $dir.'/Resources/views/'.ucfirst($app).'/'.$model;

        if (file_exists($templateDir.'/Partial/form.html.twig')) {
            return;
        }

        $this->renderFile('views/Partial/form.html.twig.twig', $templateDir.'/Partial/form.html.twig', $parameters);
    }

    public function generateQueryFunction(BundleInterface $bundle, $model, $dir, $app)
    {
        $file = $bundle->getPath().'/Model/'.$model.'Query.php';
        
        if (!file_exists($file)) {
            throw new \RunTimeException('No model query found for '.$model.'. Make sure you have generated all propel models.');
        }

        $alias = $bundle->getContainerExtension()->getAlias();
        $class = "\\".$bundle->getNamespace()."\Model\\".$model."Query";

        $peer = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";
        $table = $peer::TABLE_NAME;
        
        if (method_exists($class, 'filter' .$model. 'sByOptions')) { 
            return;
        }

        $parameters = array(
            'model' => $model,
            'columnNames' => $peer::getFieldNames(BasePeer::TYPE_RAW_COLNAME),
            'phpNames' => $peer::getFieldNames(BasePeer::TYPE_PHPNAME)
        );

        $content = $this->render('model/ModelQuery.php.twig', $parameters);
        
        $lines = file($file);
        for($i=(count($lines)-1); $i > 0; $i--){
            $line = $lines[$i];
            if(preg_match('/}/', $line)){
                $lines[$i] = $content;
                $lines[$i+1] = <<<EOF
                

$line
EOF;
                break;
            }
        }
        fwrite(($handler = fopen($file, 'w+')), implode("", $lines));
        fclose($handler);
    }

    public function generateManager(BundleInterface $bundle, $model, $dir, $app)
    {
        $peer = "\\".$bundle->getNamespace()."\Model\\".$model."Peer";
        $table = $peer::TABLE_NAME;

        $parameters = array(
            'app' => $app,
            'extension_alias' => $bundle->getContainerExtension()->getAlias(),
            'model' => $model,
            'modelVariable' => lcfirst($model),
            'name' => $bundle->getName(),
            'namespace' => $bundle->getNamespace(),
            'table' => $peer::TABLE_NAME,
            'columnNames' => $peer::getFieldNames(BasePeer::TYPE_RAW_COLNAME)
        );

        $templateDir = $dir.'/Service/ModelManager';

        if (file_exists($templateDir.'/Partial/form.html.twig')) {
            return;
        }

        $this->renderFile('manager/ModelManager.php.twig', $templateDir.'/'.$model.'Manager.php', $parameters);

        $this->addService($bundle, $model, $dir, $app);

    }

    public function generateController(BundleInterface $bundle, $model, $dir, $app)
    {
        $controllerFile = $dir.'/Controller/'.ucfirst($app).'/'.$model.'Controller.php';
        
        if (file_exists($controllerFile)) {
            return;
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

        // generate controller
        $this->renderFile('controller/Controller.php.twig', $controllerFile, $parameters);
    }
}
