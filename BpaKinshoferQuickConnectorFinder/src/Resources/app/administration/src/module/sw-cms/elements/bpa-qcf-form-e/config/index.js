import template from './template.html.twig';

const { Component, Mixin } = Shopware;

Component.register('bpa-qcf-form-e-config', {
    template,

    mixins: [ Mixin.getByName('cms-element') ],

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('bpa-qcf-form-e');
        },
    }
});
