<template>
  <v-layout row wrap>
    <v-flex xs12><v-subheader><h1>{{ this.$route.name }}</h1></v-subheader></v-flex>
    <v-flex d-flex xs12 md4>
      <v-card>
        <v-card-title class="title grey lighten-3">Some Tips</v-card-title>
        <v-card-text>
          <p>get real path: <code>\Swoft::getAlias('@root/public')</code></p>
        </v-card-text>
      </v-card>
    </v-flex>
    <v-flex d-flex xs12 md8>
      <v-card>
        <v-card-title class="title blue lighten-5">Path Aliases</v-card-title>
        <table class="table">
          <thead>
            <tr>
              <th>Alias</th>
              <th>Value</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(val, name) in aliases" :key="name">
              <td>{{ name }}</td>
              <td><code>{{ val }}</code></td>
            </tr>
          </tbody>
        </table>
      </v-card>
    </v-flex>

  </v-layout>
</template>

<script>
  import {VDataTable} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'
  import {getAppAliases} from '../../libs/api-services'

  export default {
    name: 'app-info',
    components: {VDataTable, ...VCard},
    data() {
      return {
        aliases: {}
      }
    },
    created() {
      this.fetchList()
    },
    mounted() {
    },
    computed: {},
    methods: {
      fetchList() {
        getAppAliases().then(({data}) => {
          this.aliases = data

          console.log(data)
        })
      }
    }
  }
</script>

<style scoped>

</style>
