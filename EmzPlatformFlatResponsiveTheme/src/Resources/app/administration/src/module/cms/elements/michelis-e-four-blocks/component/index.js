import template from './michelis-e-four-blocks-component.html.twig';
import './michelis-e-four-blocks-component.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-four-blocks-component', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    watch: {
        cmsPageState: {
            deep: true,
            handler() {
                this.$forceUpdate();
            }
        }
    },

    created() {
        this.createdComponent();
    },

    computed: {
        mediaUrl() {
            const context = Shopware.Context.api;
            const elemData = this.element.data.media;
            const mediaSource = this.element.config.media.source;

            if (mediaSource === 'mapped') {
                const demoMedia = this.getDemoValue(this.element.config.media.value);

                if (demoMedia && demoMedia.url) {
                    return demoMedia.url;
                }
            }

            if (elemData && elemData.id) {
                return this.element.data.media.url;
            }

            if (elemData && elemData.url) {
                return `${context.assetsPath}${elemData.url}`;
            }

            return `${context.assetsPath}/administration/static/img/cms/preview_mountain_large.jpg`;
        }
    },

    methods: {
        createdComponent() {
            this.initElementConfig('michelis-e-four-blocks');
            this.initElementData('michelis-e-four-blocks');
        }
    }
});