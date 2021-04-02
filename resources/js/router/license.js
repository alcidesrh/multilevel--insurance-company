export default {
    path: '/license',
    name: 'license',
    meta: { menu: 'license' },
    component: () => import('../views/License.vue'),
    redirect: { name: 'license_list' },
    children: [
      {
        name: 'license_list',
        path: '',
        component: () => import('../components/license/List.vue'),
        meta: { menu: 'license' },
      },
      {
        name: 'license_profile',
        path: ':id',
        component: () => import('../components/license/Profile.vue'),
        meta: { menu: 'license' },
      },
      {
        name: 'license_new',
        path: 'new',
        component: () => import('../components/license/Profile.vue'),
        meta: { menu: 'license' },
      },
    ]
  }
