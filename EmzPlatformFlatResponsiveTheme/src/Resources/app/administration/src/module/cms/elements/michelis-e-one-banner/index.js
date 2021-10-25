import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'michelis-e-one-banner',
    label: 'michelis.cms.elements.michelis-e-one-banner.label',
    component: 'michelis-e-one-banner-component',
    configComponent: 'michelis-e-one-banner-config',
    previewComponent: 'michelis-e-one-banner-preview',
    defaultConfig: {
        oneBanners: {
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
                    label: banner.label,
                    title: banner.title,
                    btnName: banner.btnName
                });
            });
        });
    }
});
