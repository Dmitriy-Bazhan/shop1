import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'michelis-img-button',
    label: 'michelis.cms.blocks.michelis-img-button.label',
    category: 'text-image',
    component: 'sw-cms-block-michelis-img-button',
    previewComponent: 'sw-cms-michelis-img-button-preview',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        imageSlot: 'michelis-e-img-button',
        button: 'michelis-e-button',
    },
});
