import Vue from 'vue'
import Router from 'vue-router'
import Routes from './routes'
import Util from '../libs/util'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  linkActiveClass: 'active',
  scrollBehavior: () => ({ y: 0 }),
  routes: Routes
})

router.beforeEach((to, from, next) => {
  // iView.LoadingBar.start()
  Util.title(to.meta.title ? to.meta.title : to.name)

  console.log('before route each')

  next()
})

router.afterEach((to, from, next) => {
  // iView.LoadingBar.finish()
  // window.scrollTo(0, 0)
  console.log('after route each')
})

export default router
