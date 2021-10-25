<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Manufacturer;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Class BpaQcfManufacturerCollection
 * @package Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Manufacturer
 */
class BpaQcfManufacturerCollection extends EntityCollection
{

    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return BpaQcfManufacturerEntity::class;
    }

    public function processTranslated(): void
    {
        $this->fmap(function (BpaQcfManufacturerEntity $entity) {
            $entity->setTranslated([
                'name' => $entity->getName()
            ]);
        });
    }

}
