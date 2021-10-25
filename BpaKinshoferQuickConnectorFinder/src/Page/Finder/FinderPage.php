<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Page\Finder;

use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingResult;
use Shopware\Storefront\Page\Page;

/**
 * Class FinderPage
 * @package Bpa\KinshoferQuickConnectorFinder\Page\Finder
 */
class FinderPage extends Page
{

    /**
     * @var ProductListingResult
     */
    protected $listing;

    /**
     * @var string
     */
    protected $searchTerm;

    /**
     * @return ProductListingResult
     */
    public function getListing(): ProductListingResult
    {
        return $this->listing;
    }

    /**
     * @param ProductListingResult $listing
     */
    public function setListing(ProductListingResult $listing): void
    {
        $this->listing = $listing;
    }

    /**
     * @return string|null
     */
    public function getSearchTerm(): ?string
    {
        return $this->searchTerm;
    }

    /**
     * @param string $searchTerm
     */
    public function setSearchTerm(string $searchTerm): void
    {
        $this->searchTerm = $searchTerm;
    }

}
