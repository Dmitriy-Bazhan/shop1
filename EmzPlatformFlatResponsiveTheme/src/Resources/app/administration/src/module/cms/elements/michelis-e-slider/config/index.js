import template from './michelis-e-slider-config.html.twig';
import './michelis-e-slider-config.scss';

const { Component, Mixin } = Shopware;
const { cloneDeep } = Shopware.Utils.object;
const { Criteria, EntityCollection } = Shopware.Data;

Component.register('michelis-e-slider-config', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    inject: ['repositoryFactory'],

    data() {
        return {
            mediaModalIsOpen: false,
            initialFolderId: null,
            entity: this.element,
            mediaItems: [],
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
            if (this.element.config && this.element.config.sliderItems && this.element.config.sliderItems.value) {
                return this.element.config.sliderItems.value;
            }

            return [];
        },

    },

    created() {
        this.createdComponent();
    },

    methods: {
        async createdComponent() {
            this.initElementConfig('michelis-e-slider');

            if (this.element.config.sliderItems.value.length > 0) {
                const mediaIds = this.element.config.sliderItems.value.map((configElement) => {
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
            let newUid = Math.random().toString(36).substring(8);
            
            this.element.config.sliderItems.value.push({
                uid: newUid,
                mediaUrl: mediaItem.url,
                mediaId: mediaItem.id,
                url: null,
                newTab: false,
                label: null,
                title: null,
                btnName: this.$tc('michelis.cms.elements.michelis-e-slider.labels.btnText'),
                titleBackground: null,
                urlSecond: null,
                btnNameAll: this.$tc('michelis.cms.elements.michelis-e-slider.labels.btnTextAll'),
                newTabSecond: false,
            });

            this.mediaItems.push(mediaItem);

            this.updateMediaDataValue();
            this.emitUpdateEl();
        },

        onItemRemove(mediaItem, index) {
            const key = mediaItem.id;
            const { value } = this.element.config.sliderItems;

            this.element.config.sliderItems.value = value.filter(
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
            let newUid = Math.random().toString(36).substring(8);
            
            mediaItems.forEach((item) => {
                this.element.config.sliderItems.value.push({
                    uid: newUid,
                    mediaUrl: item.url,
                    mediaId: item.id,
                    url: null,
                    newTab: false,
                    label: null,
                    title: null,
                    btnName: this.$tc('michelis.cms.elements.michelis-e-slider.labels.btnText'),
                    titleBackground: null,
                    urlSecond: null,
                    btnNameAll: this.$tc('michelis.cms.elements.michelis-e-slider.labels.btnTextAll'),
                    newTabSecond: false,
                });
            });
            
            this.mediaItems.push(...mediaItems);

            this.updateMediaDataValue();
            this.emitUpdateEl();
        },

        updateMediaDataValue() {
            if (this.element.config.sliderItems.value) {
                const sliderItems = cloneDeep(this.element.config.sliderItems.value);

                sliderItems.forEach((sliderItem) => {
                    this.mediaItems.forEach((mediaItem) => {
                        if (sliderItem.mediaId === mediaItem.id) {
                            sliderItem.media = mediaItem;
                        }
                    });

                });
                
                this.$set(this.element.data, 'sliderItems', sliderItems);
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
