// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import {
  Vuetify,
  VApp,
  VDivider,
  VNavigationDrawer,
  VFooter,
  VList,
  VBtn,
  VIcon,
  VGrid,
  VToolbar,
  VTextField,
  VSubheader,
  transitions
} from 'vuetify'
import '../node_modules/vuetify/src/stylus/app.styl'

// json view
import TreeView from 'vue-json-tree-view'

// Helpers
// import colors from 'vuetify/es5/util/colors'

// console.log(colors.teal)
Vue.use(TreeView)
Vue.use(Vuetify, {
  components: {
    VApp,
    VDivider,
    VNavigationDrawer,
    VFooter,
    VList,
    VBtn,
    VIcon,
    VGrid,
    VToolbar,
    VTextField,
    VSubheader,
    transitions
  }
})

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
