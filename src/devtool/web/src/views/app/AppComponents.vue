<template>
  <div>
    <v-subheader><h1>{{ this.$route.name }}</h1></v-subheader>
    <v-card>
      <v-card-title class="pt-1">
        <v-spacer></v-spacer>
        <v-text-field
          append-icon="search"
          label="Search"
          single-line
          hide-details
          v-model="search"
        ></v-text-field>
      </v-card-title>
      <v-divider></v-divider>
      <v-data-table
        :headers="headers"
        :items="dataList"
        :search="search"
        :rows-per-page-items="pageOpts"
        item-key="name"
        class="elevation-1"
      >
        <template slot="items" slot-scope="props">
          <tr @click="props.expanded = !props.expanded">
            <td>
              <a :href="props.item.source.url" title="To github repo" target="_blank">{{ props.item.name }}</a>
            </td>
            <td><v-chip outline small color="green">{{ props.item.version }}</v-chip></td>
            <td><code>{{ props.item.source.reference.slice(0, 7) }}</code></td>
            <td>{{ props.item.keywords.join(', ') }}</td>
            <td>{{ props.item.time.slice(0, 19) }}</td>
          </tr>
        </template>
        <template slot="expand" slot-scope="props">
          <v-card flat color="grey lighten-4">
            <v-card-text class="pl-4">{{ props.item.description }}</v-card-text>
          </v-card>
        </template>
        <template slot="no-data">
          <v-alert :value="true" color="info" icon="info">
            Sorry, nothing to display here :(
          </v-alert>
        </template>
        <v-alert slot="no-results" :value="true" color="error" icon="warning">
          Your search for "{{ search }}" found no results.
        </v-alert>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
  import {VChip, VAlert, VDataTable} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'
  import {getComponents} from '../../libs/api-services'

  export default {
    name: 'app-components',
    components: {VChip, VAlert, ...VCard, VDataTable},
    data() {
      return {
        search: '',

        // table settings
        pageOpts: [10, 25, {'text': 'All', 'value': -1}],

        // table headers
        headers: [{
          text: 'Name',
          value: 'name'
        }, {
          text: 'Version',
          value: 'version'
        }, {
          text: 'Commit ID',
          sortable: false,
          value: 'commit'
        }, {
          text: 'Keywords',
          sortable: false,
          value: 'keywords'
        }, {
          text: 'Publish Time',
          value: 'time'
        }],

        // data list
        dataList: []
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
        getComponents().then(({data}) => {
          this.dataList = data
        })
      }
    }
  }
</script>

<style scoped>

</style>
