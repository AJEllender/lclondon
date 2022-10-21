<script>
  import get from 'lodash/get';
  import filter from 'lodash/filter';
  import includes from 'lodash/includes';
  import indexOf from 'lodash/indexOf';
  import map from 'lodash/map';

  /**
   * Functionality to Add and remove items from an array,
   * based on the value of an input field.
   */
  export default {
    model: {
      prop: "items",
      event: "update",
    },

    props: {
      items: {
        type: Array,
        default: () => {
          return [];
        }
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

    data() {
      return {
        current_value: "",
      }
    },

    computed: {
      inputIsValidValue() {
        if (this.current_value.trim().length === 0) {
          return false;
        }

        if (this.allowDuplicates) {
          return true;
        }

        let test_value = this.formatter(this.current_value);
        let current_selections = filter(this.items);

        if (this.caseSensitive === false) {
          current_selections = map(current_selections, (item) => { return item.toLowerCase(); });
          test_value = test_value.toLowerCase();
        } 

        return (includes(current_selections, test_value) === false);
      },
    },
    
    methods: {
      /**
       * Adds the current input value to the array of items, if
       * it meets it's criteria
       */
      addItem() {
        // Do Nothing with when no value or value already present
        if (!this.inputIsValidValue) {
          return; 
        }

        let new_item = this.formatter(this.current_value);
        let new_value = [...this.items, new_item];
        
        this.$emit("update", new_value);
        this.clearCurrentValue();

        /**
         * Return the index and item so that the caller could
         * look up and modify the element after adding it
         */
        return {
          index: indexOf(new_value, new_item),
          item: new_item,
        };
      },

      /**
       * Child components may push updates to the array (such
       * as a sortable-list), so this function propagates
       * these updates to the parent
       */
      updateItems(new_items) {
        this.$emit("update", new_items);
      },

      /**
       * Removes the specified item from the array of items.
       * Does not affect the actual item itself
       */
      removeItem(item_to_remove) {
        this.$emit("update", this.items.filter((item) => {
          return item !== item_to_remove;
        }));
      },

      handleEnter() {
        if (this.addOnEnter) {
          this.addItem();
        }
      },
      
      handleBackspace() {
        if (this.current_value.length === 0 && this.removeOnBackspace) {
          this.$emit("update", this.items.slice(0, -1));
        }
      },

      clearCurrentValue() {
        this.current_value = '';
      }     
    },

    render() {
      if (get(this.$scopedSlots, 'default', null)) {
        return this.$scopedSlots.default({
          items: this.items,

          addItem: this.addItem,

          updateItems: (new_items) => {
            this.updateItems(new_items);
          },

          removeItem: (item_to_remove) => {
            this.removeItem(item_to_remove);
          },

          inputProps: {
            value: this.current_value,
          },

          inputEvents: {
            input: e => (this.current_value = e.target.value),
            keydown: e => {
              if (e.key === "Backspace") {
                this.handleBackspace();
              }
              if (e.key === "Enter") {
                e.preventDefault();
                this.handleEnter();
              }
            },
          },

          inputIsValid: this.inputIsValidValue,
        });
      } else {
        return createElement('div', 'You must add a slot-scope to the root element of this list builder.');
      }
    }
  }
</script>
