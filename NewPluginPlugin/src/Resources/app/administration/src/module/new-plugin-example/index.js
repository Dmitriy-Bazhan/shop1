import NewPluginPluginService from './service/newPluginPlugin.service';
import './view/example';

// Shopware.Service().register('NewPluginService', () => {
//     return new NewPluginService(
//         Shopware.Application.getContainer('init').httpClient,
//         Shopware.Service('loginService')
//     );
// });

Shopware.Service().register('joker', (container) => {
    const initContainer = Shopware.Application.getContainer('init');
    return new NewPluginPluginService(initContainer.httpClient);
});


Shopware.Module.register('new-plugin', {
    type: 'plugin',
    name: 'NewPluginMenuModule',
    title: 'New Plugin',
    description: 'New Plugin Description',
    entity: 'a_new_plugin_item',
    color: 'rgb(221,0,37)',
    icon: 'default-basic-stack-circle',
    favicon: 'icon-module-products.png',

    snippets : {

    },

    routes : {
        index: {
            components: {
                default: 'new-plugin-view-example'
            },
            path: 'index'
        },
    },

    navigation: [
        {
            id: 'new-plugin-example',
            label: 'Main',
            color: 'rgb(221,0,37)',
            icon: 'default-basic-stack-circle',
            parent: 'sw-catalogue'
        },
        {
            id: 'new-plugin-example-first',
            label: 'First',
            color: 'rgb(221,0,37)',
            icon: 'default-basic-stack-circle',
            path: 'new.plugin.index',
            parent: 'new-plugin-example'
        }
    ]
});