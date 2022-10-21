<template>
  <div class="enso-bulk-actions">
    <p class="bulk-action-help" v-if="helpText">
      <small>{{ helpText }}</small>
    </p>
    <enso-field-select
      v-bind="selectSettings"
      :fieldset-classes="['field', 'has-addons']"
      :field-classes="['is-expanded']"
      ref="selectField"
      :input-value="selectValue"
      @input="updateCurrentValue"
    >
      <div class="control" slot="appends">
        <a class="button is-primary" @click.prevent="commitBulkAction">Go</a>
      </div>
    </enso-field-select>
  </div>
</template>

<script>
import swal from 'sweetalert2/dist/sweetalert2.js';
import EnsoFieldSelect from '../components/fields/EnsoFieldSelect.vue';
import queryString from 'query-string';
import get from 'lodash/get';
import map from 'lodash/map';
import Qs from 'qs';

export default {
  components: {
    EnsoFieldSelect,
  },

  props: {
    actions: {
      default: () => {},
      type: Object,
    },

    selections: {
      default: () => {},
      type: Object,
    },

    currentFilters: {
      default: () => {},
      type: Object,
    },

    values: {
      default: [],
      type: Array,
    },
  },

  data() {
    return {
      submitting: false,
      current_value: undefined,
    };
  },

  computed: {
    selectSettings() {
      return {
        settings: {
          options: this.bulkActionsLabels,
          track_by: 'value',
          label: 'label',
          select_label: '',
          deselect_label: '',
        },
      };
    },

    bulkActionsLabels() {
      return map(this.actions, (action, name) => {
        return {
          value: name,
          label: get(action, 'label', 'Unnamed Action'),
        };
      });
    },

    helpText() {
      let action = this.getActionByName(this.current_value);

      if (action === undefined) {
        return '';
      }

      return get(action, 'help_text', '');
    },

    currentAction() {
      if (!this.current_value) {
        return null;
      }

      return this.actions[this.current_value];
    },

    selectValue() {
      if (!this.current_value) {
        return null;
      }

      return {
        name: this.current_value,
        label: this.currentAction.label,
      };
    },
  },

  methods: {
    getActionByName(name) {
      return get(this.actions, name, undefined);
    },

    updateCurrentValue(selected) {
      this.current_value = get(selected, 'value', undefined);
    },

    commitBulkAction(event) {
      if (this.current_value) {
        let action = this.getActionByName(this.current_value);
        if (action !== undefined) {
          this.commitAction(action);
        } else {
          this.generalError();
        }
      } else {
        this.generalError('No Action Selected');
      }
    },

    commitAction(action) {
      let is_download = get(action, 'is_download', false);
      let method = get(action, 'method');
      let route = get(action, 'route');

      if (!method) {
        this.generalError("Bulk action 'method' missing");
        return;
      }

      if (!method) {
        this.generalError("Bulk action 'route' missing");
        return;
      }

      this.submitting = true;

      let data = {
        values: this.values,
        filters: this.currentFilters,
      };

      this.axios
        .request({
          method: method,
          url: route,
          params: data,
          paramsSerializer: function(params) {
            return Qs.stringify(params, { arrayFormat: 'brackets' });
          },
        })
        .then(this.onResponse)
        .catch(this.onError);
    },

    onResponse(response) {
      this.submitting = false;

      if (
        response.data.status === 'success' ||
        response.data.status === 'warning' ||
        response.data.status === 'info'
      ) {
        // Allow for providing a download url.
        let download_url = get(response, 'data.data.file_url', null);
        if (download_url) {
          window.location = download_url;
          return;
        }

        swal({
          title: get(response, 'data.title', 'Success!'),
          text: get(response, 'data.message', 'Your changes were saved successfully.'),
          type: response.data.status,
          allowOutsideClick: false,
          allowEscapeKey: false,
        }).then((result) => {
          if (typeof response.data.redirect_url === 'undefined') {
            this.$emit('update');
          } else {
            window.location = response.data.redirect_url;
          }
        });
      } else if (response.data.status === 'error') {
        this.$emit('update');
        swal({
          title: 'Oops!',
          text: response.data.message,
          type: 'error',
        });
      } else {
        swal({
          title: 'Oops!',
          text: 'Something went wrong. Please try again.',
          type: 'error',
        });
      }
    },

    onError(error) {
      this.submitting = false;

      if (get(error, 'response.status', false) === 422) {
        if (get(error, 'response.data.errors')) {
          this.errors = get(error, 'response.data.errors', []);
        } else {
          this.errors = get(error, 'response.data', []);
        }

        window.scrollTo(0, 0);
      } else {
        swal({
          title: 'Oops!',
          text: 'Something went wrong! ' + error,
          type: 'error',
        });
      }
    },

    generalError(message) {
      swal({
        title: 'Oops!',
        text: message ? message : 'Something went wrong. Please try again.',
        type: 'error',
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.bulk-action-help {
  margin-bottom: 5px;
}
</style>
