import { getField, updateField } from 'vuex-map-fields';
import ListState from '../../states/state_list'
import * as actionsList from '../../actions/action_list';

export default {
  namespaced: true,
  state: {
    ...ListState,
    filters: {},
    perPage: 9,
  },
  getters: {
    getField,
  },
  mutations: {
    updateField
  },
  actions:{
    ...actionsList,
  }
};