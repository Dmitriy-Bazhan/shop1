import template from './michelis-e-img-text-block-config.html.twig';
import './michelis-e-img-text-block-config.scss';

const { Component, Mixin } = Shopware;

Component.register('michelis-e-img-text-block-config', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    inject: ['repositoryFactory'],

    data() {
        return {
            mediaModalIsOpen: false
        };
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('michelis-e-img-text-block');
            this.initElementData('michelis-e-img-text-block');
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
        }
    }
});