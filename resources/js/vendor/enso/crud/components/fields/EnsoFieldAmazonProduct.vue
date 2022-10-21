<template>
  <div :class="allFieldsetClasses">
    <label
      :for="id_attr"
      :class="labelClasses"
      v-if="label"
    >
      <slot name="label">{{ label }}</slot>
    </label>
    <p :class="controlClasses">
      <slot>
        <input
          :name="name"
          :value="internal_value"
          :id="id_attr"
          :placeholder="placeholder"
          :class="allInputClasses"
          :required="required"
          :readonly="readonly"
          :disabled="disabled"
          @input="lookupASIN($event.target.value)"
          type="text"
        >
      </slot>
      <div class="help is-warning" v-if="doing_lookup">
        Checking product...
      </div>
      <div v-else-if="is_valid" class="help">
        Found this product: <a :href="product.url" target="_blank" rel="nofollow noreferer">{{ product.title }}</a>
      </div>
      <div v-else-if="this.internal_value" class="help is-danger">
        Not found...
      </div>
      <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
      <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
    </p>
  </div>
</template>

<script>
  import BaseInput from '../mixins/base-input';
  import debounce  from 'lodash/debounce';
  import swal from 'sweetalert2/dist/sweetalert2.js';

  export default {
    mixins: [BaseInput],

    data() {
      return {
        doing_lookup: false,
        is_valid: false,
        product: null,
        lookup_url: '/admin/amazon/asin-check',
        internal_value: null,
      };
    },

    created() {
      this.internal_value = this.value;

      this.lookupASIN(this.internal_value);
    },

    methods: {
      lookupASIN: debounce(function(value) {
        this.internal_value = value;
        this.is_valid = false;
        this.product = null;
        this.updateValue(null);

        if (!value) {
          return;
        }

        this.doing_lookup = true;

        this.$root.axios.get(this.lookup_url, {
          params: {
            asin: value,
          },
        })
        .then((response) => {
          if (response.data.product_exists) {
            this.is_valid = true;
            this.product = response.data.product;
            this.updateValue(response.data.product.asin);
          }
        })
        .catch((error) => {
          swal({
            title: 'Oops...',
            text: 'Something went wrong!',
            type: 'error',
          });
        })
        .then(() => {
          this.doing_lookup = false;
        });
      }, 500),
    }
  }
</script>
