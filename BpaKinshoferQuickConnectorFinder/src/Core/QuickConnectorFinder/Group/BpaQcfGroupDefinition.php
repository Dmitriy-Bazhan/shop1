<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Group;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\BpaQcfExcavatorDefinition;

/**
 * Class BpaQcfGroupDefinition
 * @package Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Group
 */
class BpaQcfGroupDefinition extends EntityDefinition {

    public const ENTITY_NAME = 'bpa_qcf_group';

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
        return BpaQcfGroupCollection::class;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return BpaQcfGroupEntity::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),
            (new StringField('name', 'name'))->addFlags(new ApiAware(), new Required()),
            (new CreatedAtField()),
            (new UpdatedAtField()),

            (new OneToManyAssociationField(
                'excavators',
                BpaQcfExcavatorDefinition::class,
                'bpa_qcf_group_id',
                'id'
            ))
        ]);
    }

}
