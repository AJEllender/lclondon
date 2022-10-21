<template>
  <div>
    <div v-if="!firstItem" class="has-text-centered">
      <button
        :class="matchingTypeButtonClass"
        @click.prevent="toggleMatchingType"
      >{{ matchingType }}</button>
    </div> 

    <div class="query-condition">
      <div class="columns is-marginless is-flex-fullauto">
        <div class="column is-3">
          <div class="select is-fullwidth" :class="operandClasses">
            <select 
              :value="operandValue"
              @input="updateOperand($event.target.value)"
            >
              <option :value="null" disabled selected>Please select...</option>
              <option v-for="(option, value) in operandOptions" :key="value" :value="value">{{ option }}</option>
            </select>
          </div>
        </div>
        <div class="column is-3">
          <div class="select is-fullwidth" :class="operatorClasses">
            <select 
              :value="operatorValue"
              @input="updateOperator($event.target.value)"
            >
              <option :value="null" disabled selected>Please select...</option>
              <option v-for="(option, value) in operatorOptions" :key="value" :value="value">{{ option }}</option>
            </select>
          </div>
        </div>
        <div class="columns column is-6">
          <div class="column">
            <div v-if="!dataInputsDisabled">
              <component
                :is="dataComponent"
                :fieldset-classes="['has-addons']"
                :field-classes="['is-expanded']"
                v-bind="dataComponentProps"
                v-for="(datum, datum_key) in data"
                :key="datum_key"
                :input-value="datum"
                :input-classes="[elementSize]"
                ref="data_selection"
                @input="updateData($event, datum_key)"
              >
                <p slot="appends" class="control" @click.prevent="removeData(datum_key)">
                  <button
                    class="button is-danger"
                    :class="elementSize"
                    :disabled="!canRemoveData"
                  >
                    <span class="icon"><i class="fa fa-minus-circle"></i></span>
                  </button>
                </p>
              </component>
            </div>
          </div>
          <div class="column is-narrow no-horizontal-padding">
            <button
              class="button is-success"
              :class="elementSize"
              :disabled="dataInputsDisabled"
              @click.prevent="addData"
            ><span class="icon"><i class="fa fa-plus-circle"></i></span></button>
          </div>
        </div>
      </div>

      <div class="is-flex-fixed remove-button">
        <button
          class="button is-danger"
          :class="elementSize"
          @click.prevent="removeSelf"
        ><span class="icon"><i class="fa fa-times"></i></span></button>
      </div>
    </div>
  </div>
</template>

