import template from './michelis-e-four-blocks-config.html.twig';
import './michelis-e-four-blocks-config.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-four-blocks-config', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    inject: ['repositoryFactory'],

    data() {
        return {
            mediaModalIsOpen: false
        };
    },

    computed: {
        mediaRepository() {
            return this.repositoryFactory.create('media');
        },

        uploadTag() {
            return `michelis-e-four-blocks-config-${this.element.id}`;
        },

        previewSource() {

            if (this.element.data && this.element.data.media && this.element.data.media.id) {
                return this.element.data.media;
            }

            return this.element.config.media.value;
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('michelis-e-four-blocks');
            this.initElementData('michelis-e-four-blocks');
        },

        async onImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId, Shopware.Context.api);

            this.element.config.media.value = mediaEntity.id;

            this.updateElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },

        onImageRemove() {
            this.element.config.media.value = null;

            this.updateElementData();

            this.$emit('element-update', this.element);
        },

        onCloseModal() {
            this.mediaModalIsOpen = false;
        },

        onSelectionChanges(mediaEntity) {
            if(mediaEntity[0]) {
                const media = mediaEntity[0];

                this.element.config.media.value = media.id;

                this.updateElementData(media);

                this.$emit('element-update', this.element);
            }
        },

        updateElementData(media = null) {
            this.$set(this.element.data, 'mediaId', media === null ? null : media.id);
            this.$set(this.element.data, 'media', media);
        },

        onOpenMediaModal() {
            this.mediaModalIsOpen = true;
        }
    }
});