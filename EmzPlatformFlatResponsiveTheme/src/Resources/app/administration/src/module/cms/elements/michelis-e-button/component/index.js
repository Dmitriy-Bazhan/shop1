import template from './michelis-e-button-component.html.twig';
import './michelis-e-button-component.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-button-component', {
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

    methods: {
        createdComponent() {
            this.initElementConfig('michelis-e-button');
            this.initElementData('michelis-e-button');
        }
    }
});