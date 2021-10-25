<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Class BpaQcfExcavatorCollection
 * @package Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder
 */
class BpaQcfExcavatorCollection extends EntityCollection
{

    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return BpaQcfExcavatorEntity::class;
    }

    /**
     * @return array
     */
    public function getGroupIds(): array
    {
        return $this->fmap(function (BpaQcfExcavatorEntity $entity) {
            return $entity->getGroup()->getId();
        });
    }

    public function processTranslated(): void
    {
        $this->fmap(function (BpaQcfExcavatorEntity $entity) {
            $entity->setTranslated([
                'name' => $entity->getType()
            ]);
        });
    }

}
