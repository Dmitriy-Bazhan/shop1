<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

/**
 * Class BpaQcfGroupProductEntity
 * @package Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder
 */
class BpaQcfGroupProductEntity extends Entity
{

    use EntityIdTrait;

    /**
     * @var string
     */
    protected $productId;

    /**
     * @var string|null
     */
    protected  $basicSetProductId;

    /**
     * @var string|null
     */
    protected  $safetySetProductId;

    /**
     * @var ProductEntity|void
     */
    protected $basicSetProduct;

    /**
     * @var ProductEntity|void
     */
    protected $safetySetProduct;

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @param string $productId
     * @return void
     */
    public function setGroup(string $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return string|null
     */
    public function getBasicSetProductId(): ?string
    {
        return $this->basicSetProductId;
    }

    /**
     * @param string $basicSetProductId
     * @return void
     */
    public function setBasicSetProductId(string $basicSetProductId): void
    {
        $this->basicSetProductId = $basicSetProductId;
    }

    /**
     * @return string|null
     */
    public function getSafetySetProductId(): ?string
    {
        return $this->safetySetProductId;
    }

    /**
     * @param string $safetySetProductId
     * @return void
     */
    public function setSafetySetProductId(string $safetySetProductId): void
    {
        $this->safetySetProductId = $safetySetProductId;
    }

    /**
     * @return ProductEntity
     */
    public function getBasicSetProduct(): ProductEntity
    {
        return $this->basicSetProduct;
    }

    /**
     * @return ProductEntity
     */
    public function getSafetySetProduct()
    {
        return $this->safetySetProduct;
    }

}
