const { Component, Mixin } = Shopware;
import template from './template.html.twig';
const { Criteria } = Shopware.Data;

Component.register('bpa-qcf-view-list', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('listing')
    ],

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
        bpaQcfExcavatorsRepository() {
            return this.repositoryFactory.create('bpa_qcf_excavator');
        },

        columns() {
            return [
                {
                    property: 'type',
                    dataIndex: 'type',
                    label: this.$tc('bpa-qcf-import.columns.type'),
                    allowInlineEdit: false,
                    allowResize: true,
                    primary: true
                },
                {
                    property: 'manufacturer.name',
                    label: this.$tc('bpa-qcf-import.columns.manufacturer'),
                    allowInlineEdit: false,
                    allowResize: true,
                    align: 'center'
                },
                {
                    property: 'group.name',
                    label: this.$tc('bpa-qcf-import.columns.group'),
                    allowInlineEdit: false,
                    allowResize: true,
                    align: 'center'
                }
            ];
        },

        itemsCriteria() {
            const criteria = new Criteria();
            const params = this.getMainListingParams();

            params.sortBy = params.sortBy || 'type';
            params.sortDirection = params.sortDirection || 'ASC';

            criteria.setTerm(this.term);
            criteria.addSorting(Criteria.sort(params.sortBy, params.sortDirection));

            return criteria;
        }
    },

    created() {
        this.getList();
    },

    methods: {
        getList() {
            this.isLoading = true;

            this.bpaQcfExcavatorsRepository.search(this.itemsCriteria, Shopware.Context.api).then(items => {
                this.total = items.total;
                this.itemsCollection = items;
                this.isLoading = false;
            });
        }
    }
});
