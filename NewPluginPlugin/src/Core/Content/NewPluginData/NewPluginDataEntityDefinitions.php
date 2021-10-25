<?php

namespace NewPluginPlugin\Core\Content\NewPluginData;

use NewPluginPlugin\Core\Content\NewPluginItem\NewPluginItemEntityDefinitions;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class NewPluginDataEntityDefinitions extends EntityDefinition
{
    public const ENTITY_NAME = 'a_new_plugin_data';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return NewPluginDataEntityCollection::class;
    }

    public function getEntityClass(): string
    {
        return NewPluginDataEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),
            new StringField('data', 'data'),
            (new FkField('item_id', 'itemId', NewPluginItemEntityDefinitions::class))->addFlags(new ApiAware(), new Required()),
            (new ManyToOneAssociationField('item',
                'item_id',
                NewPluginItemEntityDefinitions::class,
                'id', true)),
        ]);
    }
}