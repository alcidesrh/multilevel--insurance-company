export default [
    {
        path: "/login",
        name: "login",
        component: () => import("../views/Login.vue")
    },
    {
        path: "/edit-user",
        name: "edit-user"
        // component: () => import("../views/Login.vue")
    },
    {
        path: "/agency",
        name: "agency",
        meta: { menu: "agency" },
        component: () => import("../views/User.vue"),
        redirect: { name: "agency_list" },
        children: [
            {
                name: "agency_list",
                path: "",
                component: () => import("../components/user/ListAgency.vue"),
                meta: { menu: "agency" }
            },
            {
                name: "agency_profile",
                path: ":id",
                component: () => import("../components/user/Profile.vue"),
                meta: { menu: "agency" }
            },
            {
                name: "agency_edit_profile",
                path: "edit/:id",
                component: () =>
                    import("../components/user/EditAgencyProfile.vue"),
                meta: { menu: "agency" }
            }
        ]
    },
    {
        path: "/elite",
        name: "elite",
        meta: { menu: "elite" },
        component: () => import("../views/User.vue"),
        redirect: { name: "elite_list" },
        children: [
            {
                name: "elite_list",
                path: "",
                component: () => import("../components/user/ListElite.vue"),
                meta: { menu: "elite" }
            },
            {
                name: "elite_profile",
                path: ":id",
                component: () => import("../components/user/Profile.vue"),
                meta: { menu: "elite" }
            },
            {
                name: "elite_edit_profile",
                path: "edit/:id",
                component: () =>
                    import("../components/user/EditEliteProfile.vue"),
                meta: { menu: "elite" }
            }
        ]
    },
    {
        path: "/broker",
        name: "broker",
        meta: { menu: "broker" },
        component: () => import("../views/User.vue"),
        redirect: { name: "broker_list" },
        children: [
            {
                name: "broker_list",
                path: "",
                component: () => import("../components/user/ListBroker.vue"),
                meta: { menu: "broker" }
            },
            {
                name: "broker_profile",
                path: ":id",
                component: () => import("../components/user/Profile.vue"),
                meta: { menu: "broker" }
            },
            {
                name: "broker_edit_profile",
                path: "edit/:id",
                component: () =>
                    import("../components/user/EditBrokerProfile.vue"),
                meta: { menu: "broker" }
            }
        ]
    },
    {
        path: "/staff",
        name: "staff",
        meta: { menu: "staff" },
        component: () => import("../views/User.vue"),
        redirect: { name: "staff_list" },
        children: [
            {
                name: "staff_list",
                path: "",
                component: () => import("../components/user/ListStaff.vue"),
                meta: { menu: "staff" }
            },
            {
                name: "staff_profile",
                path: ":id",
                component: () => import("../components/user/Profile.vue"),
                meta: { menu: "staff" }
            },
            {
                name: "staff_edit_profile",
                path: "edit/:id",
                component: () =>
                    import("../components/user/EditStaffProfile.vue"),
                meta: { menu: "staff" }
            }
        ]
    }
];
