import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'michelis-double-banner',
    label: 'michelis.cms.blocks.michelis-double-banner.label',
    category: 'text-image',
    component: 'michelis-double-banner-component',
    previewComponent: 'michelis-double-banner-preview',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'full_width'
    },
    slots: {
        doubleBanners: 'michelis-e-double-banner'
    }
});
