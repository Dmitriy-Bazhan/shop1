import template from './michelis-e-title-component.html.twig';
import './michelis-e-title-component.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-title-component', {
    template,

    mixins: [ Mixin.getByName('cms-element') ],

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
            this.initElementConfig('michelis-e-title');
            this.initElementData('michelis-e-title');
        }
    }
});
