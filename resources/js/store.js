import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import util from './store/util'

import user from './store/modules/user/user'

import broker from './store/modules/user/broker'

import elite from './store/modules/user/elite'

import agency from './store/modules/user/agency'

import staff from './store/modules/user/staff'

import representative from './store/modules/user/representative'

// import company from './store/modules/company'

import role from './store/modules/role'

import recruitment from './store/modules/recruitment'

import category from './store/modules/category'

import subscription from './store/modules/subscription'

import generic from './store/modules/generic'

import license from './store/modules/license'

import finance from './store/modules/finance'

import insurance from './store/modules/insurance'

export default new Vuex.Store({
  modules: {
    util,
    user,
    broker, 
    // company,
    elite,
    agency,
    staff,
    representative,
    recruitment,
    role,
    category,
    subscription,
    generic,
    license,
    finance,
    insurance
  }
})
