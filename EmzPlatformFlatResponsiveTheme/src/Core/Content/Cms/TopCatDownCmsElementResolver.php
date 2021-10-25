<?php declare(strict_types=1);

namespace Emz\EmzPlatformFlatResponsiveTheme\Core\Content\Cms;

use Psr\Container\ContainerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class TopCatDownCmsElementResolver extends AbstractCmsElementResolver
{
    private const STATIC_SEARCH_KEY = 'michelis-e-top-categories-down';
    
    /**
     * @var container
     */
    private $container;
    
    public function __construct(
        ContainerInterface $container
    )
    {
        $this->container = $container;
    }
    
    public function getType(): string
    {
        return 'michelis-e-top-categories-down';
    }
    
    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $config = $slot->getFieldConfig();
        
        $collection = new CriteriaCollection();
        
        return $collection->all() ? $collection : null;
    }
	
	public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
		$config = $slot->getFieldConfig();
        
        $media = $config->get('media');
        
        if ( $mediaId = $media->getValue() ) {
             $slot->assign([
                'picture' => $this->container->get('media.repository')->search(new Criteria([$mediaId]), Context::createDefaultContext())
                            ->getEntities()
                            ->first() ?? null
             ]);
        }
	}
	
}
