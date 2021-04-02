export default {
  path: '/agencies',
  name: 'agencies',
  meta: { menu: 'agencies' },
  component: () => import('../views/Company.vue'),
  redirect: { name: 'list' },
  children: [
    {
      name: 'list',
      path: '',
      component: () => import('../components/company/List'),
      meta: { menu: 'agencies' },
    },
    {
      name: 'profile',
      path: ':id',
      component: () => import('../components/company/Profile.vue'),
      meta: { menu: 'agencies' },
    },
    {
      name: 'new',
      path: 'new',
      component: () => import('../components/company/Profile.vue'),
      meta: { menu: 'agencies' },
    },
    ,
    {
      name: 'show',
      path: 'show/:id',
      component: () => import('../components/company/Show.vue'),
      meta: { menu: 'agencies' },
    }
  ]
};
