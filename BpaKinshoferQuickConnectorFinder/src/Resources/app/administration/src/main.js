import './module/bpa-qcf-import';
import './module/sw-cms/elements/bpa-qcf-form-e';
import './module/sw-cms/blocks/bpa-qcf-form';
import './app/component/structure/sw-search-bar-item';

const { Application } = Shopware;

Application.addServiceProviderDecorator('searchTypeService', searchTypeService => {
    searchTypeService.upsertType('bpa_qcf_excavator', {
        entityName: 'bpa_qcf_excavator',
        entityService: 'bpaQcfExcavatorService',
        placeholderSnippet: 'bpa-qcf-import.searchSnippet',
        listingRoute: 'bpa.qcf.import.overview'
    });

    return searchTypeService;
});
