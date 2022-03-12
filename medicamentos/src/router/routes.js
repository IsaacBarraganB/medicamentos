const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
    { path: "", component: () => import("pages/Home.vue") },
    { path: "/medicine", component: () => import("pages/medicine/Index.vue") },
    { path: "/medicine/new", component: () => import("pages/medicine/New.vue") },
    { path: "/medicine/:id", component: () => import("pages/medicine/Edit.vue") }
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/Error404.vue"),
  },
];

export default routes;
