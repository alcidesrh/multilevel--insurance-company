import { getField, updateField } from 'vuex-map-fields';
import * as actionsList from '../../actions/action_list';
import listState from '../../states/state_list'
import ItemState from "../../states/state_item";
import * as actionsItem from "../../actions/action_item";

export default {
  namespaced: true,
  state: {
    ...ItemState,
    ...listState,
    globalLoading: 0
  },
  getters: {
    getField,
  },
  mutations: {
    updateField
  },
  actions:{
    ...actionsItem,
    ...actionsList
  }
};