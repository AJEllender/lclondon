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
              <textarea
                  :name="name"
                  :value="value"
                  :id="id_attr"
                  :placeholder="placeholder"
                  :class="allInputClasses"
                  :rows="rows"
                  :required="required"
                  :readonly="readonly"
                  :disabled="disabled"
                  :pattern="pattern"
                  :minlength="inputMin"
                  :maxlength="inputMax"
                  ref="input"
                  @input="updateValue($event.target.value)"
                >
              </textarea>
          </slot>
          <span v-if="(hasMin || hasMax) && value" :class="lengthClasses">{{ value.length }}</span>
          <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
          <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
        </p>
    </div>
</template>

<script>
  import BaseInput from '../mixins/base-input';

  export default {
    mixins: [BaseInput],

    data() {
        return {
            input_classes: ['input', 'textarea'],
        };
    },

    props: {
      rows: {
        default() {
          return 5;
        }
      },
    }
  }
</script>
