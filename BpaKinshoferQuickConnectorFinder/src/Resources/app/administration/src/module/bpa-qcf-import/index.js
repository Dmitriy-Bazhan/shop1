const {Module} = Shopware;

import BpaQcfImportService from './service/bpaQcfImport.service';
import './view/import';
import './view/list';
import './view/relations';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

Shopware.Service().register('bpaQcfImport', () => {
    return new BpaQcfImportService(
        Shopware.Application.getContainer('init').httpClient,
        Shopware.Service('loginService')
    );
});

Module.register('bpa-qcf-import', {
    type: 'plugin',
    name: 'BpaKinshoferQuickConnectorFinder',
    title: 'bpa-qcf-import.common.mainMenuItemGeneral',
    description: 'bpa-qcf-import.common.descriptionTextModule',
    color: 'rgb(87, 217, 163)',
    entity: 'bpa_qcf_excavator',
    icon: 'default-basic-stack-circle',
    favicon: 'icon-module-products.png',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            components: {
                default: 'bpa-qcf-view-import'
            },
            path: 'index'
        },
        overview: {
            components: {
                default: 'bpa-qcf-view-list'
            },
            path: 'overview'
        },
        relations: {
            components: {
                default: 'bpa-qcf-view-relations'
            },
            path: 'relations'
        },
    },

    navigation: [
        {
            id: 'bpa-qcf-import',
            label: 'bpa-qcf-import.common.mainMenuItemGeneral',
            color: 'rgb(87, 217, 163)',
            icon: 'default-basic-stack-circle',
            parent: 'sw-catalogue'
        },
        {
            id: 'bpa-qcf-import-overview',
            label: 'bpa-qcf-import.common.listTitle',
            color: 'rgb(87, 217, 163)',
            path: 'bpa.qcf.import.overview',
            parent: 'bpa-qcf-import'
        },
        {
            id: 'bpa-qcf-import-relations',
            label: 'bpa-qcf-import.common.relationsTitle',
            color: 'rgb(87, 217, 163)',
            path: 'bpa.qcf.import.relations',
            parent: 'bpa-qcf-import'
        }, {
            id: 'bpa-qcf-import-index',
            label: 'bpa-qcf-import.common.importListTitle',
            color: 'rgb(87, 217, 163)',
            path: 'bpa.qcf.import.index',
            parent: 'bpa-qcf-import'
        }
    ]
});
