<?php

namespace NewPluginPlugin\Controller;

use Shopware\Storefront\Framework\Cache\Annotation\HttpCache;
use NewPluginPlugin\Service\NewPluginFirstService;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Context;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Core\System\SystemConfig\SystemConfigService;


/**
 * @RouteScope(scopes={"storefront"})
 */
class NewPluginController extends StorefrontController
{
    private $productRepository;
    private $newPluginData;
    private $newPluginItem;
    private $firstService;
    private $systemConfigService;

    public function __construct(
        EntityRepositoryInterface $productRepository,
        EntityRepositoryInterface $newPluginData,
        EntityRepositoryInterface $newPluginItem,
        NewPluginFirstService     $firstService,
        SystemConfigService $systemConfigService
    )
    {
        $this->productRepository = $productRepository;
        $this->newPluginData = $newPluginData;
        $this->newPluginItem = $newPluginItem;
        $this->firstService = $firstService;
        $this->systemConfigService = $systemConfigService;
    }

    /**
     * @HttpCache()
     * @Route("/new-plugin", name="newplugin.showProducts", methods={"GET"})
     */
    public function showProducts(Request $request, Context $context): Response
    {
        $criteria = new Criteria();
        $products = $this->productRepository->search($criteria, $context)->getElements();

        return $this->renderStorefront('@NewPluginPlugin/storefront/page/index.html.twig', [
            'customParameter' => 'Custom parameter value',
            'productData' => $products,
        ]);
    }

    /**
     * @HttpCache()
     * @Route("/new-plugin/show-product/{id}", name="newplugin.showProduct", methods={"GET"})
     */
    public function showProduct(Request $request, Context $context): Response
    {
        $productId = $request->get('id');

//        dump($this->productRepository->getDefinition());
//        $criteria = new Criteria();
//        $criteria->addFilter(new EqualsFilter('name', 'product1'));
//        dump($this->productRepository->searchIds($criteria, $context));

        $product = $this->firstService->findProduct($context, $productId);

//        dump($product);
//        die();

        return $this->renderStorefront('@NewPluginPlugin/storefront/page/show-product.html.twig', [
            'customParameter' => 'Custom parameter value',
            'product' => $product,
        ]);
    }

    /**
     * @Route("/show-products-ajax", name="newplugin.showProductsAjax", defaults={"XmlHttpRequest"=true}, methods={"GET"})
     */
    public function showExample(Context $context): JsonResponse
    {
        $criteria = new Criteria();
        $products = $this->productRepository->search($criteria, $context)->getElements();

        return new JsonResponse(['products' => $products]);
    }

    /**
     * @Route("/new-plugin/form", name="newplugin.checkForm", methods={"POST"})
     */
    public function checkForm(Request $request, Context $context): Response
    {
        dump($request->get('text'));
        die();

    }


    /**
     * @Route("/new-plugin/add-settings", name="newplugin.addSettings", methods={"GET"})
     */
    public function addSettings(Context $context): void
    {
        $id = Uuid::randomHex();
        $rand = rand(0,100000);

        $this->newPluginItem->create([
            [
                'id' => $id,
                'item' => 'Item number ' . $rand,
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => null,
            ]
        ], $context);

        $criteria = new Criteria();
        $newPluginItem = $this->newPluginItem->search($criteria, $context)->last();


        $this->newPluginData->create([
            [
                'id' => Uuid::randomHex(),
                'data' => 'data number ' . $rand,
                'itemId' => $newPluginItem->id,
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => null,
            ]
        ], $context);

        $this->newPluginData->create([
            [
                'id' => Uuid::randomHex(),
                'data' => 'data number ' . $rand,
                'itemId' => $newPluginItem->id,
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => null,
            ]
        ], $context);

        dump('Create complete');
        die();
    }

    /**
     * @Route("/new-plugin/show-settings", name="newplugin.showSettings", methods={"GET"})
     */
    public function showSettings(Context $context): void
    {
        $criteria = new Criteria();
        $newPluginItem = $this->newPluginItem->search($criteria->addAssociation('data'), $context)->first();

        dump($newPluginItem);
        die();
    }
}