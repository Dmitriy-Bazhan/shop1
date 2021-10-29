import template from './bpa-download-center-file-edit-form.html.twig';

const { Component, Mixin } = Shopware;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('bpa-download-center-file-edit-form', {
    template,

    mixins: [
        Mixin.getByName('placeholder')
    ],


    data() {
        return {
            isLoading: false
        };
    },

    computed: {
        file() {
            return Shopware.State.get('bpaDownloadCenterAlbum').file;
        },

        album() {
            return Shopware.State.get('bpaDownloadCenterAlbum').album;
        },

        ...mapPropertyErrors('file', [
            'name'
        ])
    }

});
