<template>
  <div>
    <v-subheader><h1>{{ this.$route.name }}</h1></v-subheader>
    <simple-table class="table-bordered">
      <template slot="header">
        <th> Number </th>
        <th> Middleware Class</th>
      </template>
      <tr v-for="(val, index) in middles" :key="index">
        <td>{{ index }}</td>
        <td><code>{{ val }}</code></td>
      </tr>
    </simple-table>
  </div>
</template>

<script>
  import SimpleTable from '../parts/SimpleTable'
  import {getHttpMiddles} from '../../libs/api-services'

  export default {
    name: 'http-middleware',
    components: {SimpleTable},
    data: () => ({
      middles: []
    }),
    created() {
      this.fetchList()
    },
    mounted() {
    },
    computed: {},
    methods: {
      fetchList() {
        getHttpMiddles().then(({data}) => {
          console.log(data)

          this.middles = data
        })
      }
    }
  }
</script>

<style scoped>

</style>
