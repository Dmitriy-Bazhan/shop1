import template from './bpa-download-center-album-files.twig';
import './bpa-download-center-album-files.scss';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('bpa-download-center-album-files', {
    template,

    inject: ['repositoryFactory', 'bpaConfigService'],

    data() {
        return {
            showDeleteModal: null,
            basePath: null,
            showLink: 0
        }
    },

    computed: {
        columns() {
            return [
            {
                property: 'typeimg',
                width: '20px',
                label: '',
            }, {
                property: 'name',
                width: '40px',
                label: 'bpa-download-center.albumFiles.albumName',
                useCustomSort: true
            }, {
                property: 'title',
                    width: '400px',
                label: 'bpa-download-center.albumFiles.albumTitle',
                useCustomSort: true
            }, {
                property: 'fileSize',
                width: '40px',
                label: 'bpa-download-center.albumFiles.albumFileSize',
                useCustomSort: true
            }, {
                property: 'type',
                width: '40px',
                label: 'bpa-download-center.albumFiles.albumFileType',
                useCustomSort: true
            }
            ];
        },

        files() {
            this.getBasePath();
            return Shopware.State.get('bpaDownloadCenterAlbum').files;
        },

        albumRepository() {
            return this.repositoryFactory.create('bpa_downloadcenter_album');
        },

        filesRepository() {
            return this.repositoryFactory.create('bpa_downloadcenter_files');
        }
    },

    created() {
        this.showLinkFile();
    },

    methods: {
        onEditFile(file) {
            Shopware.State.commit('bpaDownloadCenterAlbum/setActiveFile', {file: file})

            const route = {
                name: 'bpa.download.center.detail.file',
                params: { id: file.albumId ,fileId: file.id }
            };
            this.$router.push(route);
        },

        onCloseDeleteModal() {
            this.showDeleteModal = null;
        },

        onDeleteFile(file) {
            Shopware.State.commit('bpaDownloadCenterAlbum/setActiveFile', {file: file})

            this.onCloseDeleteModal();
            const route = {
                name: 'bpa.download.center.detail.file',
                params: { id: file.albumId ,fileId: file.id }
            };
            this.$router.push(route);
        },

        showLinkFile() {
            this.bpaConfigService.getConfig().then((result) => {
                this.showLink = result.showLinkInHeader
            })
        },

        onConfirmDelete(file) {
            if (!file) {
                return;
            }

            this.onCloseDeleteModal();

            this.filesRepository.delete(file.id)
                .then(() => {
                    return Shopware.State.dispatch('bpaDownloadCenterAlbum/loadFilesAlbum', {
                        repository: this.filesRepository,
                        apiContext: Shopware.Context.api,
                        id: file.albumId
                    });
                });
        },

        getFilePreview(type) {
            const icons = Shopware.Context.api.assetsPath + '/bpaplatformdownloadcenter/dist/assets/icon/file-icon-vivid/'
            let path

            if (type == 'IMAGE') {
                path = icons + 'image.svg'
            } else if (type == 'VIDEO') {
                path = icons + 'mp4.svg'
            } else if (type == 'SOUND') {
                path = icons + 'mp3.svg'
            } else {
                path = icons + 'blank.svg'
            }

            return path
        },

        getBasePath() {
            this.basePath = Shopware.Context.api.basePath
        },
    }
});
