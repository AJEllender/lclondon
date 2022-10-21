<template>
    <div :class="allFieldsetClasses">
        <label
            :for="id_attr"
            :class="labelClasses"
            v-if="label"
        >
            <slot name="label">{{ label }}</slot>
        </label>
        <slot name="prepends"></slot>
        <p :class="controlClasses">
          <slot>
              <input
                  :id="id_attr"
                  :name="name"
                  :value="value"
                  :placeholder="placeholder"
                  :class="allInputClasses"
                  :required="required"
                  :readonly="readonly"
                  :disabled="disabled"
                  type="email"
                  ref="input"
                  @input="updateValue($event.target.value)"
              >
          </slot>
          <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
          <span :class="helpTextClasses" v-for="error in errors">{{ error }}</span>
        </p>
        <slot name="appends"></slot>
    </div>
</template>

<script>
  import BaseInput from '../mixins/base-input';

  export default {
    mixins: [BaseInput],
  }
</script>
