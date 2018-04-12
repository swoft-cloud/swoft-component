<template>
  <div>
    <v-subheader><h1>{{ this.$route.name }}</h1></v-subheader>
    <v-layout row wrap>
      <v-flex d-flex xs12 md4>
        <v-card>
          <v-card-title primary-title class="blue lighten-5">
            <div>
              <div class="headline">Class Setting</div>
              <span class="grey--text">There are some setting for class generate!</span>
            </div>
          </v-card-title>
          <v-container>
            <v-form v-model="valid" ref="form" lazy-validation>
              <v-select
                label="Class Type"
                v-model="select"
                :items="items"
                :rules="[v => !!v || 'Class type is required']"
                required
              ></v-select>
              <v-text-field
                label="Class Name"
                v-model="name"
                :rules="nameRules"
                :counter="10"
                hint="The class name, don't need suffix and ext(eg. demo)"
                persistent-hint
                required
              ></v-text-field>
              <v-text-field
                label="Save Directory"
                v-model="dir"
                :rules="dirRules"
                hint="The class file save directory(default: @app/Controllers)"
                persistent-hint
                required
              ></v-text-field>
              <v-text-field
                label="Class Suffix"
                v-model="suffix"
                :rules="[v => /^[a-zA-Z]+$/.test(v) || 'Suffix only allow alpha']"
                hint="The class name suffix. default is: Controller"
                persistent-hint
              ></v-text-field>
              <v-text-field
                label="Template Directory"
                v-model="tplDir"
                :rules="[v => /^[a-zA-Z]+$/.test(v) || 'Suffix only allow alpha']"
                hint="The template file dir path.(default: @devtool/res/templates)"
                persistent-hint
              ></v-text-field>
              <v-text-field
                label="Template Filename"
                v-model="tplFile"
                :rules="[v => /^[a-zA-Z]+$/.test(v) || 'Suffix only allow alpha']"
                hint="The template file name.(default: controller.stub)"
                persistent-hint
              ></v-text-field>
              <v-checkbox
                label="Force override exists file?"
                v-model="override"
              ></v-checkbox>

              <v-btn @click="submit" :disabled="!valid" color="success">
                submit
              </v-btn>
              <v-btn @click="clear">clear</v-btn>
            </v-form>
          </v-container>
        </v-card>

      </v-flex>
      <v-flex d-flex xs12 md8>
        <v-card>
          <v-card-title primary-title class="blue-grey lighten-5">
            <div>
              <div class="headline">Class Preview</div>
              <span class="grey--text">1,000 miles of wonder</span>
            </div>
          </v-card-title>
          <v-card-actions>
            <v-btn flat>Share</v-btn>
            <v-btn flat color="purple">Explore</v-btn>
            <v-spacer></v-spacer>
            <v-btn flat @click.native="show = !show">
              View <v-icon>{{ show ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
            </v-btn>
          </v-card-actions>
          <v-slide-y-transition>
            <v-card-text v-show="show">
              escape.
            </v-card-text>
          </v-slide-y-transition>
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import axios from 'axios'
  import * as VCard from 'vuetify/es5/components/VCard'
  import {VForm, VCheckbox, VSelect} from 'vuetify'

  export default {
    name: 'class-gen',
    components: {VForm, VCheckbox, VSelect, ...VCard},
    data: () => ({
      show: false,
      valid: true,
      name: 'demo',
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length <= 10) || 'Name must be less than 10 characters'
      ],
      dir: '@app/Controllers',
      dirRules: [
        v => !!v || 'E-mail is required',
        v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
      ],
      suffix: 'Controller',
      tplDir: '@devtool/res/templates',
      tplFile: 'controller.stub',
      select: 'controller',
      items: [
        'command',
        'controller',
        'listener',
        'middleware',
        'process',
        'task',
        'ws controller'
      ],
      defaultTplFiles: [
        'command.stub',
        'controller-rest.stub',
        'controller.stub',
        'listener.stub',
        'middleware.stub',
        'process.stub',
        'task.stub',
        'ws-controller.stub'
      ],
      override: false,
      config: {}
    }),

    methods: {
      submit () {
        if (this.$refs.form.validate()) {
          // Native form submission is not yet supported
          axios.post('/api/submit', {
            name: this.name,
            email: this.email,
            select: this.select,
            checkbox: this.checkbox
          })
        }
      },
      clear () {
        this.$refs.form.reset()
      }
    }
  }
</script>

<style scoped>

</style>
