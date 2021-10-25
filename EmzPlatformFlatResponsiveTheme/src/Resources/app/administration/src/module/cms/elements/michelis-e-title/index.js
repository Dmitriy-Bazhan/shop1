import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'michelis-e-title',
    label: 'michelis.cms.elements.michelis-e-title.label',
    component: 'michelis-e-title-component',
    configComponent: 'michelis-e-title-config',
    previewComponent: 'michelis-e-title-preview',
    defaultConfig: {
        title: {
            source: 'static',
            value: 'Some Title Text'
        }
    }
});
