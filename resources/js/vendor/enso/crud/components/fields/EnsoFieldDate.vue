<template>
  <div :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses" v-if="label">
      <slot name="label">{{ label }}</slot>
    </label>
    <slot name="prepends"></slot>
    <p :class="controlClasses">
      <slot>
        <Datepicker
          :value="dateInput"
          :name="name"
          :id="id_attr"
          :disabled-picker="readonly || disabled"
          :monday-first="true"
          :required="required"
          :input-class="allInputClasses"
          :placeholder="placeholder"
          :clear-button="!required"
          @input="updateDateValue"
          ref="input"
        ></Datepicker>
      </slot>
      <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
      <span :class="helpTextClasses" v-for="(error, index) in errors" :key="index">{{ error }}</span>
    </p>
    <slot name="appends"></slot>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';
import Datepicker from 'vuejs-datepicker';
import Moment from 'moment';

export default {
  mixins: [BaseInput],

  components: {
    Datepicker,
  },

  methods: {
    updateDateValue(new_value) {
      if (!new_value) {
        new_obj = null;
      } else {
        // Output from Datepicker: "2017-06-02T23:00:00.000Z"
        // Format for PHP: { "date": "2017-06-23 12:46:30.000000", "timezone_type": 3, "timezone": "UTC" }
        var new_moment = new Moment(new_value);

        // To prevent conversion to utc wrapping to previous/next day (e.g. if browser is on BST)
        // I'm setting the time to 12 midday. This isn't exactly elegant but it should hold up.
        // I'm currently only using this as readonly so will have to revisit later on.
        // Really we shouldn't have to normalise to UTC but moment.js has removed some formatting
        // options so I'm going with this for now. -Jake
        new_moment.hour(12);

        new_moment.utc();
        var new_obj = {
          date: new_moment.format('YYYY-MM-DD HH:mm:ss.SSSSSS'),
          timezone_type: 3,
          timezone: 'UTC',
        };
      }

      this.$emit('input', new_obj);
    },
  },

  computed: {
    // Format the date value for input into the date picker
    dateInput() {
      if (this.value) {
        return Moment(this.value.date).format();
      } else {
        return null;
      }
    },
  },
};
</script>
