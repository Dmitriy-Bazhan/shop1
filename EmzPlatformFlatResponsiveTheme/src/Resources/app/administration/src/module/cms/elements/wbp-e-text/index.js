import './component';
import './config';

Shopware.Service('cmsService').registerCmsElement({
    name: 'wbp-e-text',
    label: 'wbp.cms.elements.wbp-e-text.label',
    component: 'sw-cms-wbp-e-text',
    configComponent: 'sw-cms-wbp-e-text-config',
    defaultConfig: {
        content: {
            source: 'static',
            value: `
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. 
                At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            `.trim(),
        },
        verticalAlign: {
            source: 'static',
            value: null,
        },
        title: {
            source: 'static',
            value: null,
        },
        titleM: {
            source: 'static',
            value: null,
        },
        titleSecond: {
            source: 'static',
            value: null,
        },
        time: {
            source: 'static',
            value: null,
        },
    },
});