<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Manufacturer;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

/**
 * Class BpaQcfManufacturerEntity
 * @package Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Manufacturer
 */
class BpaQcfManufacturerEntity extends Entity
{

    /**
     * @var string
     */
    protected $name;

    use EntityIdTrait;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

}
