import template from './michelis-e-landing-slider-component.html.twig';
import './michelis-e-landing-slider-component.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-landing-slider-component', {
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
            columnWidth: 90,
            sliderPos: 0,
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

        landingSliderItems() {
            if (this.element.data && this.element.data.landingSliderItems && this.element.data.landingSliderItems.length > 0) {
                return this.element.data.landingSliderItems;
            }

            return [];
        },

        contextAssetPath() {
            return Shopware.Context.api.assetsPath;
        }
    },

    watch: {
        'element.data.landingSliderItems': {
            handler() {
                if (this.landingSliderItems.length > 0) {
                    this.imgSrc = this.landingSliderItems[0].media.url;
                    this.$emit('active-image-change', this.landingSliderItems[0].media);
                } else {
                    this.imgSrc = `${this.contextAssetPath}${this.imgPath}`;
                }
            },
            deep: true
        },

        activeMedia() {
            this.sliderPos = this.activeMedia.sliderIndex;
            this.imgSrc = this.activeMedia.url;
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('michelis-e-landing-slider');
            this.initElementData('michelis-e-landing-slider');

            if (this.element.data && this.element.data.landingSliderItems && this.element.data.landingSliderItems.length > 0) {
                this.imgSrc = this.landingSliderItems[0].media.url;
                this.$emit('active-image-change', this.landingSliderItems[this.sliderPos].media);
            } else {
                this.imgSrc = `${this.contextAssetPath}${this.imgPath}`;
            }
        }
    }
});
