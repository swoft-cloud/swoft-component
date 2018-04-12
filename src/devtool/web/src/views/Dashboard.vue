<template>
  <div>
    <v-jumbotron :color="randomColor()" dark>
      <v-container fill-height>
        <v-layout align-center>
          <v-flex>
            <h3 class="display-3">Welcome to the DevTool</h3>
            <span class="subheading">
              This is a simple tool to help you quickly see some information about the application
            </span>
            <v-divider class="my-3"></v-divider>
            <div class="title mb-3">Check out our newest features!</div>
            <v-btn large color="primary" class="mx-0" :href="github" target="_blank">Github</v-btn>
            <v-btn large color="success" class="mx-0" :href="document" target="_blank">Document</v-btn>
          </v-flex>
        </v-layout>
      </v-container>
    </v-jumbotron>

    <v-layout row wrap>
      <v-flex d-flex xs12 tag="h2" class="headline">
        Application
      </v-flex>
      <v-flex d-flex xs12 sm6 md3>
        <v-card :color="randomColor()" dark>
          <v-card-title primary class="title">Environment</v-card-title>
          <table class="table transparent">
            <tbody>
            <tr v-for="(val, name) in env" :key="name">
              <td>{{ name }}</td>
              <td><code>{{ val }}</code></td>
            </tr>
            </tbody>
          </table>
        </v-card>
      </v-flex>
      <v-flex d-flex xs12 sm6 md3>
        <v-layout row wrap>
          <v-flex d-flex v-for="item in app1" :key="item.href">
            <v-card :color="randomColor()" dark>
              <v-card-title primary class="title">{{ item.title }}</v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat :to="item.href">View</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex d-flex xs12 sm6 md3 child-flex>
        <v-layout row wrap>
          <v-flex d-flex v-for="item in app2" :key="item.href">
            <v-card :color="randomColor()" dark>
              <v-card-title primary class="title">{{ item.title }}</v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat :to="item.href">View</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex d-flex xs12 sm6 md3>
        <v-layout row wrap>
          <v-flex d-flex v-for="item in app3" :key="item.href">
            <v-card :color="randomColor()" dark>
              <v-card-title primary class="title">{{ item.title }}</v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat :to="item.href">View</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-flex>
    </v-layout>

    <v-layout row wrap>
      <v-flex d-flex xs12 tag="h2" class="headline">Server</v-flex>
      <v-flex d-flex xs6 sm4 md3 xl2 v-for="item in server" :key="item.href">
        <v-card :color="randomColor()" dark>
          <v-card-title primary-title class="title">{{ item.title }}</v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat :to="item.href">View</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>

    <v-layout row wrap>
      <v-flex d-flex xs12 tag="h2" class="headline">Tools</v-flex>
      <v-flex d-flex xs6 sm4 md3 xl2 v-for="item in tools" :key="item.href">
        <v-card :color="randomColor()" dark>
          <v-card-title primary-title class="title">{{ item.title }}</v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat :to="item.href">View</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>

  </div>
</template>

<script>
  import {VJumbotron, VDataTable} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'
  import {URI_PREFIX} from '../libs/constants'
  import {getBasicEnv} from '../libs/api-services'

  export default {
    name: 'dashboard',
    components: {VJumbotron, VDataTable, ...VCard},
    data: () => ({
      env: {},
      basePath: '',
      uriPrefix: URI_PREFIX,
      github: 'https://github.com/swoft-cloud/swoft',
      document: 'https://doc.swoft.org',
      app1: [{
        title: 'Information',
        href: URI_PREFIX + '/app/info'
      }, {
        title: 'Configuration',
        href: URI_PREFIX + '/app/config'
      }, {
        title: 'Registered Events',
        href: URI_PREFIX + '/app/events'
      }],
      app2: [{
        title: 'HTTP Routes',
        href: URI_PREFIX + '/http/routes'
      }, {
        title: 'RPC Routes',
        href: URI_PREFIX + '/rpc/routes'
      }, {
        title: 'WebSocket Routes',
        href: URI_PREFIX + '/ws/routes'
      }],
      app3: [{
        title: 'Registered Beans',
        href: URI_PREFIX + '/app/beans'
      }, {
        title: 'AOP Handlers',
        href: URI_PREFIX + '/aop/handlers'
      }, {
        title: 'HTTP Middleware',
        href: URI_PREFIX + '/http/middles'
      }],
      server: [{
        title: 'Information',
        href: URI_PREFIX + '/server/info'
      }, {
        title: 'Configuration',
        href: URI_PREFIX + '/server/config'
      }, {
        title: 'Registered Events',
        href: URI_PREFIX + '/server/events'
      }, {
        title: 'Swoole Log',
        href: URI_PREFIX + '/swoole/logs'
      }],
      tools: [{
        title: 'Code Generator',
        href: URI_PREFIX + '/code/gen'
      }, {
        title: 'WebSocket Test',
        href: URI_PREFIX + '/ws/test'
      }, {
        title: 'Run Tracing',
        href: URI_PREFIX + '/run/trace'
      }, {
        title: 'Application Log',
        href: URI_PREFIX + '/app/logs'
      }],
      colors: [
        'amber darken-2',
        'blue',
        'blue lighten-2',
        'blue darken-3',
        'blue accent-4',
        'brown',
        'brown darken-2',
        'cyan',
        'cyan darken-1',
        'cyan darken-3',
        'indigo',
        'indigo darken-2',
        'lime darken-2',
        'orange',
        'orange darken-2',
        'deep-orange darken-2',
        'deep-purple',
        'deep-purple darken-2',
        'purple',
        'purple darken-2',
        'yellow darken-3',
        'teal',
        'teal darken-3',
        'blue-grey',
        'blue-grey darken-2',
        'green',
        'green darken-2',
        'red darken-2',
        'pink darken-1',
        'light-blue',
        'light-blue darken-4',
        'light-green',
        'light-green darken-3'
      ],
      lorem: `Lorem ipsum dolor sit amet, mel at clita quando. Te sit oratio vituperatoribus, nam ad ipsum posidonium mediocritatem, explicari dissentiunt cu mea. Repudiare disputationi vim in, mollis iriure nec cu, alienum argumentum ius ad. Pri eu justo aeque torquatos.`
    }),
    created() {
      this.fetchList()
    },
    computed: {
    },
    methods: {
      randomColor () {
        let index = Math.floor(Math.random() * this.colors.length)

        return this.colors[index]
      },
      buildRoute (route) {
        return this.uriPrefix + route
      },
      fetchList() {
        getBasicEnv().then(({data}) => {
          this.basePath = data.basePath

          // remove key
          delete data.basePath

          this.env = data

          console.log(data)
        })
      }
    }
  }
</script>

<style scoped>

</style>
