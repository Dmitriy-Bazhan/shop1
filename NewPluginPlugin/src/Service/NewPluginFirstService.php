<?php

namespace NewPluginPlugin\Service;

use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class NewPluginFirstService
{
    private $productRepository;

    public function __construct(
        EntityRepositoryInterface $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    public function findProduct(Context $context, string $productId)
    {
        $criteria = new Criteria();
        $criteria->addAssociation('media');
        $criteria->addFilter(new EqualsFilter('id', $productId));
        return $this->productRepository->search($criteria, $context)->first();
    }
}