<?php

namespace NewPluginPlugin\Core\Content\NewPluginItem;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(NewPluginItemEntity $entity)
 * @method void              set(string $key, NewPluginItemEntity $entity)
 * @method NewPluginItemEntity[]    getIterator()
 * @method NewPluginItemEntity[]    getElements()
 * @method NewPluginItemEntity|null get(string $key)
 * @method NewPluginItemEntity|null first()
 * @method NewPluginItemEntity|null last()
 */
class NewPluginItemEntityCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return NewPluginItemEntity::class;
    }
}