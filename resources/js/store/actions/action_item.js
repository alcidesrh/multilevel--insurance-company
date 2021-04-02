import axios from "axios";
import { objectToGetParameters } from "../../util";

export const getItem = ({ rootState, state }, params) => {

    state.loadingItem = true;

    let endpoint, query;

    ({ endpoint, query } = params);

    let uri = '';
    if(endpoint){

    uri = rootState.util.entrypoint + endpoint;
    if (query) uri = uri + "?" + objectToGetParameters(query);
    }
    else uri = rootState.util.entrypoint + params;

    return axios
        .get(uri)
        .then(response => {

            state.loadingItem = false;

            state.item = response.data.data;
        })
        .catch(e => {
            console.log(e);
            state.loadingItem = false;
        });
};

export const getItemGeneric = ({ rootState }, params) => {

    let endpoint, query;

    ({ endpoint, query } = params);

    let uri = '';
    if(endpoint){

    uri = rootState.util.entrypoint + endpoint;
    if (query) uri = uri + "?" + objectToGetParameters(query);
    }
    else uri = rootState.util.entrypoint + params;

    return axios
        .get(uri)
        .then(response => {

            return response.data.data;
        })
        .catch(e => {
            console.log(e);
        });
};

export const getItemGenericTable = ({ rootState }, params) => {

    return axios
        .post(rootState.util.entrypoint + 'generic/item', params)
        .then(response => {
            return response.data;
        })
        .catch(e => {
            console.log(e);
        });
};

export const save = ({ rootState, state }, params) => {

    state.loadingItem = true;

    let endpoint, data;

    if(typeof params == 'string'){
         endpoint = params;
         data = state.item;
    }
    else ({endpoint, data} = params);        
    
    return axios
        .post(rootState.util.entrypoint + endpoint, data)
        .then(response => {

            state.loadingItem = false;

            return response;

        })
        .catch(e => {
            console.log(e);
            state.loadingItem = false;
        });
};

export const remove = ({ rootState, state }, endpoint) => {

    state.loadingItem = true;

    return axios
        .delete(rootState.util.entrypoint + endpoint)
        .then(response => {

            state.loadingItem = false;

            return response;

        })
        .catch(e => {
            console.log(e);
            state.loadingItem = false;
        });
};

// export const getSelectItems = (
//   { commit },
//   { page = 'provinces', params = { properties: ['@id', 'name'] } } = {},
// ) => {
//   commit(types.TOGGLE_LOADING);

//   fetch(page, { params })
//     .then(response => response.json())
//     .then(data => {
//       commit(types.TOGGLE_LOADING);
//       commit(types.SET_SELECT_ITEMS, data['hydra:member']);
//     })
//     .catch(e => {
//       commit(types.TOGGLE_LOADING);
//       commit(types.SET_ERROR, e.message);
//     });
// };
