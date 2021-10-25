<?php

namespace NewPluginPlugin\Core\Content\NewPluginData;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(NewPluginDataEntity $entity)
 * @method void              set(string $key, NewPluginDataEntity $entity)
 * @method NewPluginDataEntity[]    getIterator()
 * @method NewPluginDataEntity[]    getElements()
 * @method NewPluginDataEntity|null get(string $key)
 * @method NewPluginDataEntity|null first()
 * @method NewPluginDataEntity|null last()
 */
class NewPluginDataEntityCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return NewPluginDataEntity::class;
    }
}