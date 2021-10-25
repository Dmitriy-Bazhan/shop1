import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'michelis-button',
    label: 'michelis.cms.blocks.michelis-button.label',
    category: 'text',
    component: 'michelis-button',
    previewComponent: 'michelis-button-preview',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'full_width'
    },
    slots: {
        button: 'michelis-e-button'
    }
});
