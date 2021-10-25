import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'michelis-e-banner-box',
    label: 'michelis.cms.elements.michelis-e-banner-box.label',
    component: 'michelis-e-banner-box-component',
    configComponent: 'michelis-e-banner-box-config',
    previewComponent: 'michelis-e-banner-box-preview',
    defaultConfig: {
        banners: {
            source: 'static',
            value: [],
            required: true,
            entity: {
                name: 'media'
            }
        }
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
            elem.config[configKey].value.forEach((banner) => {
                elem.data[configKey].push({
                    newTab: banner.newTab,
                    url: banner.url,
                    media: data[`entity-${entityKey}`].get(banner.mediaId),
                    mediaUrl: banner.mediaUrl,
                    title: banner.title,
                    btnName: banner.btnName,
                    description: banner.description,
                });
            });
        });
    }
});
