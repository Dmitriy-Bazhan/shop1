import template from './michelis-e-button-config.html.twig';
import './michelis-e-button-config.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-button-config', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('michelis-e-button');
        }
    }
});
