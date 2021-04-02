const state = {
    loading: false,
    entrypoint: location.protocol.concat("//", window.location.hostname + '/api/'),
    host: location.protocol.concat("//", window.location.hostname)
};

function loading(loading) {
    return {type: 'loading', loading};
}

const getters = {
    loading: state => state.loading,
    entrypoint: state => state.entrypoint,  
    host: state => state.host
};

const actions = {
    setLoading({commit}, increment = true) {
        if (increment == 0)
            commit(loading(0))
        else if (increment)
            commit(loading(state.loading + 1));
        else if (state.loading)
            commit(loading(state.loading - 1));
    },
};

const mutations = {
    loading(state, payload) {
        state.loading = payload.loading;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
