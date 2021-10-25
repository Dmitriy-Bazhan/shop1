<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Group\BpaQcfGroupEntity;

/**
 * Class BpaQcfExcavatorEntity
 * @package Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder
 */
class BpaQcfExcavatorEntity extends Entity
{

    use EntityIdTrait;

    /**
     * @return BpaQcfGroupEntity
     */
    public function getGroup(): BpaQcfGroupEntity
    {
        return $this->group;
    }

    /**
     * @param BpaQcfGroupEntity $group
     * @return void
     */
    public function setGroup(BpaQcfGroupEntity $group): void
    {
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

}
