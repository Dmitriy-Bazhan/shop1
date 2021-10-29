import template from './bpa-download-center-album-edit-form.html.twig';

const { Component, Mixin, Context } = Shopware;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();
const { EntityCollection, Criteria } = Shopware.Data;

Component.register('bpa-download-center-album-edit-form', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('placeholder')
    ],

    data() {
        return {
            isLoading: false,
            customerGroups: null,
            customers: null,
        };
    },

    computed: {
        album() {
            return Shopware.State.get('bpaDownloadCenterAlbum').album;
        },

        customerGroupRepository() {
            return this.repositoryFactory.create('customer_group');
        },

        customerRepository() {
            return this.repositoryFactory.create('customer');
        },

        availableAdapters() {
            return [{
                label: 'SFTP',
                value: 'sftp'
            }, {
                label: 'FTP',
                value: 'ftp'
            }];
        },

        ...mapPropertyErrors('album', [
            'description',
            'name',
            'settings.adapter',
            'settings.host'
        ]),

        customerGroupIds: {
            get() {
                return JSON.parse(Shopware.State.get('bpaDownloadCenterAlbum').album.customerGroups) || [];
            },
            set(customerGroupIds) {
                Shopware.State.get('bpaDownloadCenterAlbum').album.customerGroups = JSON.stringify(customerGroupIds) ;
            },
        },

        customerIds: {
            get() {
                return JSON.parse(Shopware.State.get('bpaDownloadCenterAlbum').album.customers) || [];
            },
            set(customerIds) {
                Shopware.State.get('bpaDownloadCenterAlbum').album.customers = JSON.stringify(customerIds) ;
            },
        }
    },

    created() {
        this.createdComponent()
    },

    methods: {
        createdComponent() {
            this.getCustomersGroup()
            this.getCustomers()
        },

        getCustomersGroup() {
            this.customerGroups = new EntityCollection(
                this.customerGroupRepository.route,
                this.customerGroupRepository.entityName,
                Context.api,
            );

            if (this.customerGroupIds && this.customerGroupIds.length <= 0) {
                return Promise.resolve();
            }

            const criteria = new Criteria();
            criteria.setIds(this.customerGroupIds);

            return this.customerGroupRepository.search(criteria, Context.api).then((customerGroups) => {
                this.customerGroups = customerGroups;
            });
        },

        getCustomers() {
            this.customers = new EntityCollection(
                this.customerRepository.route,
                this.customerRepository.entityName,
                Context.api,
            );

            if (this.customerIds && this.customerIds.length <= 0) {
                return Promise.resolve();
            }

            const criteria = new Criteria();
            criteria.setIds(this.customerIds);

            return this.customerRepository.search(criteria, Context.api).then((customers) => {
                this.customers = customers;
            });
        },

        setCustomerGroupIds(customerGroups) {
            this.customerGroupIds = customerGroups.getIds();
            this.customerGroups = customerGroups;
        },

        setCustomerIds(customers) {
            this.customerIds = customers.getIds();
            this.customers = customers;
        },
    }
});
