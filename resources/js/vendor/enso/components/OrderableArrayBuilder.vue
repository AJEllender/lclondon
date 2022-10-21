<template>
  <renderless-array-builder
    class="array-builder array-builder--orderable"
    :add-on-enter="addOnEnter"
    :allow-duplicates="allowDuplicates"
    :case-sensitive="caseSensitive"
    :formatter="formatter"
    :remove-on-backspace="removeOnBackspace"
    :items="items"
    @update="emitUpdate"
  >
    <div 
      slot-scope="{ items: array_items, inputProps, inputEvents, addItem, removeItem, updateItems, inputIsValid }"
    >
      <div class="array-builder__input" :class="[inputIsValid ? 'input-group' : ''] ">
        <input type="text"
          class="input"
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
      <sortable-list 
        :items="array_items"
        @update="emitUpdate"
        class="array-builder__list"
      >
        <ul slot-scope="{ items: ordered_array_items }">
          <sortable-item v-for="array_item in ordered_array_items" :key="array_item">
            <li class="array-builder__item">
              <sortable-handle class="flex-static drag-handle"></sortable-handle>
              <span class="array-builder__content">{{ array_item }}</span>
              <delete-button 
                class="button-remove flex-static" 
                @click.prevent="removeItem(array_item)"
              ></delete-button>
            </li>
          </sortable-item>
        </ul>
      </sortable-list>
    </div>
  </renderless-array-builder>
</template>

<script>
  import DeleteButton from './DeleteButton.vue';
  import RenderlessArrayBuilder from './renderless/ArrayBuilder.vue';
  import SortableHandle from './SortableHandle.vue';
  import SortableItem from './renderless/SortableItem.vue';
  import SortableList from './renderless/SortableList.vue';

  export default {
    components: {
      DeleteButton,
      RenderlessArrayBuilder,
      SortableHandle,
      SortableItem,
      SortableList,
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
