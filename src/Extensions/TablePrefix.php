<?php

namespace Happensit\Doctrine\Extensions;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class TablePrefix
 * @package App\ServiceProvider\Doctrine
 */
class TablePrefix implements EventSubscriber
{
    protected $prefix = '';

    public function __construct($prefix)
    {
        $this->prefix = (string)$prefix;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['loadClassMetadata'];
    }

    /**
     * @param LoadClassMetadataEventArgs $args
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {

        /** @var ClassMetadataInfo $classMetadata */
        $classMetadata = $args->getClassMetadata();
        if ($classMetadata->isInheritanceTypeSingleTable() && !$classMetadata->isRootEntity()) {
            return;
        }

        $classMetadata->setPrimaryTable(['name' => $this->prefix . $classMetadata->getTableName()]);
        $associationMappings = $classMetadata->associationMappings;
        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY) {
                if (!empty($associationMappings[$fieldName]['joinTable'])) {
                    $mappedTableName = $associationMappings[$fieldName]['joinTable']['name'];
                    $associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
                }
            }
        }
    }
}
