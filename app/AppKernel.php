<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            //3rd party modules
            new Symfony\Bundle\AsseticBundle\AsseticBundle(), // Manage assets
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(), // DB migrations
            new EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle(), // Administration
            new FOS\UserBundle\FOSUserBundle(), // Bundle for use database as users backend
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(), // Doctrine extensions
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),// WYSIWYG editor

            //Controller as a service bundle
            new CommonBundle\CommonBundle(),

            //Application modules
            new PhpOfBy\WebsiteBundle\PhpOfByWebsiteBundle(),
            new PhpOfBy\SecurityBundle\PhpOfBySecurityBundle(),
            new PhpOfBy\AdminBundle\PhpOfByAdminBundle(),
            new PhpOfBy\ContentBundle\PhpOfByContentBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();

            //Additional info in profiler
            $bundles[] = new Elao\WebProfilerExtraBundle\WebProfilerExtraBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
