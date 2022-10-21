// Make all App specific changes in this file.

import Vue from 'vue';

/**
 * This import is your base enso-admin file. You should not edit it,
 * it will be overriden as JS changes to Enso are made.
 */
import admin_mixin from './vendor/enso/enso-admin';

import store from './vendor/enso/store';

/**
 * Add custom store modules here with store.registerModule(moduleName, storeModule);
 */

var App = new Vue({
  store,
  mixins: [
    admin_mixin
  ],
});
