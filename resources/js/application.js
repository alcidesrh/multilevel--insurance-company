import Vue from 'vue'

import vuetify from './vuetify'

import Application from './Application.vue'

import axios from 'axios';  
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': window.csrfToken
};

new Vue({
  el: '#app',
  vuetify,
  render: h => h(Application)
})

