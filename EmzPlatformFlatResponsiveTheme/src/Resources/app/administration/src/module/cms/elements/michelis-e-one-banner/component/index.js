import template from './michelis-e-one-banner-component.html.twig';
import './michelis-e-one-banner-component.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-one-banner-component', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    props: {
        activeMedia: {
            type: [Object, null],
            required: false,
            default: null
        }
    },

    data() {
        return {
            columnCount: 7,
            columnWidth: 90,
            bannerPos: 0,
            imgPath: '/administration/static/img/cms/preview_mountain_large.jpg',
            imgSrc: ''
        };
    },

    computed: {
        gridAutoRows() {
            return `grid-auto-rows: ${this.columnWidth}`;
        },

        uploadTag() {
            return `cms-element-media-config-${this.element.id}`;
        },

        oneBanners() {
            if (this.element.data && this.element.data.oneBanners && this.element.data.oneBanners.length > 0) {
                return this.element.data.oneBanners;
            }

            return [];
        },

        contextAssetPath() {
            return Shopware.Context.api.assetsPath;
        }
    },

    watch: {
        'element.data.oneBanners': {
            handler() {
                if (this.oneBanners.length > 0) {
                    this.imgSrc = this.oneBanners[0].media.url;
                    this.$emit('active-image-change', this.oneBanners[0].media);
                } else {
                    this.imgSrc = `${this.contextAssetPath}${this.imgPath}`;
                }
            },
            deep: true
        },

        activeMedia() {
            this.bannerPos = this.activeMedia.bannerIndex;
            this.imgSrc = this.activeMedia.url;
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('michelis-e-one-banner');
            this.initElementData('michelis-e-one-banner');
            
            if (this.element.data && this.element.data.oneBanners && this.element.data.oneBanners.length > 0) {
                this.imgSrc = this.oneBanners[0].media.url;
                this.$emit('active-image-change', this.oneBanners[this.bannerPos].media);
            } else {
                this.imgSrc = `${this.contextAssetPath}${this.imgPath}`;
            }
        }
    }
});
