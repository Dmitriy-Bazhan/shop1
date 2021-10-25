<?php

namespace NewPluginPlugin\Core\Extensions;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Runtime;

class NewCustomExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new StringField('custom_string', 'customString'))->addFlags(new Runtime())
        );
    }

    public function getDefinitionClass(): string
    {
        return ProductDefinition::class;
    }
}