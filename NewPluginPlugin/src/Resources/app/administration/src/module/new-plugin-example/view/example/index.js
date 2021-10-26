import template from './template.html.twig';

const {Criteria} = Shopware.Data;
const {Component, Mixin} = Shopware;

Component.register('new-plugin-view-example', {
    template,

    // inject: {
    //     jokeService: 'joker',
    //     inject: ['repositoryFactory']
    // },

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('listing')
    ],

    metaInfo() {
        return {
            title: 'New Plugin Items'
        };
    },

    data() {
        return {
            repository: null,
            isLoading: true,
            itemsCollection: null,
            total: 0
        };
    },

    computed: {
        aNewPluginItemRepository() {
            return this.repositoryFactory.create('a_new_plugin_item');
        },
        columns() {
            return [
                {
                    property: 'id',
                    dataIndex: 'id',
                    label: 'ID',
                    allowInlineEdit: false,
                    allowResize: true,
                    primary: true
                },
                {
                    property: 'item',
                    label: 'Item',
                    allowInlineEdit: false,
                    allowResize: true,
                    align: 'center'
                }
            ];
        },
        itemsCriteria() {
            const criteria = new Criteria();
            const params = this.getMainListingParams();

            params.sortBy = params.sortBy || 'id';
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

            this.aNewPluginItemRepository.search(this.itemsCriteria, Shopware.Context.api).then(items => {
                this.total = items.total;
                this.itemsCollection = items;
                this.isLoading = false;
            });
        }
    }

    // mounted() {
    //     this.jokeService.joke().then(
    //         (joke) => {
    //             console.log(joke);
    //             // this.products = joke;
    //         }
    //     );
    // }
});