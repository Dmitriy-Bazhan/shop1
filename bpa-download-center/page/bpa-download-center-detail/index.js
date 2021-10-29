import template from './bpa-download-center-detail.html.twig';
import pageState from './state';

const {Component, Mixin} = Shopware;


Component.register('bpa-download-center-detail', {
    template,

    inject: ['repositoryFactory', 'fileSynchronizeService'],

    mixins: [
        Mixin.getByName('notification')
    ],

    props: {
        albumId: {
            type: String,
            required: false,
            default: null
        }
    },

    data() {
        return {
            isLoading: false,
            isSaveSuccessful: false
         };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        albumRepository() {
            return this.repositoryFactory.create('bpa_downloadcenter_album');
        },

        filesRepository() {
            return this.repositoryFactory.create('bpa_downloadcenter_files')
        },

        album() {
            if (!Shopware.State.get('bpaDownloadCenterAlbum')) {
                return {};
            }

            return Shopware.State.get('bpaDownloadCenterAlbum').album;
        },

        file() {
            if (!Shopware.State.get('bpaDownloadCenterAlbum')) {
                return {};
            }

            return Shopware.State.get('bpaDownloadCenterAlbum').file;
        },

        files() {
            if (!Shopware.State.get('bpaDownloadCenterAlbum')) {
                return {};
            }

            return Shopware.State.get('bpaDownloadCenterAlbum').files;
        },

        tooltipSave() {
            const systemKey = this.$device.getSystemKey();

            return {
                message: `${systemKey} + S`,
                appearance: 'light'
            };
        },

        tooltipCancel() {
            return {
                message: 'ESC',
                appearance: 'light'
            };
        }
    },

    watch: {
        albumId() {
            this.setAlbum();
            this.setFiles();
        },
    },

    beforeCreate() {
        Shopware.State.registerModule('bpaDownloadCenterAlbum', pageState);
    },

    created() {
        this.createdComponent();
        this.setAlbum();
        this.setFiles();
    },

    beforeDestroy() {
        Shopware.State.unregisterModule('bpaDownloadCenterAlbum');
    },

    methods: {
        createdComponent() {
            this.isLoading = true;
        },

        cancelEdit() {
            this.resetAlbum();
        },

        resetAlbum() {
            this.$router.push({name: 'bpa.download.center.index'});
        },

        setAlbum() {
            this.isLoading = true;

            if (this.albumId === null) {
                Shopware.State.commit('shopwareApps/setSelectedIds', []);

                return Shopware.State.dispatch('bpaDownloadCenterAlbum/loadActiveAlbum', {
                    album: null,
                    repository: this.albumRepository,
                    apiContext: Shopware.Context.api,
                })
                    .then(() => {
                        this.isLoading = false;
                });
            }

            return Shopware.State.dispatch('shopwareApps/setSelectedIds',
                [this.albumId]
            ).then(() => {
                return Shopware.State.dispatch('bpaDownloadCenterAlbum/loadActiveAlbum', {
                    repository: this.albumRepository,
                    apiContext: Shopware.Context.api,
                    id: this.albumId
                });
            })
                .then(() => {
                    this.isLoading = false;
                });
        },

        setFiles() {
            this.isLoading = true;

             return Shopware.State.dispatch('shopwareApps/setSelectedIds',
                [this.albumId]
            ).then(() => {
                return Shopware.State.dispatch('bpaDownloadCenterAlbum/loadFilesAlbum', {
                    repository: this.filesRepository,
                    apiContext: Shopware.Context.api,
                    id: this.albumId
                });
            })
                .then(() => {
                    this.isLoading = false;
                });
        },

        saveFinish() {
            this.isSaveSuccessful = false;
        },

        onSaveFile() {
            this.isSaveSuccessful = false;

            this.isLoading = true;
            this.filesRepository.save(this.file, Shopware.Context.api).then(() => {
                return this.setFiles();
            }).then(() => {
                this.isSaveSuccessful = true;
                this.createNotificationSuccess({
                    title: this.$tc('global.default.success'),
                    message: this.$tc('bpa-download-center.detail.messageSaveFileSuccess', 0, {
                        name: this.file.name
                    })
                });
            }).catch(() => {
                this.isLoading = false;

                this.createNotificationError({
                    message: this.$tc(
                        'global.notification.notificationSaveErrorMessageRequiredFieldsInvalid'
                    )
                });
            }).finally(() => {
                this.isLoading = false;
            })

        },

        onSave() {
            this.isSaveSuccessful = false;

            this.isLoading = true;
            this.albumRepository.save(this.album, Shopware.Context.api).then(() => {
                return this.setAlbum();
            }).then(() => {
                this.isSaveSuccessful = true;

                this.createNotificationSuccess({
                    title: this.$tc('global.default.success'),
                    message: this.$tc('bpa-download-center.detail.messageSaveAlbumSuccess', 0, {
                        name: this.album.name
                    })
                });
            }).catch(() => {
                this.isLoading = false;
                this.createNotificationError({
                    message: this.$tc(
                        'global.notification.notificationSaveErrorMessageRequiredFieldsInvalid'
                    )
                });
            }).finally(() => {
                this.isLoading = false;
            });
        },

        onSynchronize() {
            this.isLoading = true;

            if (
                this.album.settings.adapter == null ||
                this.album.settings.host == null ||
                this.album.settings.username == null ||
                this.album.settings.password == null ||
                this.album.settings.root == null
            ) {
                this.createNotificationError({
                    title: this.$tc('global.default.error'),
                    message: this.$tc(
                        'global.notification.notificationSaveErrorMessageRequiredFieldsInvalid'
                    )
                });
                this.isLoading = false;
            } else {
                this.fileSynchronizeService.synchronize(this.album).then((response) => {
                    if (response.success === true) {
                        this.createNotificationSuccess({
                            title: this.$tc('global.default.success'),
                            message: this.$tc('bpa-download-center.detail.messageSynchronizedAlbumSuccess', 0, {
                                name: this.album.name
                            })
                        });
                    } else {
                        this.createNotificationError({
                            title: this.$tc('global.default.error'),
                            message: this.$tc('bpa-download-center.detail.messageSynchronizedAlbumError', 0, {
                                name: this.album.name
                            })
                        });
                    }

                    this.isLoading = false;
                    return Shopware.State.dispatch('bpaDownloadCenterAlbum/loadFilesAlbum', {
                        repository: this.filesRepository,
                        apiContext: Shopware.Context.api,
                        id: this.album.id
                    });
                })
            }
        },

        onChangeLanguage() {

        },

        saveOnLanguageChange() {

        },

        abortOnLanguageChange() {

        }
    }
});
