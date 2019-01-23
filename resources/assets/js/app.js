require('./bootstrap');
require('./mixins/forms/bootstrap');

window.Vue = require('vue');

import ElementUI from 'element-ui';
import locale from "element-ui/lib/locale/lang/fr";
import 'element-ui/lib/theme-chalk/index.css';
import router from './routes/router';
import jiriVariables from './mixins/jiriVariables';

Vue.use(ElementUI, { locale });


Vue.mixin(jiriVariables);

// Define document title
router.beforeEach((to, from, next) => {
    document.title = to.meta.title + ' - Jiri'
    next()
})

export const bus = new Vue();

const app = new Vue({
    router,
    el: '#app'
});
