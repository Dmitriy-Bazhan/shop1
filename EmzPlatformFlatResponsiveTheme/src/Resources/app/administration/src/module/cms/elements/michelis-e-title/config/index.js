import template from './michelis-e-title-config.html.twig';
import './michelis-e-title-config.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-title-config', {
    template,

    mixins: [ Mixin.getByName('cms-element') ],

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('michelis-e-title');
        },
    }
});
