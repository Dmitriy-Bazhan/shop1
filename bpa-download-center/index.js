import './component/bpa-download-center-album-tree';
import './component/bpa-download-center-album-tree-item';
import './component/bpa-download-center-album-files';
import './component/bpa-download-center-album-edit-form';
import './component/bpa-download-center-file-edit-form';
import './page/bpa-download-center-detail';
import fileSynchronizeService from "../../services/file.synchronize.service";
import bpaConfigService from "../../services/bpa.config.service";

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

const { Module } = Shopware;

Shopware.Service().register('fileSynchronizeService', () => {
    return new fileSynchronizeService(
        Shopware.Application.getContainer('init').httpClient,
        Shopware.Service('loginService')
    )
});

Shopware.Service().register('bpaConfigService', () => {
    return new bpaConfigService(
        Shopware.Application.getContainer('init').httpClient,
        Shopware.Service('loginService')
    )
});

Module.register('bpa-download-center', {
    type: 'plugin',
    name: 'download-center',
    title: 'bpa-download-center.general.title',
    description: 'bpa-download-center.general.description',
    color: 'rgb(255, 104, 180)',
    icon: 'default-action-circle-download',
    favicon: 'icon-module-content.png',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            component: 'bpa-download-center-detail',
            path: 'index'
        },

        detail: {
            component: 'bpa-download-center-detail',
            path: 'index/:id',
            redirect: {
                name: 'bpa.download.center.detail.files'
            },

            children: {
                files: {
                    component: 'bpa-download-center-album-files',
                    path: 'files'
                },
                edit: {
                    component: 'bpa-download-center-album-edit-form',
                    path: 'edit'
                },
                file: {
                    component: 'bpa-download-center-file-edit-form',
                    path: 'file-edit/:fileId'
                }
            },

            props: {
                default(route) {
                    return {
                        albumId: route.params.id
                    };
                }
            }
        }
    },

    navigation: [{
        label: 'bpa-download-center.general.title',
        color: 'rgb(255, 104, 180)',
        path: 'bpa.download.center.index',
        icon: 'default-action-circle-download',
        parent: 'sw-content'
    }]
});
