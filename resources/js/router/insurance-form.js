export default {
  path: '/services',
  name: 'services',
  meta: { menu: 'insurance' },
  component: () => import('../views/Insurance.vue'),
  redirect: { name: 'insurance_obama_new' },
  children: [
    {
      name: 'insurance_obama_new',
      path: '/services/obama-care/new',
      component: () => import('../components/insurance/form/ObamaCare'),
      meta: { menu: 'insurance_obama_new', type: 'new' },
    },
    {
      name: 'insurance_obama_renew',
      path: '/services/obama-care/renew',
      component: () => import('../components/insurance/form/ObamaCare'),
      meta: { menu: 'insurance_obama_renew', type: 'renew' }
    },
    {
      name: 'insurance_obama_life_change',
      path: '/services/obama-care/life-change',
      component: () => import('../components/insurance/form/ObamaCare'),
      meta: { menu: 'insurance_obama_life_change', type: 'life-change' }
    },
    {
      name: 'insurance_car',
      path: '/services/car',
      component: () => import('../components/insurance/form/Car'),
      meta: { menu: 'insurance_car' }
    },
    {
      name: 'insurance_home',
      path: '/services/home',
      component: () => import('../components/insurance/form/Home'),
      meta: { menu: 'insurance_home' }
    },
    {
      name: 'insurance_business',
      path: '/services/business',
      component: () => import('../components/insurance/form/Business'),
      meta: { menu: 'insurance_business' }
    },
    {
      name: 'insurance_live',
      path: '/services/live',
      component: () => import('../components/insurance/form/Live'),
      meta: { menu: 'insurance_live' }
    }
  ]
};
