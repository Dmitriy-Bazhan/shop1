<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder;

use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Content\Product\ProductDefinition;
use Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Group\BpaQcfGroupDefinition;

class BpaQcfGroupProductDefinition extends EntityDefinition {

    public const ENTITY_NAME = 'bpa_qcf_group_product';

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    /**
     * @return string
     */
    public function getCollectionClass(): string
    {
        return BpaQcfGroupProductCollection::class;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return BpaQcfGroupProductEntity::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),
            (new StringField('quick_coupler', 'quickCoupler'))->addFlags(new ApiAware()),
            (new StringField('product_number', 'productNumber'))->addFlags(new ApiAware()),
            (new BoolField('load_hook', 'loadHook'))->addFlags(new ApiAware(), new Required()),
            (new StringField('basic_set', 'basicSet'))->addFlags(new ApiAware()),
            (new StringField('safety_set', 'safetySet'))->addFlags(new ApiAware()),
            (new FkField('bpa_qcf_group_id', 'groupId', BpaQcfGroupDefinition::class))->addFlags(new ApiAware(), new Required()),
            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new ApiAware(), new Required()),
            (new FkField('basic_set_product_id', 'basicSetProductId', ProductDefinition::class))->addFlags(new ApiAware()),
            (new FkField('safety_set_product_id', 'safetySetProductId', ProductDefinition::class))->addFlags(new ApiAware()),
            (new CreatedAtField()),
            (new UpdatedAtField()),
            (new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id', true)),
            (new ManyToOneAssociationField('basicSetProduct', 'basic_set_product_id', ProductDefinition::class, 'id', true)),
            (new ManyToOneAssociationField('safetySetProduct', 'safety_set_product_id', ProductDefinition::class, 'id', true)),
            (new ManyToOneAssociationField('group', 'bpa_qcf_group_id', BpaQcfGroupDefinition::class, 'id', true)),
        ]);
    }

}
