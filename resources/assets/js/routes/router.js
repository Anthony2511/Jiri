import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routes';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes
});

router.beforeEach((toRoute, fromRoute, next) => {
  window.document.title = toRoute.meta && toRoute.meta.title ? toRoute.meta.title : 'Jiri';

  document.getElementById('header-title').innerHTML = toRoute.meta && toRoute.meta.title ? toRoute.meta.title : '';

  next();
})

export default router;
