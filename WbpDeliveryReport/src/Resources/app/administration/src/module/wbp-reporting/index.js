const { Module } = Shopware;

import './page/wbp-report-list';
import './component/wbp-sidebar-filter'
import exportOrdersService from "../../services/export.orders.service";

Shopware.Service().register('exportOrdersService', () => {
    return new exportOrdersService(
        Shopware.Application.getContainer('init').httpClient,
        Shopware.Service('loginService')
    )
});

Module.register('wbp-reporting', {
    type: 'core',
    name: 'reporting',
    title: 'wbp-report.general.mainMenuItemGeneral',
    description: 'wbp-report.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    color: '#ff2623',
    icon: 'default-symbol-content',
    favicon: 'icon-module-content.png',

    routes: {
        index: {
            component: 'wbp-report-list',
            path: 'index',
        },
    },

    navigation: [{
        id: 'wbp-reporting',
        label: 'wbp-report.general.mainMenuItemGeneral',
        color: '#ff2623',
        icon: 'default-symbol-content',
        position: 70,
    }, {
        path: 'wbp.reporting.index',
        label: 'wbp-report.general.HeaderTitle',
        parent: 'wbp-reporting',
    }],
});
