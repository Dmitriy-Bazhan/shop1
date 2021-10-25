import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'michelis-banner-box',
    label: 'michelis.cms.blocks.michelis-banner-box.label',
    category: 'text-image',
    component: 'sw-cms-block-michelis-banner-box-component',
    previewComponent: 'michelis-banner-box-preview',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'full_width'
    },
    slots: {
        banners: 'michelis-e-banner-box'
    }
});
