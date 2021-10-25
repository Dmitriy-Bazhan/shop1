import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'bpa-qcf-form',
    label: 'bpa-qcf-admin.cms.blocks.bpa-qcf-form.label',
    category: 'form',
    component: 'sw-cms-block-bpa-qcf-form',
    previewComponent: 'bpa-qcf-form-preview',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'full_width'
    },
    slots: {
        form: 'bpa-qcf-form-e'
    }
});
