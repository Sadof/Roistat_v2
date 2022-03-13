import Vue from 'vue'
import App from './App.vue'

import router from './router.js';

// window.axios = require('axios');

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// window.axios.defaults.headers.common = {
//   "Content-Type": "application/json"
// }

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
  router,
}).$mount('#app')
