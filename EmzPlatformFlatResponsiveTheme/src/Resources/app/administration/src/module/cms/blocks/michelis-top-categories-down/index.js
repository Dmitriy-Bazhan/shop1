import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'michelis-top-categories-down',
    label: 'michelis.cms.blocks.michelis-top-categories-down.label',
    category: 'text-image',
    component: 'sw-cms-block-michelis-top-categories-down-component',
    previewComponent: 'michelis-top-categories-down-preview',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'full_width'
    },
    slots: {
        topCategoriesFirst: 'michelis-e-top-categories-down',
        topCategoriesSecond: 'michelis-e-top-categories-down'
    }
});
