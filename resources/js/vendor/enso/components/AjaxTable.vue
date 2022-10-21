<template>
  <div>
    <ajax-container
      :route="route"
      :data="formData"
    >
      <div slot-scope="{ items, currentPage, totalPages, totalItems, prevPage, nextPage, isLoading, setPage, pagination }">
        <div class="field">
          <label class="label">Search</label>
          <div class="control">
            <input class="input" :value="search" @input="setSearch($event.target.value)" @keypress.enter="updateSearch(setPage)">
          </div>
        </div>
        <div class="table-pager">
          <button class="button is-info" :disabled="isLoading || (currentPage === 1)" @click.prevent="prevPage">Prev</button>
          <span>Page {{ currentPage }} of {{ totalPages }} ({{ totalItems }} Users)</span>
          <button class="button is-info" :disabled="isLoading || (currentPage === totalPages)" @click.prevent="nextPage">Next</button>
        </div>
        <table v-if="items.length > 0" class="table is-fullwidth">
            <thead>
                <th v-for="(column, index) in columns" :key="index">
                  {{ column.title }}
                </th>
            </thead>
            <tbody>
                <tr v-for="item in items" :key="item.id">
                  <template v-for="(column, index) in columns">
                    <component :is="column.component" name="column.title" :value="item[column.name]" :key="index"></component>
                  </template>
                </tr>
            </tbody>
        </table>
        <div v-else class="crud-no-results">
            There are no Users to display.
        </div>

        <div class="table-pager">
          <div class="">
            <p class="control">
              <button class="button is-info" :disabled="isLoading || (currentPage === 1)" @click.prevent="prevPage">Prev</button>
            </p>
          </div>
          <div class="field has-addons">
            <p class="control" v-for="(link, index) in pagination" :key="index">
              <a class="button" :disabled="isLoading" v-if="link.name != currentPage" @click.prevent="link.action()">{{ link.name }}</a>
              <a class="button" v-else disabled>{{ link.name }}</a>
            </p>
          </div>
          <div class="">
            <p class="control">
              <button class="button is-info" :disabled="isLoading || (currentPage === totalPages)" @click.prevent="nextPage">Next</button>
            </p>
          </div>
        </div>
      </div>
    </ajax-container>
  </div>
</template>

<script>
  import AjaxContainer from './renderless/AjaxContainer.vue'
  import EnsoCellText from '../crud/components/cells/EnsoCellText.vue';
  import EnsoCellTextLink from '../crud/components/cells/EnsoCellTextLink.vue';

  export default {
    components: {
      AjaxContainer,
      EnsoCellText,
      EnsoCellTextLink,
    },

    props: {
      route: {
        required: true,
        type: String,
      },

      columns: {
        type: Array,
        default: () => {
          return [
            {
              name: 'name',
              title: 'name',
              component: 'enso-cell-text',
            },
          ];
        }
      },
    },

    data() {
      return {
        search: '',
      };
    },

    computed: {
      formData() {
        return {
          search: this.search,
        };
      },
    },

    methods: {
      setSearch(value) {
        this.$set(this, 'search', value);
      },

      updateSearch(setPageFunction) {
        setPageFunction(1);
      },
    },
  }
</script>

<style lang="scss" scoped>
  .table-pager {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
  }

  .field.has-addons {
    margin-bottom: 0;
  }
</style>