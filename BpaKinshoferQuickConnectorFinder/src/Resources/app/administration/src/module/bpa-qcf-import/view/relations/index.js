const { Component, Mixin } = Shopware;
import template from './template.html.twig';
const { Criteria } = Shopware.Data;

Component.register('bpa-qcf-view-relations', {
    template,

    inject: ['repositoryFactory'],

    mixins: [Mixin.getByName('notification')],

    data() {
        return {
            repository: null,
            itemsCollection: null,
            total: 0,
            isLoading: true
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        columns() {
            return [
                {
                    property: 'productNumber',
                    label: this.$tc('bpa-qcf-import.columns.productNumber'),
                    allowInlineEdit: false,
                    allowResize: true,
                    primary: true
                },
                {
                    property: 'group.name',
                    label: this.$tc('bpa-qcf-import.columns.group'),
                    allowInlineEdit: false,
                    allowResize: true,
                    align: 'center'
                },
                {
                    property: 'quickCoupler',
                    label: this.$tc('bpa-qcf-import.columns.quickCoupler'),
                    allowInlineEdit: false,
                    allowResize: true,
                    align: 'center'
                },
                {
                    property: 'loadHook',
                    label: this.$tc('bpa-qcf-import.columns.loadHook'),
                    allowInlineEdit: false,
                    allowResize: true,
                    align: 'center'
                },
                {
                    property: 'basicSet',
                    label: this.$tc('bpa-qcf-import.columns.basicSet'),
                    allowInlineEdit: false,
                    allowResize: true,
                    align: 'center'
                },
                {
                    property: 'safetySet',
                    label: this.$tc('bpa-qcf-import.columns.safetySet'),
                    allowInlineEdit: false,
                    allowResize: true,
                    align: 'center'
                },
            ];
        },

        itemsCriteria() {
            const criteria = new Criteria();
            criteria.addSorting(Criteria.sort('quickCoupler', 'ASC'));

            return criteria;
        }
    },

    created() {
        this.repository = this.repositoryFactory.create('bpa_qcf_group_product');
        this.getList();
    },

    methods: {
        getList() {
            this.isLoading = true;

            this.repository.search(this.itemsCriteria, Shopware.Context.api).then(items => {
                this.total = items.total;
                this.itemsCollection = items;
                this.isLoading = false;
            });
        }
    }
});
