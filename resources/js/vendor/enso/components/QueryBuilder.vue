<template>
  <div class="query-builder">
    <component 
      :is="condition.component" 
      v-for="(condition, index) in conditions" 
      :key="index"
      :index="index"
      :condition="condition"
      @update="updateCondition"
      @remove-condition="removeCondition"
    ></component>
  </div>
</template>

<script>
  import clone from 'lodash/clone';
  import filter from 'lodash/filter';
  import get from 'lodash/get';

  export default {
    provide() {
      return {
        operands: this.operands,
        operators: this.operators,
        elementSize: this.elementSize, 
      };
    },

    components: {
      //
    },

    props: {
      fieldSelectionOptions: {
        type: Object,
        required: true,
      },

      conditions: {
        type: Array,
        default: () => {
          return [];
        },
      },

      elementSize: {
        type: String,
        default: '',
      },
    },

    computed: {
      operands() {
        return get(this.fieldSelectionOptions, 'operands', []);
      },

      operators() {
        return get(this.fieldSelectionOptions, 'operators', []);
      },
    },

    methods: {
      updateCondition({ id, condition }) {
        let new_conditions = clone(this.conditions);

        this.$set(new_conditions, id, condition);

        this.$emit('update', new_conditions);
      },

      removeCondition(condition) {
        let new_conditions = this.conditions.filter((item) => {
          return item !== condition;
        });

        this.$emit("update", new_conditions);
      },
    },
  }
</script>

<style lang="scss" scoped>
  .query-builder {
    margin: 0 -10px;
  }
</style>
