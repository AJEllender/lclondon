<template>
  <div>
    <div v-if="!firstItem" class="has-text-centered">
      <button
        :class="matchingTypeButtonClass"
        @click.prevent="toggleMatchingType"
      >{{ matchingType }}</button>
    </div> 
    <div class="query-group" :class="(depth % 2) ? 'query-group-dark' : ''">
      <ul class="has-text-right query-group-actions">
        <li 
          class="button is-danger"
          :class="elementSize"
          @click.prevent="removeSelf"
        ><span class="icon"><i class="fa fa-times"></i></span></li>
      </ul>
      <div v-if="hasConditions" class="query-elements">
        <component 
          :is="condition.component" 
          v-for="(condition, index) in conditions" 
          :key="index"
          :index="index"
          :condition="condition"
          :depth="depth + 1"
          @update="updateCondition"
          @toggle-matching-type="toggleOwnMatchingType"
          @remove-condition="removeCondition"
        ></component>
      </div>
      <ul class="has-text-right query-group-actions">
        <li 
          class="button is-success"
          :class="elementSize"
          @click.prevent="addGroup"
        ><span class="icon"><i class="fa fa-plus"></i></span><span>Group</span></li>
        <li 
          class="button is-success"
          :class="elementSize"
          @click.prevent="addCondition"
        ><span class="icon"><i class="fa fa-plus"></i></span><span>Condition</span></li>
      </ul>
    </div>
  </div>
</template>

<script>
  import clone from 'lodash/clone';
  import filter from 'lodash/filter';
  import get from 'lodash/get';

  export default {
    inject: [
      'elementSize',
    ],

    components: {
      //
    },

    props: {
      condition: {
        type: Object,
        required: true,
      },
      index: {
        type: Number,
        required: true,
      },
      depth: {
        type: Number,
        default: 0,
      },
    },

    computed: {
      conditions() {
        return get(this.condition, 'conditions', []);
      },

      hasConditions() {
        return !! this.conditions.length;
      },

      matchingType() {
        return get(this.$parent.condition, 'type', 'AND');
      },

      firstItem() {
        return this.index === 0;
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
      addGroup() {
        let new_condition = clone(this.condition);

        new_condition.conditions.push({
          component: 'query-group', 
          type: 'AND',
          conditions: [],
        });

        this.$emit('update', { id: this.index, condition: new_condition });
      },

      addCondition() {
        let new_condition = clone(this.condition);

        new_condition.conditions.push({
          type: 'OR',
          component: 'query-condition', 
          conditions: [],
        });

        this.$emit('update', { id: this.index, condition: new_condition });
      },

      updateCondition({ id, condition }) {
        let new_condition = clone(this.condition);

        this.$set(new_condition.conditions, id, condition);

        this.$emit('update', { id: this.index, condition: new_condition });
      },

      removeCondition(condition) {
        let new_condition = clone(this.condition);

        this.$set(new_condition, 'conditions', this.conditions.filter((item) => {
          return item !== condition;
        }));

        this.$emit("update", { id: this.index, condition: new_condition });
      },

      toggleMatchingType() {
        this.$emit('toggle-matching-type');
      },

      toggleOwnMatchingType() {
        let new_condition = clone(this.condition);

        this.$set(new_condition, 'type',  this.condition.type === 'AND' ? 'OR' : 'AND');

        this.$emit('update', { id: this.index, condition: new_condition });
      },

      removeSelf() {
        this.$emit('remove-condition', this.condition);
      },
    },
  }
</script>

<style lang="scss" scoped>
  .query-group {
    background-color: #ddd;
    border: 1px solid #bbb;
    border-radius: 4px;
    padding: 10px;
    margin: 10px;
    box-shadow: 0 2px 4px rgba(163,136,190,0.5);
  }
  
  .query-group-actions {
    margin-top: 10px;

    &:first-child {
      margin-top: 0;
      margin-bottom: 10px;
    }
  }

  .query-group-dark {
    background-color: #ccc;
  }

  .query-elements {
    margin-top: 10px;
  }
</style>

