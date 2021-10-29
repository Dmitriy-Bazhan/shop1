import './module/new-plugin-example';

// import './app/component/structure/sw-search-bar';


const { Application } = Shopware;

Application.addServiceProviderDecorator('searchTypeService', searchTypeService => {
    searchTypeService.upsertType('a_new_plugin_item', {
        entityName: 'a_new_plugin_item',
        entityService: '',
        placeholderSnippet: 'New Plugin',
        listingRoute: 'New Plugin'
    });

    return searchTypeService;
});
