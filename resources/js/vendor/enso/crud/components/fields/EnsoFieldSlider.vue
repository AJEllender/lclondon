<template>
  <div :class="allFieldsetClasses">
      <label
          :for="id_attr"
          :class="labelClasses"
      >
          <slot name="label">{{ field.label }}</slot>
      </label>
      <p :class="controlClasses">
        <slot>
          {{ item[field_name] }} {{ field_name }}<br><br><br>
          <vue-slider
            :name="field.name"
            v-model="item[field_name]"
            v-bind="item"
            :height="5"
            :min="field.options.min"
            :max="field.options.max"
            :interval="field.options.interval"
            :readonly="field.readonly"
            :disabled="field.disabled"
          >
          </vue-slider>
        </slot>
        <input
          type="hidden"
          v-model="current_value"
          :name="name"
        >
       <span :class="helpTextClasses" v-if="this.field.help_text">{{ this.field.help_text }}</span>
       <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
     </p>
  </div>
</template>

<script>
  import BaseInput from '../mixins/base-input';
  import vueSlider from 'vue-slider-component/src/vue2-slider.vue';

  export default {
    mixins: [
      BaseInput,
      vueSlider
    ],

    components: {
      vueSlider
    },

    // data() {
    //   return {
    //     options: this.field.options
    //   };
    // },

    // data () {
    //   return {
    //     width: "auto",
    //     height: 6,
    //     value: 0,
    //     direction: "horizontal",
    //     dotSize: 16,
    //     eventType: "auto",
    //     min: 0,
    //     max: 100,
    //     interval: 1,
    //     disabled: false,
    //     show: true,
    //     tooltip: "always",
    //     tooltipDir: "top",
    //     piecewise: false,
    //     lazy: false,
    //     reverse: false,
    //     speed: 0.5,
    //     formatter: null,
    //     bgStyle: null,
    //     sliderStyle: null,
    //     tooltipStyle: null,
    //     processStyle: null,
    //     piecewiseStyle: null
    //   }
    // }
    created() {
      // this.options = this.field.options;
    }
  }
</script>
