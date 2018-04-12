import { URI_PREFIX } from '../libs/constants'

export default [{
  path: URI_PREFIX,
  name: 'Dashboard',
  component: () => import('@/views/Dashboard.vue')
}, {
  path: URI_PREFIX + '/http/routes',
  name: 'HTTP Routes',
  component: () => import('../views/http/HttpRoutes.vue')
}, {
  path: URI_PREFIX + '/http/middles',
  name: 'HTTP Middleware',
  component: () => import('../views/http/HttpMiddleware.vue')
}, {
  path: URI_PREFIX + '/app/info',
  name: 'Application Info',
  component: () => import('../views/app/AppInfo.vue')
}, {
  path: URI_PREFIX + '/app/config',
  name: 'Application Config',
  component: () => import('../views/app/AppConfig.vue')
}, {
  path: URI_PREFIX + '/app/events',
  name: 'Application Events',
  component: () => import('../views/app/AppEvents.vue')
}, {
  path: URI_PREFIX + '/app/beans',
  name: 'Application Beans',
  component: () => import('../views/app/AppBeans.vue')
}, {
  path: URI_PREFIX + '/app/components',
  name: 'App Components',
  component: () => import('../views/app/AppComponents.vue')
}, {
  path: URI_PREFIX + '/aop/handlers',
  name: 'AOP Handlers',
  component: () => import('../views/app/AopHandlers.vue')
}, {
  path: URI_PREFIX + '/connection/pools',
  name: 'Connection Pools',
  component: () => import('../views/app/ConnectionPools.vue')
}, {
  path: URI_PREFIX + '/server/info',
  name: 'Server Info',
  component: () => import('../views/server/ServerInfo.vue')
}, {
  path: URI_PREFIX + '/server/stats',
  name: 'Server Stats',
  component: () => import('../views/server/ServerStats.vue')
}, {
  path: URI_PREFIX + '/server/config',
  name: 'Server Config',
  component: () => import('../views/server/ServerConfig.vue')
}, {
  path: URI_PREFIX + '/server/events',
  name: 'Server Events',
  component: () => import('../views/server/ServerEvents.vue')
}, {
  path: URI_PREFIX + '/swoole/logs',
  name: 'Swoole Logs',
  component: () => import('../views/server/SwooleLog.vue')
}, {
  path: URI_PREFIX + '/ws/routes',
  name: 'WebSocket Routes',
  component: () => import('../views/ws/WsRoutes.vue')
}, {
  path: URI_PREFIX + '/ws/test',
  name: 'WebSocket Test',
  component: () => import('../views/ws/WsTest.vue')
}, {
  path: URI_PREFIX + '/rpc/routes',
  name: 'RPC Routes',
  component: () => import('../views/rpc/RpcRoutes.vue')
}, {
  path: URI_PREFIX + '/rpc/middles',
  name: 'RPC Middleware',
  component: () => import('../views/rpc/RpcMiddleware.vue')
}, {
  path: URI_PREFIX + '/code/gen',
  name: 'Class Generator',
  component: () => import('../views/gen/ClassGen.vue')
}, {
  path: URI_PREFIX + '/run/trace',
  name: 'Run Tracing',
  component: () => import('../views/app/RunTrace.vue')
}, {
  path: URI_PREFIX + '/app/logs',
  name: 'Application Logs',
  component: () => import('../views/app/AppLog.vue')
}, {
  path: URI_PREFIX + '/about',
  name: 'About',
  component: () => import('../views/pages/About.vue')
}, {
  path: '*',
  redirect: URI_PREFIX
}]
