<script>
  import ArrayBuilder from './ArrayBuilder.vue';
  import get from 'lodash/get';
  import filter from 'lodash/filter';
  import includes from 'lodash/includes';
  import indexOf from 'lodash/indexOf';
  import isFunction from 'lodash/isFunction';
  import map from 'lodash/map';
  import maxBy from 'lodash/maxBy';

  export default {
    extends: ArrayBuilder,

    props: {
      keyName: {
        default: 'key',
      },

      newItemKey: {
        default: null,
      },

      valueName: {
        default: 'value',
      },

      newItem: {
        default: null,
      },
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
        let current_selections = filter(map(this.items, (item) => { return item[this.valueName]; }));

        if (this.caseSensitive === false) {
          current_selections = map(current_selections, (item) => { return item.toLowerCase(); });
          test_value = test_value.toLowerCase();
        }

        return (includes(current_selections, test_value) === false);
      },
    },

    methods: {
      /**
       * Generates a new Item and adds it to the list of items.
       * You'll need to either pass in a function that returns 
       * an new item, or an object that represent the new item.
       */
      addItem(new_item_argument) {
        // Do Nothing with when no value or value already present
        if (!this.inputIsValidValue) {
          return; 
        }

        const new_item = this.createNewItem(new_item_argument);

        const new_value = [...this.items,  new_item];

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
       * Removes the specified item from the array of items.
       * Does not affect the actual item itself
       */
      removeItem(item_to_remove) {
        let new_array = filter(this.items, (item) => {
          return item[this.valueName] !== item_to_remove[this.valueName];
        });

        this.$emit("update", new_array);
      },

      /**
       * Generates a new item Id. This can be passed in as a function which
       * should return the new id, as the new id itself, or it can be 
       * generated based on the id's of existing items.
       */
      getNewItemKey() {
        if (isFunction(this.newItemKey)) {
          return this.newItemKey();
        } else if (this.newItemKey) {
          return this.newItemKey;
        }

        const max_id_object = maxBy(this.items, (item) => {
          return item[this.keyName];
        });

        if (!max_id_object) {
          return 1;
        } else {
          return max_id_object[this.keyName] + 1;
        }
      },

      /**
       * Creates a new item from the argument passed. This should be
       * a function that returns the new item. Otherwise, return something
       * that will 'work'.
       */
      createNewItem(new_item_argument) {
        const new_key = this.getNewItemKey();
        let new_item;

        if (isFunction(new_item_argument)) {
          new_item = new_item_argument(new_key, this.formatter(this.current_value));
        } else if (isFunction(this.newItem)) {
          new_item = this.newItem(new_key, this.formatter(this.current_value));
        } else {
          new_item = {
            [this.keyName]: new_key,
            [this.valueName]: this.formatter(this.current_value),
          };
        }

        return new_item;
      }, 
    },

    render: function(createElement) {
      if (get(this.$scopedSlots, 'default', null)) {
        return this.$scopedSlots.default({
          items: this.items,

          addItem: (new_item) => {
            return this.addItem(new_item);
          },

          updateItems: (new_items) => {
            return this.updateItems(new_items);
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
            }
          },

          inputIsValid: this.inputIsValidValue,
        });
      } else {
        return createElement('div', 'You must add a slot-scope to the root element of this list builder.');
      }
    },
  }
</script>