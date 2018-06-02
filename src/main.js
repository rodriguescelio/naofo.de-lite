// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './config/router'
import store from './config/store'
import database from './config/database'

global.store = store
global.database = database

Vue.config.productionTip = false

import "normalizecss/normalize.css"

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>',
  created() {
    this.$store.dispatch('loadPageTitle')
  }
})
