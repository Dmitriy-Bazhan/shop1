<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Controller;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\Routing\Annotation\Route;
use Bpa\KinshoferQuickConnectorFinder\Page\Finder\FinderPageLoader;
use Shopware\Core\Content\Product\SalesChannel\Listing\AbstractProductListingRoute;
use Bpa\KinshoferQuickConnectorFinder\Service\FinderService;

/**
 * @RouteScope(scopes={"storefront"})
 */
class FinderController extends StorefrontController
{

    /**
     * @var FinderPageLoader
     */
    private $finderPageLoader;

    /**
     * @var AbstractProductListingRoute
     */
    private $listingRoute;

    /**
     * @var FinderService
     */
    private $finderService;

    /**
     * FinderController constructor.
     * @param FinderPageLoader $finderPageLoader
     * @param AbstractProductListingRoute $listingRoute
     * @param FinderService $finderService
     */
    public function __construct(
        FinderPageLoader $finderPageLoader,
        AbstractProductListingRoute $listingRoute,
        FinderService $finderService
    ) {
        $this->finderPageLoader = $finderPageLoader;
        $this->listingRoute = $listingRoute;
        $this->finderService = $finderService;
    }

    /**
     * @Route("/bpa-connector-finder", name="bpa.qcf.connector.finder", options={"seo"="false"}, methods={"GET"})
     *
     * @param Request $request
     * @param SalesChannelContext $context
     * @return Response
     */
    public function connectorFinder(Request $request, SalesChannelContext $context): Response
    {


//        $request->query->set("bpaQcfManufacturer",  "5abc6fe1fdae4509a4355fd577916bf2");
//        $request->query->set("bpaQcfType",  "92ef3b2c5f1942b08e40ecc884ce148a");
//        $request->query->set("bpaQcfManufacturer",  "d678af2d077849aea285952e124446d8");
//        $request->query->set("bpaQcfType",  "457897d800c343a39a22c056b1e12296");

        $page = $this->finderPageLoader->load($request, $context);

        return $this->renderStorefront('@Storefront/storefront/page/bpa-qcf-finder/index.html.twig', [
            'page' => $page
        ]);
    }

    /**
     * @Route("/widgets/bpa-connector-finder", name="widgets.bpa.qcf.connector.finder", methods={"GET", "POST"}, defaults={"XmlHttpRequest"=true})
     *
     * @param Request $request
     * @param SalesChannelContext $context
     * @return Response
     */
    public function ajax(Request $request, SalesChannelContext $context): Response
    {
        $request->request->set('no-aggregations', true);
        $page = $this->finderPageLoader->load($request, $context);

        return $this->renderStorefront('@Storefront/storefront/page/bpa-qcf-finder/search-pagelet.html.twig', [
            'page' => $page
        ]);
    }

    /**
     * @Route("/widgets/bpa-connector-finder/filter", name="bpa.qcf.connector.finder.filter", methods={"GET", "POST"}, defaults={"XmlHttpRequest"=true})
     *
     * @param Request $request
     * @param SalesChannelContext $context
     * @return Response
     */
    public function filter(Request $request, SalesChannelContext $context): Response
    {
        $request->request->set('only-aggregations', true);
        $request->request->set('reduce-aggregations', true);
        $navigationId = $context->getSalesChannel()->getNavigationCategoryId();

        $listing = $this->listingRoute
            ->load($navigationId, $request, $context, new Criteria())
            ->getResult();

        $mapped = [];
        foreach ($listing->getAggregations() as $aggregation) {
            $mapped[$aggregation->getName()] = $aggregation;
        }

        return new JsonResponse($mapped);
    }

    /**
     * @Route("/bpa-connector-finder-types", name="bpa.qcf.connector.finder.types", options={"seo"="false"}, methods={"POST"}, defaults={"XmlHttpRequest"=true})
     *
     * @param Request $request
     * @param SalesChannelContext $context
     * @return Response
     */
    public function connectorFinderTypes(Request $request, SalesChannelContext $context): Response
    {
        $manufacturerId = $request->get('manufacturerId', null);
        $bpaTypes = null;

        if($manufacturerId) {
            $bpaTypes = $this->finderService->findExcavatorByManufacturer($context->getContext(), $manufacturerId);
        }

        return $this->renderStorefront('@Storefront/storefront/component/bpa-qcf-finder/select-types.html.twig', [
            'bpaTypes' => $bpaTypes
        ]);
    }

}
