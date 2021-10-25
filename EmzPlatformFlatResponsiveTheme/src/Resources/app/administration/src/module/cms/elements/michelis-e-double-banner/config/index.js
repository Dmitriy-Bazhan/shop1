import template from './michelis-e-double-banner-config.html.twig';
import './michelis-e-double-banner-config.scss';

const { Component, Mixin } = Shopware;
const { cloneDeep } = Shopware.Utils.object;
const Criteria = Shopware.Data.Criteria;

Component.register('michelis-e-double-banner-config', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    inject: ['repositoryFactory'],

    data() {
        return {
            mediaModalIsOpen: false,
            entity: this.element,
            mediaItems: []
        };
    },

    computed: {
        uploadTag() {
            return `cms-element-media-config-${this.element.id}`;
        },

        mediaRepository() {
            return this.repositoryFactory.create('media');
        },

        defaultFolderName() {
            return this.cmsPageState.pageEntityName;
        },

        items() {
            if (this.element.config && this.element.config.doubleBanners && this.element.config.doubleBanners.value) {
                return this.element.config.doubleBanners.value;
            }

            return [];
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        async createdComponent() {
            this.initElementConfig('michelis-e-double-banner');

            if (this.element.config.doubleBanners.value.length > 0) {
                const mediaIds = this.element.config.doubleBanners.value.map((configElement) => {
                    return configElement.mediaId;
                });

                const criteria = new Criteria();
                criteria.setIds(mediaIds);

                const searchResult = await this.mediaRepository.search(criteria, Shopware.Context.api);
                this.mediaItems = mediaIds.map((mediaId) => {
                    return searchResult.get(mediaId);
                });
            }
        },

        onImageUpload(mediaItem) {
            this.element.config.doubleBanners.value.push({
                mediaUrl: mediaItem.url,
                mediaId: mediaItem.id,
                url: null,
                newTab: false,
                label: null,
                title: null,
                btnName: this.$tc('michelis.cms.elements.michelis-e-double-banner.labels.btnText'),
                cost: null,
            });

            this.mediaItems.push(mediaItem);

            this.updateMediaDataValue();
            this.emitUpdateEl();
        },

        onItemRemove(mediaItem, index) {
            const key = mediaItem.id;
            const { value } = this.element.config.doubleBanners;

            this.element.config.doubleBanners.value = value.filter(
                (item, i) => {
                    return (item.mediaId !== key || i !== index);
                }
            );

            this.mediaItems = this.mediaItems.filter(
                (item, i) => {
                    return (item.id !== key || i !== index);
                }
            );

            this.updateMediaDataValue();
            this.emitUpdateEl();
        },

        onCloseMediaModal() {
            this.mediaModalIsOpen = false;
        },

        onMediaSelectionChange(mediaItems) {
            mediaItems.forEach((item) => {
                this.element.config.doubleBanners.value.push({
                    mediaUrl: item.url,
                    mediaId: item.id,
                    url: null,
                    newTab: false,
                    label: null,
                    title: null,
                    btnName: this.$tc('michelis.cms.elements.michelis-e-double-banner.labels.btnText'),
                    cost: null,
                });
            });

            this.mediaItems.push(...mediaItems);

            this.updateMediaDataValue();
            this.emitUpdateEl();
        },

        updateMediaDataValue() {
            if (this.element.config.doubleBanners.value) {
                const doubleBanners = cloneDeep(this.element.config.doubleBanners.value);

                doubleBanners.forEach((banner) => {
                    this.mediaItems.forEach((mediaItem) => {
                        if (banner.mediaId === mediaItem.id) {
                            banner.media = mediaItem;
                        }
                    });
                });

                this.$set(this.element.data, 'doubleBanners', doubleBanners);
            }
        },

        onOpenMediaModal() {
            this.mediaModalIsOpen = true;
        },
        
        emitUpdateEl() {
            this.$emit('element-update', this.element);
        }
    }
});
