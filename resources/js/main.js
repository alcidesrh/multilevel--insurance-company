import Vue from "vue";

import router from "./router";

import store from "./store";

import vuetify from "./vuetify";

import App from "./App.vue";

import moment from "moment";

import numeral from "numeral";

import numFormat from "vue-filter-number-format";

import { abilitiesPlugin } from "@casl/vue";

Vue.use(abilitiesPlugin, store.state.user.ability);

Vue.filter("numFormat", numFormat(numeral));

Vue.filter("dateFormat", function(value) {
    return moment(value).format("MM/DD/YYYY");
});

Vue.filter("money", function(value) {
    return new Intl.NumberFormat([], {
        style: "currency",
        currency: "USD"
    }).format(value);
});

Vue.mixin({
    methods: {
        is(user, role) {
            switch (role) {
                case "admin": {
                    return user.role == 1 || user.role == 'admin';
                }
                case "agency": {
                    return user.role == 2 || user.role == 'agency';
                }
                case "elite": {
                    return user.role == 3 || user.role == 'elite';
                }
                case "broker": {
                    return user.role == 4 || user.role == 'broker';
                }
                case "staff": {
                    return user.role == 5 || user.role == 'staff';
                }
                default: {
                    return false;
                }
            }
        }
    }
});

import axios from "axios";

axios.defaults.headers.common = {
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": window.csrfToken
};

// Add a request interceptor
axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    store.state.generic.globalLoading++;
    return config;
  }, function (error) {
    // Do something with request error
    if(store.state.generic.globalLoading > 0)
     store.state.generic.globalLoading--;
    return Promise.reject(error);
  });

// Add a response interceptor
axios.interceptors.response.use(function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    store.state.generic.globalLoading--;
    return response;
  }, function (error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    if(store.state.generic.globalLoading > 0)
     store.state.generic.globalLoading--;
    return Promise.reject(error);
  });

new Vue({
    el: "#app",
    vuetify,
    router,
    store,
    render: h => h(App)
});
