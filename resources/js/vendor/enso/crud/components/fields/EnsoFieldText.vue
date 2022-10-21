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
                    :name="name"
                    :value="value"
                    :id="id_attr"
                    :placeholder="placeholder"
                    :class="allInputClasses"
                    :required="required"
                    :readonly="readonly"
                    :disabled="disabled"
                    :pattern="pattern"
                    :minlength="inputMin"
                    :maxlength="inputMax"
                    type="text"
                    ref="input"
                    @input="updateValue($event.target.value)"
                >
            </slot>
            <span v-if="(hasMin || hasMax) && value" :class="lengthClasses">{{ value.length }}</span>
            <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
            <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
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
