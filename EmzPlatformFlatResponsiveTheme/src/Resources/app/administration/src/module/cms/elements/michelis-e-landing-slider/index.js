import './component';
import './config';
import './preview';

const { Criteria, EntityCollection } = Shopware.Data;

Shopware.Service('cmsService').registerCmsElement({
    name: 'michelis-e-landing-slider',
    label: 'michelis.cms.elements.michelis-e-landing-slider.label',
    component: 'michelis-e-landing-slider-component',
    configComponent: 'michelis-e-landing-slider-config',
    previewComponent: 'michelis-e-landing-slider-preview',
    defaultConfig: {
        landingSliderItems: {
            source: 'static',
            value: [],
            required: true,
            entity: {
                name: 'media'
            }
        },
    },
    enrich: function enrich(elem, data) {
        if (Object.keys(data).length < 1) {
            return;
        }

        Object.keys(elem.config).forEach((configKey) => {
            const entity = elem.config[configKey].entity;

            if (!entity) {
                return;
            }

            const entityKey = entity.name;
            if (!data[`entity-${entityKey}`]) {
                return;
            }

            elem.data[configKey] = [];
            elem.config[configKey].value.forEach((landingSliderItem) => {
                elem.data[configKey].push({
                    uid: landingSliderItem.uid,
                    newTab: landingSliderItem.newTab,
                    url: landingSliderItem.url,
                    media: data[`entity-${entityKey}`].get(landingSliderItem.mediaId),
                    label: landingSliderItem.label,
                    title: landingSliderItem.title,
                    btnName: landingSliderItem.btnName,
                });
            });
        });
    }
});
