import template from './bpa-download-center-album-tree-item.html.twig';

const { Component } = Shopware;

Component.extend('bpa-download-center-album-tree-item', 'sw-tree-item', {
    template,

    methods: {
        updatedComponent() {
            this.isLoading = false;
        }
    }
});
