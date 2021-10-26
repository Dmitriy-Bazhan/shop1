<?php
namespace NewPluginPlugin\Controller\Api;

use NewPluginPlugin\Service\NewPluginFirstService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;

/**
 * @RouteScope(scopes={"api"})
 */
class NewPluginApiController extends AbstractController
{
    private $productRepository;

    public function __construct(
        EntityRepositoryInterface $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }
    /**
     * @Route("/api/show-products-ajax", name="newplugin.api.showProductsAjax", defaults={"XmlHttpRequest"=true}, methods={"GET"})
     */
    public function showExample(Context $context): JsonResponse
    {
        $criteria = new Criteria();
        $products = $this->productRepository->search($criteria, $context)->getElements();

        return new JsonResponse(['products' => $products]);
    }
}