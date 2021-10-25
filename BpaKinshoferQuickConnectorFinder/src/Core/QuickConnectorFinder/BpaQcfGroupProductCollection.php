<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Class BpaQcfGroupProductCollection
 * @package Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder
 */
class BpaQcfGroupProductCollection extends EntityCollection
{

    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return BpaQcfGroupProductEntity::class;
    }

    /**
     * @return array
     */
    public function getProductIds(): array
    {
        return $this->fmap(function (BpaQcfGroupProductEntity $entity) {
            return $entity->getProductId();
        });
    }

}
