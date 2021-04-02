import Vue from 'vue'

import Router from 'vue-router'

import EventBus from "./event-bus";

// import company from './router/company'

import user from './router/user'

import recruitment from './router/recruitment'

import category from './router/category'

import subscription from './router/subscription'

import license from './router/license'

import insurance from './router/insurance-form'


Vue.use(Router)

const route = new Router({
  mode: 'history',
  base: '/',
  scrollBehavior(to, from, savedPosition) {
    return { x: 0, y: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'home'
    },
    {
      path: '/renew-subscription',
      name: 'renew_subscription',
      component: () => import('./views/RenewSubscription.vue')
    },
    {
      path: '/invitation',
      name: 'invitation',
      meta: { menu: 'invitation' },
      component: () => import('./views/Invitation.vue')
    },
    {
      path: '/finance',
      name: 'finance',
      meta: { menu: 'finance' },
      component: () => import('./views/Finance.vue')
    },
    {
      path: '/comming-soon',
      name: 'comming-soon',
      component: () => import('./views/CommingSoon.vue')
    },
    {
      path: '/calendar',
      name: 'calendar',
      meta: { menu: 'calendar' },
      component: () => import('./components/util/Calendar.vue')
    },
    // company,
    ...user,
    recruitment ,
    category,
    subscription,
    license,
    insurance
  ]
})
import store from './store';

function redirectAfterLogin(){

  const ability = store.state.user.ability;

  // if(ability.can('list', 'agencies')){
  //   return {name: 'agencies', menu: 'agencies'};
  // }
  if(ability.can('list', 'agency')){
    return {name: 'agency_list', menu: 'agency'};
  }
  else if(ability.can('list', 'elite')){
    return {name: 'elite_list', menu: 'elite'};
  }
  else if(ability.can('list', 'broker')){
    return {name: 'broker_list', menu: 'broker'};
  }
  else{
    return {name: 'recruitment_list', menu: 'recruitment'};
  }
}

route.beforeEach((to, from, next) => {

  if(store.state.user.user && store.state.user.user.userPay){
    if(to.name != 'invitation')
     next({name: 'invitation'});
    else next();
  }
  else if(store.state.user.user && store.state.user.user.renew){
    if(to.name != 'renew_subscription')
     next({name: 'renew_subscription'});
    else next();
  }
  else if(!store.state.user.user && !store.state.user.refreshRoute){
    store.state.user.refreshRoute = to.name == 'login' || to.path == '/' ? '/' : to.path;
    next(false, () => {});
  }
  else if(store.state.user.user && (to.name == 'login' || to.path == '/')){
    const redirect = redirectAfterLogin();
    EventBus.$emit("route-change", {
      menu: redirect.menu
    });
    next({name: redirect.name});
  }
  else if(to.name == 'edit-user'){
      let role = to.params.user.role;
      if(typeof to.params.user.role == 'object')
       role = to.params.user.role.slug;
      EventBus.$emit("route-change", {
        menu: role
      });
      next({name: role + '_edit_profile', params: { id: to.params.user.id }});
  }
  else if(!to.name || to.name == 'invitation'){
    const redirect = redirectAfterLogin();
    EventBus.$emit("route-change", {
      menu: redirect.menu
    });
    
    next({name: redirect.name});
  }
  else{
    EventBus.$emit("route-change", {
      menu: to.meta.menu
    });
    next();
  }
})
export default route;
