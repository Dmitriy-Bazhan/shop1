import template from './michelis-e-banner-box-component.html.twig';
import './michelis-e-banner-box-component.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-banner-box-component', {
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

        banners() {
            if (this.element.data && this.element.data.banners && this.element.data.banners.length > 0) {
                return this.element.data.banners;
            }

            return [];
        },

        contextAssetPath() {
            return Shopware.Context.api.assetsPath;
        }
    },

    watch: {
        'element.data.banners': {
            handler() {
                if (this.banners.length > 0) {
                    this.imgSrc = this.banners[0].media.url;
                    this.$emit('active-image-change', this.banners[0].media);
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
            this.initElementConfig('michelis-e-banner-box');
            this.initElementData('michelis-e-banner-box');
            
            if (this.element.data && this.element.data.banners && this.element.data.banners.length > 0) {
                this.imgSrc = this.banners[0].media.url;
                this.$emit('active-image-change', this.banners[this.bannerPos].media);
            } else {
                this.imgSrc = `${this.contextAssetPath}${this.imgPath}`;
            }
        }
    }
});
