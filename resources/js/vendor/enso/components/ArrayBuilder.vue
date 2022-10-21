<template>
  <div>
    <renderless-array-builder
      :add-on-enter="addOnEnter"
      :allow-duplicates="allowDuplicates"
      :case-sensitive="caseSensitive"
      :formatter="formatter"
      :remove-on-backspace="removeOnBackspace"
      :items="items"
      @update="emitUpdate"
    >
      <div slot-scope="{ items: array_items, inputProps, inputEvents, addItem, removeItem, updateItems, inputIsValid }">
        <div class="array-builder__input" :class="[inputIsValid ? 'input-group' : ''] ">
          <input type="text"
            class="input"
            :class="inputClasses"
            v-bind="inputProps"
            v-on="inputEvents"
            :placeholder="placeholder"
          >
          <div 
            class="input-group-addon" 
            v-show="inputIsValid"
            @click="addItem"
          >+</div>
        </div>
        <ul class="array-builder__list">
          <li
            class="array-builder__item sortable-list-handle sortable-list-item"
            v-for="array_item in array_items" 
            :key="array_item"
          >
            <span class="array-builder__content">{{ array_item }}</span>
            <delete-button 
              class="button-remove flex-static" 
              @click.prevent="removeItem(array_item)"
            ></delete-button>
          </li>
        </ul>
      </div>
    </renderless-array-builder>
  </div>
</template>

<script>
  import DeleteButton from './DeleteButton.vue';
  import RenderlessArrayBuilder from './renderless/ArrayBuilder.vue';

  export default {
    components: {
      DeleteButton,
      RenderlessArrayBuilder,
    },

    props: {
      items: {
        default: () => {
          return []; 
        },
        type: Array
      },

      placeholder: {
        default: 'Enter options...',
        type: String,
      },

      inputClasses: {
        default: '',
        type: String,
      },

      addOnEnter: {
        required: false,
        default: true,
        type: Boolean,
      },

      removeOnBackspace: {
        required: false,
        default: true,
        type: Boolean,
      },

      allowDuplicates: {
        required: false,
        default: false,
        type: Boolean,
      },

      caseSensitive: {
        required: false,
        default: false,
        type: Boolean,
      },

      formatter: {
        required: false,
        type: Function,
        default: (value) => {
          return value.trim();
        }
      },      
    },

    methods: {
      emitUpdate(value) {
        this.$emit('update', value);
      }
    }
  }
</script>