import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'michelis-title',
    label: 'michelis.cms.blocks.michelis-title.label',
    category: 'text',
    component: 'sw-cms-block-michelis-title',
    previewComponent: 'michelis-title-preview',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'full_width'
    },
    slots: {
        title: 'michelis-e-title'
    }
});
