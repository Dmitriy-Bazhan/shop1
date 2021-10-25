<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\RestrictDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Manufacturer\BpaQcfManufacturerDefinition;
use Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Group\BpaQcfGroupDefinition;

/**
 * Class BpaQcfExcavatorDefinition
 * @package Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder
 */
class BpaQcfExcavatorDefinition extends EntityDefinition {

    public const ENTITY_NAME = 'bpa_qcf_excavator';

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
        return BpaQcfExcavatorCollection::class;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return BpaQcfExcavatorEntity::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),
            (new StringField('type', 'type'))->addFlags(new ApiAware(), new Required()),
            (new FkField('bpa_qcf_manufacturer_id', 'manufacturerId', BpaQcfManufacturerDefinition::class))->addFlags(new ApiAware(), new Required()),
            (new FkField('bpa_qcf_group_id', 'groupId', BpaQcfGroupDefinition::class))->addFlags(new ApiAware(), new Required()),
            (new CreatedAtField()),
            (new UpdatedAtField()),
            (new ManyToOneAssociationField('manufacturer',
                'bpa_qcf_manufacturer_id',
                BpaQcfManufacturerDefinition::class,
                'id', true))->addFlags(new ApiAware(), new RestrictDelete()),
            (new ManyToOneAssociationField('group',
                'bpa_qcf_group_id',
                BpaQcfGroupDefinition::class,
                'id', true))->addFlags(new ApiAware(), new RestrictDelete()),
        ]);
    }
}
