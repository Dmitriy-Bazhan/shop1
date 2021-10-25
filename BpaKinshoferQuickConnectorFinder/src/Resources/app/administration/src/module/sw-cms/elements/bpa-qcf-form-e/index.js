import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'bpa-qcf-form-e',
    label: 'bpa-qcf-admin.cms.elements.bpa-qcf-form-e.label',
    component: 'bpa-qcf-form-e-component',
    configComponent: 'bpa-qcf-form-e-config',
    previewComponent: 'bpa-qcf-form-e-preview',
    defaultConfig: {}
});
