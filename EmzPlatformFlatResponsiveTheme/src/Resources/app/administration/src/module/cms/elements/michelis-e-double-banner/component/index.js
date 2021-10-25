import template from './michelis-e-double-banner-component.html.twig';
import './michelis-e-double-banner-component.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-double-banner-component', {
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

        doubleBanners() {
            if (this.element.data && this.element.data.doubleBanners && this.element.data.doubleBanners.length > 0) {
                return this.element.data.doubleBanners;
            }

            return [];
        },

        contextAssetPath() {
            return Shopware.Context.api.assetsPath;
        }
    },

    watch: {
        'element.data.doubleBanners': {
            handler() {
                if (this.doubleBanners.length > 0) {
                    this.imgSrc = this.doubleBanners[0].media.url;
                    this.$emit('active-image-change', this.doubleBanners[0].media);
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
            this.initElementConfig('michelis-e-double-banner');
            this.initElementData('michelis-e-double-banner');
            
            if (this.element.data && this.element.data.doubleBanners && this.element.data.doubleBanners.length > 0) {
                this.imgSrc = this.doubleBanners[0].media.url;
                this.$emit('active-image-change', this.doubleBanners[this.bannerPos].media);
            } else {
                this.imgSrc = `${this.contextAssetPath}${this.imgPath}`;
            }
        }
    }
});
