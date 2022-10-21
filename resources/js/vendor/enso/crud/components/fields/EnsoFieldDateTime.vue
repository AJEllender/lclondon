<template>
  <div :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses" v-if="label">
      <slot name="label">{{ label }}</slot>
    </label>
    <p :class="controlClasses">
      <slot>
        <div class="columns">
          <div class="column" v-if="showDate">
            <Datepicker
              v-bind="$attrs"
              v-on="listenersWithoutInput"
              :value="dateInput"
              :id="id_attr"
              :disabled="readonly || disabled"
              :monday-first="true"
              :required="required"
              :input-class="allInputClasses"
              :clear-button="false"
              ref="input"
              :name="name + '_date'"
            >
            </Datepicker>
          </div>
          <div class="column time-column" v-if="showTime">
            <div class="field has-addons">
              <p class="control">
                <input
                  type="number"
                  maxlength="2"
                  placeholder="hh"
                  :disabled="disabled"
                  :readonly="readonly"
                  :required="required"
                  :value="hours"
                  :class="allInputClasses"
                  @keypress="onKeypressHours"
                  @input="onInputHours"
                  :name="name + '_hours'"
                />
              </p>
              <p class="control time-separator">
                <a class="button is-static" :disabled="disabled" :readonly="readonly">:</a>
              </p>
              <p class="control">
                <input
                  type="number"
                  maxlength="2"
                  placeholder="mm"
                  :disabled="disabled"
                  :readonly="readonly"
                  :required="required"
                  :value="minutes"
                  :class="allInputClasses"
                  @keypress="onKeypressMinutes"
                  @input="onInputMinutes"
                  :name="name + '_minutes'"
                />
              </p>
              <p class="control time-separator" v-if="!hideSeconds">
                <a class="button is-static" :disabled="disabled" :readonly="readonly">:</a>
              </p>
              <p class="control" v-if="!hideSeconds">
                <input
                  type="number"
                  maxlength="2"
                  placeholder="ss"
                  :disabled="disabled"
                  :readonly="readonly"
                  :required="required"
                  :value="seconds"
                  :class="allInputClasses"
                  @keypress="onKeypressSeconds"
                  @input="onInputSeconds"
                  :name="name + '_seconds'"
                />
              </p>
            </div>
          </div>
          <div class="column is-narrow" v-else-if="showStaticTimezone">
            <a class="button is-static">UTC</a>
          </div>
          <div class="column is-narrow" v-if="!readonly && !disabled">
            <button class="button" @click.prevent="clearDate" title="Clear date">
              <span class="icon">
                <i class="fa fa-times"></i>
              </span>
            </button>
          </div>
        </div>
      </slot>
      <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
      <span :class="helpTextClasses" v-for="(error, index) in errors" :key="index">{{
        error
      }}</span>
    </p>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';
import Datepicker from 'vuejs-datepicker';
import Moment from 'moment';
import MomentTimezone from 'moment-timezone';
import Multiselect from 'vue-multiselect';
import clone from 'lodash/clone';

