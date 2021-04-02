export default {
    path: '/recruitment',
    name: 'recruitment',
    meta: { menu: 'recruitment' },
    component: () => import('../views/Recruitment.vue'),
    redirect: { name: 'recruitment_list' },
    children: [
      {
        name: 'recruitment_list',
        path: '',
        component: () => import('../components/recruitment/List.vue'),
        meta: { menu: 'recruitment' },
      },
      {
        name: 'recruitment_profile',
        path: ':id',
        component: () => import('../components/recruitment/Profile.vue'),
        meta: { menu: 'recruitment' },
      }
    ]
  }
