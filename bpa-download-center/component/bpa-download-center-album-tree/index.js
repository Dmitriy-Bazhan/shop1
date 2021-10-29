import template from './bpa-download-center-album-tree.html.twig';
import './bpa-download-center-album-tree.scss';

const { Component, Data: { Criteria } } = Shopware;

Component.register('bpa-download-center-album-tree', {
    template,

    inject: ['repositoryFactory', 'fileSynchronizeService'],

    props: {
        albumId: {
            type: String,
            required: false,
            default: null
        },
    },

    data() {
        return {
            loadedAlbums: {},
            isLoadingInitialData: true,
            linkContext: 'bpa.download.center.detail'
        };
    },

    created() {
        this.createdComponent();
    },

    computed: {
        albumRepository() {
            return this.repositoryFactory.create('bpa_downloadcenter_album');
        },

        albumSettingRepository() {
            return this.repositoryFactory.create('bpa_downloadcenter_album_settings')
        },

        albums() {
            return Object.values(this.loadedAlbums);
        },

        album() {
            return Shopware.State.get('bpaDownloadCenterAlbum').album;
        },
    },

    watch: {
        album(oldVal, newVal) {
            // load data when path is available
            if (!oldVal && this.isLoadingInitialData) {
                return;
            }

            // back to index
            if (newVal === null) {
                return;
            }

            // reload after save
            if (oldVal && newVal.id === oldVal.id) {
                this.albumRepository.get(newVal.id, Shopware.Context.api).then((newAlbum) => {
                    this.loadedAlbums[newAlbum.id] = newAlbum;
                    this.loadedAlbums = { ...this.loadedAlbums };
                });
            }
        }
    },

    methods: {
        createdComponent() {
            this.loadAlbums().then(() => {
                this.isLoadingInitialData = false;
            });
        },

        loadAlbums() {
            return this.albumRepository
                .search(new Criteria(), Shopware.Context.api)
                .then((albums) => {
                    albums.forEach((album) => {
                        this.addAlbum(album);
                    });
                });
        },

        createNewElement(contextItem, name = '') {
            const newAlbum = this.albumRepository.create(Shopware.Context.api);
            const settingsAlbum = this.albumSettingRepository.create(Shopware.Context.api);

            newAlbum.name = name;
            newAlbum.position = 0;
            newAlbum.isActive = true;
            newAlbum.isNotListed = false;
            newAlbum.description = ' ';

            newAlbum.save = () => {
                return this.albumRepository.save(newAlbum, Shopware.Context.api).then((e) => {
                    const newAlbumId = JSON.parse(e.config.data).id

                    settingsAlbum.albumId = newAlbumId;
                    settingsAlbum.adapter = null
                    settingsAlbum.host = null
                    settingsAlbum.username = null
                    settingsAlbum.password = null
                    settingsAlbum.port = null
                    settingsAlbum.useSsl = true
                    settingsAlbum.passiveMode = false

                    this.albumSettingRepository.save(settingsAlbum, Shopware.Context.api).then(() => {
                    })
                })
            }

            if (name != null) {
                this.addAlbum(newAlbum);
            }

            return newAlbum;
        },

        addAlbum(album) {
            if (!album) {
                return;
            }

            this.loadedAlbums = { ...this.loadedAlbums, [album.id]: album };
        },

        addAlbums(albums) {
            albums.forEach((album) => {
                this.loadedAlbums[album.id] = album;
            });
            this.loadedAlbums = { ...this.loadedAlbums };
        },

        onDeleteAlbum({data: album}) {
            if (album.isNew() && !album.name) {
                this.$delete(this.loadedAlbums, album.id);
                return Promise.resolve();
            }

            return this.albumRepository.delete(album.id).then((res) => {
                this.$delete(this.loadedAlbums, album.id);
                return Promise.resolve();
            })
        },

        getChildrenFromParent(parentId) {
            return this.onGetTreeItems(parentId);
        },

        changeAlbumRoute(album, action = '') {
            const route = {
                name: action === 'edit' ? 'bpa.download.center.detail.edit' : 'bpa.download.center.detail',
                params: { id: album.id }
            };

            if (this.album && this.albumRepository.hasChanges(this.album)) {
                this.$emit('unsaved-changes', route);
            } else {
                this.$router.push(route);
            }
        },

        getAlbumUrl(album) {
            return this.$router.resolve({
                name: this.linkContext,
                params: { id: album.id }
            }).href;
        }
    }
});
