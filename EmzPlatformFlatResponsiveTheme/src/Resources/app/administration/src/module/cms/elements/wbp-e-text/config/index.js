import template from './sw-cms-wbp-e-text-config.html.twig';

const { Component, Mixin } = Shopware;

Component.register('sw-cms-wbp-e-text-config', {
    template,

    mixins: [
        Mixin.getByName('cms-element'),
    ],

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('wbp-e-text');
            this.initElementData('wbp-e-text');
        },

        onBlur(content) {
            this.emitChanges(content);
        },

        onInput(content) {
            this.emitChanges(content);
        },

        emitChanges(content) {
            if (content !== this.element.config.content.value) {
                this.element.config.content.value = content;
                this.$emit('element-update', this.element);
            }
        },
    },
});
