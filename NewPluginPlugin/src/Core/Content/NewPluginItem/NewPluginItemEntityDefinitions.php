<?php

namespace NewPluginPlugin\Core\Content\NewPluginItem;

use NewPluginPlugin\Core\Content\NewPluginData\NewPluginDataEntityDefinitions;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class NewPluginItemEntityDefinitions extends EntityDefinition
{
    public const ENTITY_NAME = 'a_new_plugin_item';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return NewPluginItemEntityCollection::class;
    }

    public function getEntityClass(): string
    {
        return NewPluginItemEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new StringField('item', 'item'),
            (new OneToManyAssociationField(
                'data',
                NewPluginDataEntityDefinitions::class,
                'item_id',
                'id'
            ))
        ]);
    }
}