import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'michelis-four-blocks',
    label: 'michelis.cms.blocks.michelis-four-blocks.label',
    category: 'image',
    component: 'sw-cms-block-michelis-four-blocks',
    previewComponent: 'michelis-four-blocks-preview',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'full_width'
    },
    slots: {
        slotFirst: 'michelis-e-four-blocks',
        slotSecond: 'michelis-e-four-blocks',
        slotThird: 'michelis-e-four-blocks',
        slotFourth: 'michelis-e-four-blocks'
    }
});