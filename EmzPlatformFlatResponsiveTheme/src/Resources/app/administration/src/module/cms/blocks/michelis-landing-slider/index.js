import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'michelis-landing-slider',
    label: 'michelis.cms.blocks.michelis-landing-slider.label',
    category: 'image',
    component: 'sw-cms-block-michelis-landing-slider-component',
    previewComponent: 'michelis-landing-slider-preview',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'full_width'
    },
    slots: {
        landingSlider: 'michelis-e-landing-slider'
    }
});
