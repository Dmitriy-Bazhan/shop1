<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Page\Finder;

use Shopware\Core\Content\Product\SalesChannel\Listing\AbstractProductListingRoute;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\DataCollectorTranslator;

/**
 * Class FinderPageLoader
 * @package Bpa\KinshoferQuickConnectorFinder\Page\Finder
 */
class FinderPageLoader
{

    /**
     * @var GenericPageLoaderInterface
     */
    private $genericLoader;

    /**
     * @var AbstractProductListingRoute
     */
    private $listingRoute;

    /**
     * @var DataCollectorTranslator
     */
    private $translator;

    /**
     * FinderPageLoader constructor.
     * @param GenericPageLoaderInterface $genericLoader
     * @param AbstractProductListingRoute $listingRoute
     * @param DataCollectorTranslator $translator
     */
    public function __construct(
        GenericPageLoaderInterface $genericLoader,
        AbstractProductListingRoute $listingRoute,
        DataCollectorTranslator $translator
    ) {
        $this->genericLoader = $genericLoader;
        $this->listingRoute = $listingRoute;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @param SalesChannelContext $salesChannelContext
     * @return FinderPage
     */
    public function load(Request $request, SalesChannelContext $salesChannelContext): FinderPage
    {
        $page = $this->genericLoader->load($request, $salesChannelContext);
        $page = FinderPage::createFrom($page);

        if ($page->getMetaInformation()) {
            $page->getMetaInformation()->setRobots('noindex,follow');

            $page->getMetaInformation()->setMetaTitle($this->translator->trans('bpa-quick-connector-finder.finderPageTitle', []));
        }

        $categoryId = $request->get('navigationId', $salesChannelContext->getSalesChannel()->getNavigationCategoryId());

        $criteria = new Criteria();
        $criteria->setTitle('bpa-finder-page');
        $criteria->addFilter(
            new NotFilter(
                NotFilter::CONNECTION_AND,
                [new EqualsFilter('product.bpaQcfGroups.group.id', null)]
            )
        );

        $result = $this->listingRoute
            ->load($categoryId, $request, $salesChannelContext, $criteria)
            ->getResult();

        $page->setListing($result);

        return $page;
    }
}
