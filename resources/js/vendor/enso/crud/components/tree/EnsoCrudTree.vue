<template>
  <div>
    <div v-if="is_loading">Loading...</div>
    <div v-if="error_loading">Error getting data.</div>
    <div v-else class="enso-nested-tree">
      <enso-nested-item :items="items" :max-depth="crud.max_depth" />
    </div>
  </div>
</template>

<script>
import EnsoNestedItem from './EnsoNestedItem.vue';
import get from 'lodash/get';
import swal from 'sweetalert2/dist/sweetalert2.js';

export default {
  components: {
    EnsoNestedItem,
  },

  data() {
    return {
      items: [],
      crud: enso.crud,
      is_loading: true,
      error_loading: false,
    };
  },

  mounted() {
    this.endpoint = enso.crud.route;

    this.getItems();
  },

  computed: {
    dragOptions() {
      return {
        handle: '.drag-handle',
      };
    },
  },

  methods: {
    getItems() {
      this.items = [];
      this.is_loading = true;
      this.error_loading = false;

      this.axios
        .get(this.endpoint + '/tree-data')
        .then(this.onResponse)
        .catch(this.onError);
    },

    onResponse(response) {
      this.error_loading = false;

      if (response.data.status !== 'success') {
        if (response.data.message) {
          throw response.data.message;
        } else {
          throw 'An unknown error occurred.';
        }
      }

      this.items = response.data.data.items;
      this.is_loading = false;
    },

    onError(error) {
      this.request_id = null;

      if (get(error, 'response.status', false) === 422) {
        if (get(error, 'response.data.errors')) {
          this.errors = get(error, 'response.data.errors', []);
        } else {
          this.error = get(error, 'response.data', []);
        }
      } else {
        let error_msg = get(error, 'response.data.message', error.toString());

        swal({
          title: 'Oops...',
          text: 'Something went wrong! ' + error_msg,
          type: 'error',
        });
      }

      this.is_loading = false;
      this.error_loading = true;
    },
  },
};
</script>
