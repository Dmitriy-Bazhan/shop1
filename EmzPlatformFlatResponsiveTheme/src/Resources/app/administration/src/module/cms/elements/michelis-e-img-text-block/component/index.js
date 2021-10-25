import template from './michelis-e-img-text-block-component.html.twig';
import './michelis-e-img-text-block-component.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-img-text-block-component', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    computed: {
    },

    watch: {
        cmsPageState: {
            deep: true,
            handler() {
                this.$forceUpdate();
            }
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('michelis-e-img-text-block');
            this.initElementData('michelis-e-img-text-block');
        },
    }
});
