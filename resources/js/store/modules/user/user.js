import axios from "axios";
import { getField, updateField } from "vuex-map-fields";
import ItemState from "../../states/state_item";
import * as actionsItem from "../../actions/action_item";
import { AbilityBuilder, Ability } from "@casl/ability";


function getAbilityRule(user) {
    const { can, cannot, rules } = new AbilityBuilder(Ability);
    
    switch (user.role) {
        case "admin": {
            can("manage", "all");
            cannot("edit", 'profile');
            cannot("list", 'staff');
            cannot("pay", 'subscription');
            cannot("promote", 'Object', (u) => u.role == 2);
            cannot("descender", 'Object', (u) => u.role == 4);
            break;
        }
        case "staff": {
            can("list", ["broker", "recruitment", "insurance"]);
            can("pay", 'subscription');
            can("edit", 'profile');         
            break;
        }
        case "agency": {
            can("list", 'agency');
            can("edit", 'Object', {root_id: user.id});
            can("promote", 'Object', (u) => u.role == 4 || u.role == 3);
            can("descender", 'Object', (u) => u.role == 2 || u.role == 3);
            can('change', 'family')
        }
        case "elite": {
            can("list", ["elite", "staff"]);
            can("promote", 'Object', (u) => u.role == 4);
            can("descender", 'Object', (u) => u.role == 3);

        }
        default: {
            can("list", ["broker", "recruitment", "computer", "insurance"]);
            can("create", "user");
            can("edit", 'Object', {parent_id: user.id});
            can("edit", 'profile');
            can("pay", 'subscription');
            can('send', 'invitation');

        }
    }
    
    return rules;
}
export default {
    namespaced: true,
    state: {
        ...ItemState,
        user: null,
        error: null,
        ability: new Ability(),
        usersSelect: []
    },
    getters: {
        getField
    },
    mutations: {
        updateField
    },
    actions: {
        ...actionsItem,

        authenticate({ rootState, state }, param) {
            const endpoint = rootState.util.entrypoint + "authenticate";
            return axios
                .post(endpoint, param)
                .then(response => {
                    state.user = response.data.data;
                    state.ability.update(getAbilityRule(state.user));
                    return true;
                })
                .catch(e => {
                    state.error = e.response;
                    return e.response;
                });
        },

        logout({ rootState, state }, param) {
            const endpoint = rootState.util.entrypoint + "logout";
            return axios
                .post(endpoint, param)
                .then(response => {})
                .catch(e => {
                    return e.response;
                });
        },

        getUser({ rootState, state }, id = null) {
            const endpoint = rootState.util.entrypoint + "user";
            return axios
                .get(endpoint)
                .then(response => {
                    state.user = response.data.data;
                    state.ability.update(getAbilityRule(state.user));
                })
                .catch(e => {
                    return e.response;
                });
        },

        resetPassword({ rootState, state }, param) {
            const endpoint = rootState.util.entrypoint + "user-reset-password";
            return axios
                .post(endpoint, param)
                .then(response => {
                    return response;
                });
        },

        reset({ state }) {
            state.user = null;
            state.error = null;
            state.ability = new Ability();
            state.usersSelect = [];
        },
    }
};