<script>
  import assign from 'lodash/assign';
  import clone from 'lodash/clone';
  import get from 'lodash/get';
  import includes from 'lodash/includes';
  import isArray from 'lodash/isArray';
  import mapValues from 'lodash/mapValues';
  import merge from 'lodash/merge';
  import pickBy from 'lodash/pickBy';

  export default {
    inject: [
      "operands",
      "operators",
      "elementSize",
    ],

    props: {
      condition: {
        type: Object,
        required: true,
      },

      index: {
        type: Number,
        required: true,
      },
    },

    computed: {
      matchingType() {
        return get(this.$parent.condition, 'type', 'AND');
      },

      firstItem() {
        return this.index === 0;
      },

      operand() {
        return this.operandByName(this.operandValue);
      },

      operandValue() {
        return get(this.condition, 'operand', null);
      },

      operandIsValid() {
        return !! this.operandValue;
      },

      operandClasses() {
        let classes = [];

        if (this.elementSize) {
          classes.push(this.elementSize);
        }

        if (!this.operandIsValid) {
          classes.push('is-danger');
        }

        return classes.join(' ');
      },

      operandOptions() {
        return mapValues(this.operands, function(item) {
          return item.label;
        });
      },

      operandProps() {
        let property_set = get(this.operands, `${this.operandValue}.component_props.operators.${this.operatorValue}`, "");

        if (property_set.length === 0) {
          return {};
        }

        return assign({}, get(this.operands, `${this.operandValue}.component_props.property_sets.${property_set}`, {}));
      },

      operator() {
        return this.operatorByName(this.operatorValue);
      },

      operatorValue() {
        return get(this.condition, 'operator', null);
      },

      operatorIsValid() {
        return this.operandAllowsOperator(this.operandValue, this.operatorValue);
      },
      
      operatorClasses() {
        let classes = [];

        if (this.elementSize) {
          classes.push(this.elementSize);
        }

        if (!this.operatorIsValid) {
          classes.push('is-danger');
        }

        return classes.join(' ');
      },

      operatorOptions() {
        return mapValues(pickBy(this.operators, (value, name) => {
          return includes(get(this.operand, 'allowed_operators', []), name);
        }), function(item) {
          return item.label;
        });
      },

      operatorProps() {
        return assign({}, get(this.operators, `${this.operatorValue}.component_props`, {}));
      },

      data() {
        let value = get(this.condition, 'data', []);

        if (value.length === 0) {
          return [null];
        }

        if (isArray(value)) {
          return value;
        }

        return [value];
      },

      dataComponent() {
        return get(this.operator, 'component', 'enso-field-text');
      },  

      dataComponentProps() {
        return merge(
          this.operatorProps,
          this.operandProps,
        );
      },

      dataInputsDisabled() {
        return !(this.operandIsValid && this.operatorIsValid);
      },

      canRemoveData() {
        return this.data.length > 1;
      },

      matchingTypeButtonClass() {
        let classes = ['button'];

        if (this.matchingType === 'OR') {
          classes.push('is-link');
        } else {
          classes.push('is-info');
        }

        if (this.elementSize) {
          classes.push(elementSize);
        }

        return classes.join(' ');
      }
    },

    methods: {
      operandByName(name) {
        return get(this.operands, name, undefined);
      },

      operandAllowsOperator(operand, operator) {
        return includes(
          get(this.operandByName(operand), 'allowed_operators', []),
          operator
        );
      },

      operatorByName(name) {
        return get(this.operators, name, undefined);
      },

      updateOperand(value) {
        let condition = clone(this.condition);
        
        this.$set(condition, 'operand', value);

        /**
         * If new operand does not support existing operator, clear the operator.
         * This should trigger an error state class to be applied
         */
        if (!this.operandAllowsOperator(value, this.operatorValue)) {
          this.$set(condition, 'operator', null);
          this.$set(condition, 'data', []);
        }

        this.updateCondition({id: this.index, condition: condition });
      },

      updateOperator(value) {
        let condition = clone(this.condition),
            old_operator = this.operatorValue;
        
        condition.operator = value;
        if (!this.componentsMatch(old_operator, value)) {
          this.$set(condition, 'data', []);
        }

        this.updateCondition({id: this.index, condition: condition});
      },

      componentsMatch(old_operator_name, new_operator_name) {
        let old_component = get(this.operatorByName(old_operator_name), 'component', 'enso-field-text'),
            new_component = get(this.operatorByName(new_operator_name), 'component', 'enso-field-text');

        return old_component === new_component;
      },

      addData() {
        let condition = clone(this.condition);

        condition.data = this.data; // Ensure data is in array format.
        condition.data.push('');

        this.updateCondition({id: this.index, condition: condition});

        this.$nextTick(() => {
          // Focus the new element
          let last_data_element = get(this.$refs['data_selection'], this.$refs['data_selection'].length -1);

          if (last_data_element) {
            let element_input = get(last_data_element, '$refs.input');

            if (element_input) {
              element_input.focus();
            }
          }
        });
      },

      updateData(value, key) {
        let condition = clone(this.condition);
        
        condition.data = this.data;
        condition.data[key] = value;

        this.updateCondition({id: this.index, condition: condition});
      },

      removeData(key) {
        if (!this.canRemoveData) {
          return;
        }

        let condition = clone(this.condition);

        condition.data = this.data; // Ensure data is in array format.
        condition.data.splice(key, 1);

        this.updateCondition({id: this.index, condition: condition});
      },

      toggleMatchingType() {
        this.$emit('toggle-matching-type');
      },

      updateCondition(condition) {
        this.$emit('update', condition);
      },
      
      removeSelf() {
        this.$emit('remove-condition', this.condition);
      },
    },
  }
</script>

<style lang="scss" scoped>
  .query-condition {
    background-color: #eee;
    border: 1px solid #bbb;
    border-radius: 4px;
    padding-right: 10px;
    margin: 10px;
    display: flex;
  }

  .remove-button {
    margin-top: 0.75rem;
  }

  .no-horizontal-padding {
    padding-left: 0;
    padding-right: 0;
  }

  .is-flex-fullauto {
    flex: 1 1 auto;
  }
  
  .is-flex-fixed {
    flex: 0 0 auto;
  }
</style>