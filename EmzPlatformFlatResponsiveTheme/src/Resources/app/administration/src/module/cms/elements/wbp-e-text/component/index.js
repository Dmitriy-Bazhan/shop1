import template from './sw-cms-wbp-e-text.html.twig';
import './sw-cms-wbp-e-text.scss';

const { Component, Mixin } = Shopware;

Component.register('sw-cms-wbp-e-text', {
    template,

    mixins: [
        Mixin.getByName('cms-element'),
    ],

    data() {
        return {
            editable: true,
            demoValue: '',
        };
    },

    watch: {
        cmsPageState: {
            deep: true,
            handler() {
                this.updateDemoValue();
                this.$forceUpdate();
            },
        },

        'element.config.content.source': {
            handler() {
                this.updateDemoValue();
            },
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('wbp-e-text');
            this.initElementData('wbp-e-text');
        },

        updateDemoValue() {
            if (this.element.config.content.source === 'mapped') {
                this.demoValue = this.getDemoValue(this.element.config.content.value);
            }
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
