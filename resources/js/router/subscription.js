export default {
    path: '/subscription',
    name: 'subscription',
    meta: { menu: 'subscription' },
    component: () => import('../views/Subscription.vue'),
    redirect: { name: 'subscription_list' },
    children: [
      {
        name: 'subscription_list',
        path: '',
        component: () => import('../components/subscription/List.vue'),
        meta: { menu: 'subscription' },
      },
      {
        name: 'subscription_profile',
        path: ':id',
        component: () => import('../components/subscription/Profile.vue'),
        meta: { menu: 'subscription' },
      },
      {
        name: 'subscription_new',
        path: 'new',
        component: () => import('../components/subscription/Profile.vue'),
        meta: { menu: 'subscription' },
      },
    ]
  }
