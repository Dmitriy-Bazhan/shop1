import template from './wbp-report-list.html.twig';
import './wbp-report-list.scss';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('wbp-report-list', {
    template,

    inject: [
        'repositoryFactory',
        'acl',
        'exportOrdersService'
    ],

    mixins: [
        Mixin.getByName('listing'),
        Shopware.Mixin.getByName('notification')
    ],

    data() {
        return {
            orders: [],
            order: null,
            sortBy: 'orderDateTime',
            sortDirection: 'DESC',
            isLoading: false,
            filterLoading: false,
            showDeleteModal: false,
            firstDate: null,
            lastDate: null,
            downloadStatus: false,
            disabled: false,
            warehouse: null,
            filterCriteria: [],
            storeKey: 'grid.filter.order',
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        orderRepository() {
            return this.repositoryFactory.create('order');
        },

        reportColumns() {
            return this.getReportColumns();
        },

        orderCriteria() {
            const criteria = new Criteria(this.page, this.limit);

            criteria.setTerm(this.term);

            this.sortBy.split(',').forEach(sortBy => {
                criteria.addSorting(Criteria.sort(sortBy, this.sortDirection));
            });

            if (this.warehouse && this.warehouse.length > 2) {
                criteria.addFilter(Criteria.contains('customFields.custom_warehouse', this.warehouse))
            }

            if (this.firstDate) {
                criteria.addFilter(Criteria.range('orderDate', { gte: this.firstDate }))
            }

            if (this.lastDate) {
                criteria.addFilter(Criteria.range('orderDate', { lte: this.lastDate }))
            }

            return criteria;
        }
    },

    watch: {
        orderCriteria: {
            handler() {
                this.getList();
            },
            deep: true
        },
    },

    methods: {
        onEdit(order) {
            if (order && order.id) {
                this.$router.push({
                    name: 'sw.order.detail',
                    params: {
                        id: order.id
                    }
                });
            }
        },

        onInlineEditSave(order) {
            order.save();
        },

        onChangeLanguage() {
            this.getList();
        },

        async getList() {
            this.isLoading = true;

            const criteria = this.orderCriteria

            try {
                const response = await this.orderRepository.search(criteria, Shopware.Context.api);

                this.total = response.total;
                this.orders = response;

                this.orders.forEach(ord => {
                    if (ord.customFields) {
                        if (ord.customFields.custom_driver1_end && ord.customFields.custom_driver1_start) {
                            this.order = ord
                            let d1 = new Date(ord.customFields.custom_driver1_end)
                            let d2 = new Date(ord.customFields.custom_driver1_start)

                            this.order.customFields.custom_driver1_time = this.calculateTime(d1, d2)
                        }
                        if (ord.customFields.custom_driver2_end && ord.customFields.custom_driver2_start) {
                            let d1 = new Date(ord.customFields.custom_driver2_end)
                            let d2 = new Date(ord.customFields.custom_driver2_start)

                            this.order.customFields.custom_driver2_time = this.calculateTime(d1, d2)
                        }
                        this.orderRepository.save(this.order, Shopware.Context.api).then(() => {

                        })
                    }
                })
                this.isLoading = false;
            } catch {
                this.isLoading = false;
            }
        },

        disableDeletion(order) {
            if (!this.acl.can('order.deleter')) {
                return true;
            }

            return order.documents.length > 0;
        },

        calculateTime(dateEnd, dateStart) {
            let date = (dateEnd - dateStart)/1000
            let day =  Math.floor(date/86400)
            let hours = Math.floor((date - (day*86400)) / 3600)
            let minutes = Math.floor((date/60) % 60)

            return day + ' - Day ' + hours + ' - hour ' + minutes + ' - min'
        },

        getReportColumns() {
            return [{
                property: 'orderNumber',
                label: 'sw-order.list.columnOrderNumber',
                routerLink: 'sw.order.detail',
                allowResize: true,
                primary: true
            }, {
                property: 'orderDateTime',
                label: 'sw-order.list.orderDate',
                allowResize: true
            }, {
                property: 'orderCustomer.firstName',
                dataIndex: 'orderCustomer.lastName,orderCustomer.firstName',
                label: 'sw-order.list.columnCustomerName',
                allowResize: true
            }, {
                property: 'customFields.custom_warehouse',
                label: 'wbp-report.title.warehouse',
                allowResize: true
            }, {
                property: 'customFields.custom_driver1',
                label: 'wbp-report.title.driver1',
                allowResize: true
            }, {
                property: 'customFields.custom_driver1_start',
                label: 'wbp-report.title.driver1_start',
                allowResize: true
            }, {
                property: 'customFields.custom_driver1_end',
                label: 'wbp-report.title.driver1_end',
                allowResize: true
            }, {
                property: 'customFields.custom_driver1_time',
                label: 'wbp-report.title.driver1_time',
                allowResize: true
            }, {
                property: 'customFields.custom_driver2',
                label: 'wbp-report.title.driver2',
                allowResize: true
            }, {
                property: 'customFields.custom_driver2_start',
                label: 'wbp-report.title.driver2_start',
                allowResize: true
            }, {
                property: 'customFields.custom_driver2_end',
                label: 'wbp-report.title.driver2_end',
                allowResize: true
            }, {
                property: 'customFields.custom_driver2_time',
                label: 'wbp-report.title.driver2_time',
                allowResize: true
            }];
        },

        exportOrder(data) {
            if (data.event === 'export') {
                let dataCriteria = { 'warehouse': this.warehouse, 'dateFrom': this.firstDate, 'dateTo': this.lastDate }

                this.exportOrdersService.export(dataCriteria)
                    .then((response) => {
                         if (response.data.result === true) {
                             this.downloadStatus = true
                             this.disabled = true
                         } else if (response.data.result === false) {
                             this.createNotificationError({
                                 title: this.$tc('wbp-report.message.errorExport'),
                                 message: response.data.message
                             });
                         } else {
                             this.createNotificationError({
                                 title: this.$tc('wbp-report.message.errorExport'),
                                 message: this.$tc('wbp-report.message.errorExport')
                             });
                         }
                    })
            }
        },

        searchWarehouse(data) {
            this.warehouse = data.searchWarehouse;
            this.firstDate = data.firstDate;
            this.lastDate = data.lastDate;
        },

        resetFilter() {
            this.firstDate = null
            this.lastDate = null
            this.warehouse = null
            this.disabled = false
        },

        onDelete(id) {
            this.showDeleteModal = id;
        },

        onCloseDeleteModal() {
            this.showDeleteModal = false;
        },

        onConfirmDelete(id) {
            this.showDeleteModal = false;

            return this.orderRepository.delete(id, Shopware.Context.api).then(() => {
                this.getList();
            });
        }
    }
});
