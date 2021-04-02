import { getField, updateField } from "vuex-map-fields";
import * as actionsList from "../../actions/action_list";
import ListState from "../../states/state_list";
import ItemState from "../../states/state_item";
import * as actionsItem from "../../actions/action_item";
import axios from "axios";

export default {
    namespaced: true,
    state: {
        ...ItemState,
        ...ListState,
        perPage: 15
    },
    getters: {
        getField
    },
    mutations: {
        updateField
    },
    actions: {
        ...actionsItem,
        ...actionsList,
        renew({ rootState, state }, params) {

            state.loadingItem = true;

            return axios
                .post(rootState.util.entrypoint + 'subscription/renew', params)
                .then(response => {
                    state.loadingItem = false;

                    return response;
                })
                .catch(e => {
                    console.log(e);
                    state.loadingItem = false;
                });
        }
    }
};
