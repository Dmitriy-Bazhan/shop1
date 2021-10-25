import './component';
import './config';
import './preview';

const { Criteria, EntityCollection } = Shopware.Data;

Shopware.Service('cmsService').registerCmsElement({
    name: 'michelis-e-slider',
    label: 'michelis.cms.elements.michelis-e-slider.label',
    component: 'michelis-e-slider-component',
    configComponent: 'michelis-e-slider-config',
    previewComponent: 'michelis-e-slider-preview',
    defaultConfig: {
        sliderItems: {
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
            elem.config[configKey].value.forEach((sliderItem) => {
                elem.data[configKey].push({
                    uid: sliderItem.uid,
                    newTab: sliderItem.newTab,
                    url: sliderItem.url,
                    media: data[`entity-${entityKey}`].get(sliderItem.mediaId),
                    label: sliderItem.label,
                    title: sliderItem.title,
                    btnName: sliderItem.btnName,
                    titleBackground: sliderItem.titleBackground,
                    urlSecond: sliderItem.urlSecond,
                    btnNameAll: sliderItem.btnNameAll,
                    newTabSecond: sliderItem.newTabSecond,
                });
            });
        });
    }
});
