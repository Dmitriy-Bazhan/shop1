<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Aggregate;

use Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\BpaQcfGroupProductDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Runtime;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ExcavatorProductExtension extends EntityExtension
{
    /**
     * @return string
     */
    public function getDefinitionClass(): string
    {
        return ProductDefinition::class;
    }

    /**
     * @param FieldCollection $collection
     */
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new OneToManyAssociationField(
                'bpaQcfGroups',
                BpaQcfGroupProductDefinition::class,
                'product_id',
                'id'
            ))
        );

        $collection->add(
            (new OneToManyAssociationField(
                'bpaQcfGroupsProducts',
                BpaQcfGroupProductDefinition::class,
                'product_id',
                'id'
            ))
        );
    }
}
