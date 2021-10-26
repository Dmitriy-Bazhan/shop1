import template from './wbp-sidebar-filter.html.twig';
import './wbp-sidebar-filter.scss';

const { Component } = Shopware;

Component.register('wbp-sidebar-filter', {
    template,

    data() {
        return {
            searchWarehouse: null,
            event: null,
            firstDate: null,
            lastDate: null,
            configFirstDate: {
                dateFormat: 'Y-m-d'
            },
            configLastDate: {
                minDate: '',
                dateFormat: 'Y-m-d'
            }
        }
    },

    props: {
        downloadStatus: {
            type: Boolean,
            required: true
        },

        disabled: {
            type: Boolean,
            required: true
        }
    },

    methods: {
        exportOrder() {
            this.$emit('wbp-report-export-order', {
                event: 'export',
            });
        },

        resetFilter() {
            this.firstDate = null
            this.lastDate = null
            this.searchWarehouse = null
            this.$emit('wbp-report-reset-filter')
        },

        onSearch() {
            this.configLastDate.minDate = this.firstDate
            this.$emit('wbp-report-search', {
                searchWarehouse: this.searchWarehouse,
                firstDate: this.firstDate,
                lastDate: this.lastDate
            });
        },
    }
});
