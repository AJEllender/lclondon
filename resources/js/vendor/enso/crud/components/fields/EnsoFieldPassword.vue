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
                  :value="value[0]"
                  :id="id_attr"
                  :placeholder="placeholder"
                  :class="allInputClasses"
                  :required="required"
                  :readonly="readonly"
                  :disabled="disabled"
                  type="password"
                  @input="updatePassword($event.target.value)"
              >
          </slot>
          <span :class="helpTextClasses" v-if="this.helpText">{{ this.helpText }}</span>
          <span :class="helpTextClasses" v-for="error in errors">{{ error }}</span>
        </p>
        <label
            :for="id_attr + '_confirmation'"
            :class="labelClasses"
            v-if="needsConfirmation"
        >
            <slot name="label">{{ label }} Confirmation</slot>
        </label>
        <p
          :class="controlClasses"
          v-if="needsConfirmation"
        >
            <slot>
                <input
                    :name="confirmationName"
                    :value="value[1]"
                    :id="id_attr + '_confirmation'"
                    :placeholder="placeholder"
                    :class="allInputClasses"
                    :required="required"
                    :readonly="readonly"
                    :disabled="disabled"
                    type="password"
                    @input="updateConfirmation($event.target.value)"
                >
            </slot>
            <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
            <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
        </p>
    </div>
</template>

<script>
  import BaseInput from '../mixins/base-input';

  export default {
    mixins: [BaseInput],

    props: [
        'confirmed',
    ],

    computed: {
      confirmationName: function() {
        return this.name + "_confirmation";
      }
    },

    methods: {
      needsConfirmation: function() {
        return this.confirmed;
      },

      updatePassword(value) {
        let val = _.clone(this.value);
        val[0] = value;
        this.updateValue(val);
      },

      updateConfirmation(value) {
        let val = _.clone(this.value);
        val[1] = value;
        this.updateValue(val);
      },
    }
  }
</script>
