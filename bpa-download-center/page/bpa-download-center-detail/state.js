const { Criteria } = Shopware.Data;

export default {
    namespaced: true,

    state() {
        return {
            album: null,
            files: null,
            file: null,
        };
    },

    mutations: {
        setActiveAlbum(state, { album }) {
            state.album = album;
        },

        setActiveFile(state, { file }) {
            state.file = file;
        },

        setAlbumFiles(state, { files }) {
            state.files = files;
        }
    },

    actions: {
        setActiveAlbum({ commit }, payload) {
            commit('setActiveAlbum', payload);
        },

        setActiveFile({ commit }, payload) {
            commit('setActiveFile', payload);
        },

        loadActiveAlbum({ commit }, { repository, id, apiContext }) {
            const criteria = new Criteria();
            return repository.get(id, apiContext, criteria).then((album) => {
                commit('setActiveAlbum', { album });
            });
        },

        loadFilesAlbum({ commit }, { repository, id, apiContext }) {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('albumId', id))

            return repository.search(criteria, apiContext).then((files) => {
                commit('setAlbumFiles', { files });
            });
        }
    }
};