// Note:
// Output from Datepicker: "2017-06-02T23:00:00.000Z"
// Format to/from PHP:
// {
//   "date": "2017-06-23 12:46:30.000000",
//   "timezone_type": 3,
//   "timezone": "UTC"
// }
export default {
  mixins: [BaseInput],

  inheritAttrs: false,

  components: {
    Datepicker,
    Multiselect,
  },

  props: {
    columnType: {
      type: String,
      default: 'datetime',
    },
    defaultTimezone: {
      type: String,
      default: 'UTC',
    },
    hideSeconds: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      timezone: 'UTC',
      timezones: [],
      format_string: 'YYYY-MM-DD HH:mm:ss.SSSSSS',
      input_format: 'YYYY-MM-DDTHH:MM:SS',

      // The central date object that everything else will be based on.
      // This should be updated when fields change, when props change, and
      // formatted for PHP when being emitted, etc.
      the_date: null,
    };
  },

  created() {
    this.timezone = this.defaultTimezone;
    this.timezones = Moment.tz.names().map(function (item) {
      return {
        id: item,
        label: item,
      };
    });

    if (this.value) {
      this.the_date = Moment.tz(this.value.date, this.value.timezone);
    }
  },

  watch: {
    value: {
      immediate: true,
      handler(new_val, old_val) {
        if (new_val) {
          this.the_date = Moment.tz(new_val.date, new_val.timezone);
          this.timezone = new_val.timezone;
        }
      },
    },
  },

  methods: {
    // If we don't have a date and we're updating a part (hours, timezone, etc)
    // then create a date and set it to now
    initDate() {
      if (this.hasDate()) {
        return;
      }

      this.the_date = Moment.tz(this.timezone);
      this.the_date.hour(0);
      this.the_date.minute(0);
      this.the_date.second(0);
    },

    hasDate() {
      return this.the_date !== null;
    },

    clearDate() {
      this.the_date = null;
      this.timezone = 'UTC';
      this.emitValue();
    },

    emitValue() {
      if (!this.hasDate()) {
        this.$emit('input', null);
        return;
      }

      var new_obj = {
        date: this.the_date.format(this.format_string),
        timezone_type: 3,
        timezone: this.timezone,
      };

      this.$emit('input', new_obj);
    },

    onKeypressHours(e) {
      if (!e.key.match(/^[0-9]$/)) {
        e.preventDefault();
        return false;
      }
    },

    onInputHours(e) {
      this.initDate();

      let val = parseInt(e.target.value, 10);

      if (isNaN(val)) {
        val = 0;
      }

      // If value is out of range, re-emit the current
      // value, which will reset the value in the input
      if (val > 23 || val < 0) {
        this.emitValue();
        return;
      }

      this.the_date.hour(val);
      this.emitValue();
    },

    onKeypressMinutes(e) {
      if (!e.key.match(/^[0-9]$/)) {
        e.preventDefault();
        return false;
      }
    },

    onInputMinutes(e) {
      this.initDate();

      let val = parseInt(e.target.value, 10);

      if (isNaN(val)) {
        val = 0;
      }

      // If value is out of range, re-emit the current
      // value, which will reset the value in the input
      if (val > 59 || val < 0) {
        this.emitValue();
        return;
      }

      this.the_date.minute(val);
      this.emitValue();
    },

    onKeypressSeconds(e) {
      if (!e.key.match(/^[0-9]$/)) {
        e.preventDefault();
        return false;
      }
    },

    onInputSeconds(e) {
      this.initDate();

      let val = parseInt(e.target.value, 10);

      if (isNaN(val)) {
        val = 0;
      }

      // If value is out of range, re-emit the current
      // value, which will reset the value in the input
      if (val > 59 || val < 0) {
        this.emitValue();
        return;
      }

      this.the_date.second(val);
      this.emitValue();
    },

    updateDateValue(new_value) {
      if (!new_value) {
        return;
      }

      if (this.hasDate()) {
        // Create a new moment instance based on the returned date. This will
        // use the timezone of the clients' browser and will therefore not
        // necessarily match up with our own internal date
        let new_moment = Moment(new_value);

        // Apply the chosen date to our existing internal date
        this.the_date.year(new_moment.year());
        this.the_date.month(new_moment.month());
        this.the_date.date(new_moment.date());
      } else {
        this.the_date = Moment.tz(new_value, this.timezone);
        this.the_date.hour(0);
        this.the_date.minute(0);
        this.the_date.second(0);
      }

      this.emitValue();
    },
  },

  computed: {
    // Format the date value for input into the date picker
    dateInput() {
      return this.hasDate() ? this.the_date.format(this.input_format) : null;
    },

    hours() {
      if (this.hasDate()) {
        return this.the_date.format('H');
      }

      return '';
    },

    minutes() {
      if (this.hasDate()) {
        return this.the_date.format('m');
      }

      return '';
    },

    seconds() {
      if (this.hasDate() && this.the_date.second() !== 0) {
        return this.the_date.format('s');
      }

      return '';
    },

    showDate() {
      return ['datetime', 'date', 'timestamp'].includes(this.columnType);
    },

    showTime() {
      return ['datetime', 'time', 'timestamp'].includes(this.columnType);
    },

    showStaticTimezone() {
      return this.columnType === 'timestamp';
    },

    // Timezone as an object to use as the selected value in Multiselect
    timezoneVal() {
      return {
        id: this.timezone,
        label: this.timezone,
      };
    },

    listenersWithoutInput() {
      let listeners = clone(this.$listeners);

      listeners.input = this.updateDateValue;

      return listeners;
    },
  },
};
</script>
