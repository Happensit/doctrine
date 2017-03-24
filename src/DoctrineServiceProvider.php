<?php

namespace Happensit\Doctrine;

use Commty\Simple\Application;
use Happensit\Doctrine\Extensions\TablePrefix;
use Commty\Simple\ServiceProvider\ServiceProviderInterface;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\Setup;

class DoctrineServiceProvider implements ServiceProviderInterface
{
    public $connectionOptions = [];

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $entityPath = $app->getBasePath() . '/app/Entity';
        $proxyDir = $app->getStoragePath() . '/cache/proxies';

        $config = Setup::createAnnotationMetadataConfiguration([$entityPath], false, $proxyDir, null, false);

        $tablePrefix = new TablePrefix(getenv("DB_PREFIX"));

        $eventManager = new EventManager();
        $eventManager->addEventListener(Events::loadClassMetadata, $tablePrefix);

        $app->container->setInstance(
            EntityManager::class,
            EntityManager::create($this->getConnectionOptions(), $config, $eventManager)
        );
    }

    /**
     * @return array
     */
    public function getConnectionOptions()
    {
        $connectionConf = [
            'driver' => getenv("DB_DRIVER"),
            'host' => getenv("DB_HOST"),
            'port' => getenv("DB_PORT"),
            'dbname' => getenv("DB_NAME"),
            'user' => getenv("DB_USER"),
            'password' => getenv("DB_PASSWORD"),
            'charset' => 'UTF-8',
        ];

        return array_replace($connectionConf, $this->connectionOptions);
    }
}
