import template from './template.html.twig';
import './styles.scss';

const { Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Shopware.Component.register('bpa-qcf-view-import', {
    template,

    inject: ['bpaQcfImport', 'repositoryFactory'],

    mixins: [
        Mixin.getByName('notification')
    ],

    data() {
        return {
            isLoading: false,
            isLoadingGroupProduct: false,
            importFile: null,
            importFileGroupProduct: null
        };
    },

    computed: {
        disableImporting() {
            return this.isLoading || this.importFile === null;
        },

        disableImportingGroupProduct() {
            return this.isLoadingGroupProduct || this.importFileGroupProduct === null;
        }
    },

    methods: {
        handleError(error) {
            if (!error.response || !error.response.data || !error.response.data.errors) {
                this.createNotificationError({
                    title: this.$tc('bpa-qcf-import.labels.errorNotificationTitle'),
                    message: error.message
                });
            } else {
                error.response.data.errors.forEach((singleError) => {
                    this.createNotificationError({
                        title: this.$tc('bpa-qcf-import.labels.errorNotificationTitle'),
                        message: `${singleError.code}: ${singleError.detail}`
                    });
                });
            }
        },

        onStartProcess() {
            this.isLoading = true;

            this.bpaQcfImport.import(this.importFile, this.handleProgress, {'method': 'importExcavators'}).then((result) => {
                this.importFile = null;

            }).catch((error) => {
                this.handleError(error);
                this.isLoading = false;
            });
        },

        handleProgress(response) {
            if (response.state === 'succeeded') {
                this.createNotificationSuccess({
                    title: this.$tc('bpa-qcf-import.labels.titleImportSuccess'),
                    message: this.$tc('bpa-qcf-import.labels.messageImportSuccess', 0)
                });
                this.onProgressFinished();
                this.$router.push({ name: 'bpa.qcf.import.overview'});

            } else if (response.state === 'failed') {
                this.createNotificationError({
                    title: this.$tc('bpa-qcf-import.labels.titleImportError'),
                    message: response.error
                });
                this.onProgressFinished();
            }
        },

        onStartProcessGroupProduct() {
            this.isLoadingGroupProduct = true;

            this.bpaQcfImport.import(this.importFileGroupProduct, this.handleProgressGroupProduct, {'method': 'importExcavatorGroups'}).then((result) => {
                this.importFileGroupProduct = null;

            }).catch((error) => {
                this.handleError(error);
                this.isLoadingGroupProduct = false;
            });
        },

        handleProgressGroupProduct(response) {
            if (response.state === 'succeeded') {
                this.createNotificationSuccess({
                    title: this.$tc('bpa-qcf-import.labels.titleImportSuccess'),
                    message: this.$tc('bpa-qcf-import.labels.messageImportSuccess', 0)
                });
                this.onProgressGroupProductFinished();
                this.$router.push({ name: 'bpa.qcf.import.relations'});

            } else if (response.state === 'failed') {
                this.createNotificationError({
                    title: this.$tc('bpa-qcf-import.labels.titleImportError'),
                    message: response.error
                });
                this.onProgressGroupProductFinished();
            }
        },

        onProgressFinished() {
            this.isLoading = false;
            this.importFile = null;
        },

        onProgressGroupProductFinished() {
            this.isLoadingGroupProduct = false;
            this.importFileGroupProduct = null;
        }
    }
});
