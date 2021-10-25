import template from './michelis-e-top-categories-down-component.html.twig';
import './michelis-e-top-categories-down-component.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-top-categories-down-component', {
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
            this.initElementConfig('michelis-e-top-categories-down');
            this.initElementData('michelis-e-top-categories-down');
        }
    }

});
