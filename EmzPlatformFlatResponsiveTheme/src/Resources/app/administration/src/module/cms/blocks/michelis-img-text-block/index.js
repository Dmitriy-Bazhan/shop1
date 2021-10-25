import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'michelis-img-text-block',
    label: 'michelis.cms.blocks.michelis-img-text-block.label',
    category: 'text-image',
    component: 'sw-cms-block-michelis-img-text-block-component',
    previewComponent: 'sw-cms-block-michelis-img-text-block-preview',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        firstLeft: 'image',
        secondRight: 'michelis-e-img-text-block',
        button: 'michelis-e-button',
    },
});
