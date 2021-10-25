import template from './template.html.twig';
import './styles.scss';

const { Component, Mixin } = Shopware;

Component.register('bpa-qcf-form-e-component', {
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
            this.initElementConfig('bpa-qcf-form-e');
            this.initElementData('bpa-qcf-form-e');
        }
    }
});
