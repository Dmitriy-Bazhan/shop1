import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'wbp-text-image-block',
    label: 'michelis.cms.blocks.wbp-text-image-block.label',
    category: 'text-image',
    component: 'wbp-text-image-block-component',
    previewComponent: 'wbp-text-image-block-preview',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        slotFirst: 'wbp-e-text',
        slotSecond: 'image',
    },
});
