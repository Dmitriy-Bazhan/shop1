import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'michelis-one-banner',
    label: 'michelis.cms.blocks.michelis-one-banner.label',
    category: 'text-image',
    component: 'michelis-one-banner-component',
    previewComponent: 'michelis-one-banner-preview',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'full_width'
    },
    slots: {
        oneBanners: 'michelis-e-one-banner'
    }
});
