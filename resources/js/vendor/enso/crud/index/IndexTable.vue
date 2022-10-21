<template>
  <div>
    <form
      v-if="hasTableActions"
      :action="route"
      autocomplete="off"
      class="columns columns--align-bottom"
      method="GET"
      @submit.prevent
    >
      <div v-if="hasBulkActions" class="column is-4">
        <label class="label">Bulk Actions</label>
        <bulk-actions
          :actions="bulkActions"
          :selections="bulkSelections"
          :current-filters="filters"
          :values="selected_items"
          ref="BulkActions"
          @update="getItems"
        ></bulk-actions>
      </div>

      <div
        v-if="hasFiltering && !crud.nested"
        :class="'crud-search is-clearfix column is-' + (hasBulkActions ? '8' : '12')"
      >
        <label class="label has-text-right">Filters</label>

        <div v-if="hasItemFilters">
          <filter-set
            :filter-items="itemFilters"
            :current-filters="filters"
            @update="updateCurrentFilters"
            ref="Filters"
          ></filter-set>
        </div>

        <div v-if="isSearchable" class="field is-pulled-right">
          <div class="control">
            <input
              placeholder="Search"
              v-model="searchterm"
              class="input"
              @input="onSearchChange"
            />
          </div>
        </div>
      </div>
    </form>

    <pagination
      v-if="hasPagination"
      :page="page"
      :total-items="total_items"
      :per-page="per_page"
      @previous="goToPreviousPage"
      @next="goToNextPage"
      @go-to-page="goToPage"
      :show-number-buttons="false"
      :show-page-count="true"
    />
    <index-table-table-generic
      ref="table"
      v-if="items.length > 0"
      :crud="crud"
      :drag-options="dragOptions"
      :has-bulk-actions="hasBulkActions"
      :is-draggable="isDraggable"
      :is-orderable="isOrderable"
      :items="items"
      :selected-items="selected_items"
      :table-classes="tableClasses"
      :orderby="orderby"
      @orderby="onOrderBy"
      @action-response="onAxiosComplete"
      @action-error="onError"
      @change-selection="onChangeSelection"
      @dragend="onDragEnd"
      @change="onChange"
    />
    <div v-else-if="is_loading" class="crud-index-message">Loading...</div>
    <div v-else-if="error_loading" class="crud-index-message">
      There was a problem.
      <button class="button is-small" @click="getItems">
        <span>Try again</span>
        <span class="icon">
          <i class="fa fa-refresh"></i>
        </span>
      </button>
    </div>
    <div v-else class="crud-index-message">There are no {{ crud.name_plural }} to display.</div>
    <pagination
      v-if="hasPagination"
      :page="page"
      :total-items="total_items"
      :per-page="per_page"
      @previous="goToPreviousPage"
      @next="goToNextPage"
      @go-to-page="goToPage"
    />
  </div>
</template>

<script>
import swal from 'sweetalert2/dist/sweetalert2.js';
import draggable from 'vuedraggable';
import BulkActions from './BulkActions.vue';
import FilterSet from './FilterSet.vue';
import Pagination from '../components/Pagination.vue';
import assign from 'lodash/assign';
import clone from 'lodash/clone';
import debounce from 'lodash/debounce';
import find from 'lodash/find';
import filter from 'lodash/filter';
import forEach from 'lodash/forEach';
import get from 'lodash/get';
import includes from 'lodash/includes';
import map from 'lodash/map';
import size from 'lodash/size';
import kebabCase from 'lodash/kebabCase';
import IndexTableTableGeneric from './IndexTableTableGeneric.vue';

export default {
  components: {
    BulkActions,
    draggable,
    IndexTableTableGeneric,
    FilterSet,
    Pagination,
  },

  mounted: function () {
    this.columns = enso.crud.columns;
    this.route = enso.crud.route;
    this.bulk_actions = get(enso.crud, 'bulk_actions', this.getEmptyBulkActions());
    this.setupFilters();

    let hash_page = this.getHashPageNumber();
    let last_page = parseInt(localStorage.getItem(this.pageCacheName), 10);

    if (!isNaN(hash_page) && typeof hash_page === 'number') {
      this.setPage(hash_page);
    } else if (!isNaN(last_page) && typeof last_page === 'number') {
      this.setPage(last_page);
    } else {
      this.setPage(1);
    }

    this.getItems();

    window.addEventListener('hashchange', this.onHashChange);
  },

  data: function () {
    return {
      items: [],
      crud: enso.crud,
      orderby: enso.crud.orderby,
      order: enso.crud.order,
      order_column: enso.crud.order_column,
      is_loading: true,
      error_loading: false,
      paginate: enso.crud.paginate,
      per_page: enso.crud.per_page,
      max_page_numbers: 6,
      total_items: 0,
      page: 1,
      searchterm: '',
      bulk_actions: {
        actions: {},
        selections: {},
      },
      selected_items: [],
      item_filters: {},
      filters: {},
      route: '',
    };
  },

  computed: {
    // True if there is an order column set and drag-and-drop
    // is potentially available. This may be true but drag and
    // drop may not be available, e.g. if the index is currently
    // ordered by a different column.
    isOrderable() {
      return this.order_column !== null;
    },

    // True if drag-and-drop is currently useable.
    isDraggable() {
      return this.isOrderable && this.orderby === this.order_column;
    },

    dragOptions() {
      return {
        disabled: !this.isDraggable,
        handle: '.drag-handle',
      };
    },

    tableClasses() {
      return {
        'is-loading': this.is_loading,
        'is-loaded': !this.is_loading,
      };
    },

    hasBulkActions() {
      return !!size(this.bulkActions);
    },

    bulkActions() {
      let actions = get(this.bulk_actions, 'actions', {});
      if (Array.isArray(actions)) {
        actions = {};
      }
      return actions;
    },

    bulkSelections() {
      let selections = get(this.bulk_actions, 'selections', {});
      if (Array.isArray(selections)) {
        selections = {};
      }
      return selections;
    },

    isSearchable() {
      return get(this.crud, 'searchable', false);
    },

    hasItemFilters() {
      return !!size(this.itemFilters);
    },

    hasFiltering() {
      return this.hasItemFilters || this.isSearchable;
    },

    itemFilters() {
      let selections = this.item_filters;
      if (Array.isArray(selections)) {
        selections = {};
      }
      return selections;
    },

    hasPagination() {
      return !!this.crud.paginate;
    },

    hasTableActions() {
      return this.hasBulkActions || this.hasFiltering;
    },

    pageCacheName() {
      return 'enso-page-' + kebabCase(this.crud.name_singular);
    },

    orderColumn() {
      return find(this.crud.columns, (column) => {
        return column.name === this.order_column;
      });
    },
  },

  methods: {
    getEditUrl(item) {
      return this.route + '/' + item.id + '/edit';
    },

    onOrderBy(column) {
      // column is an object and order_column is a string so we need to
      // treat it as a special case
      if (column === this.order_column) {
        this.order = 'desc';
        this.orderby = this.order_column;
        this.getItems();
        return;
      }

      if (!column.orderable_by || this.is_loading) {
        return false;
      }

      if (this.orderby === column.orderable_by) {
        this.order = this.order === 'desc' ? 'asc' : 'desc';
      } else {
        this.orderby = column.orderable_by;
        this.order = 'asc';
      }

      this.getItems();
    },

    onChange(item) {
      this.getItems();
    },

    getItems() {
      this.is_loading = true;
      this.error_loading = false;

      var params = {
        orderby: this.orderby,
        order: this.order,
        table: true,
      };

      // Use Clone so that we don't get size from it being an observer.
      if (size(clone(this.filters)) > 0) {
        params.filters = assign({}, this.filters);
      }

      if (this.paginate) {
        params.per_page = this.per_page;
        params.page = this.page;
      }

      if (this.crud.searchable && this.searchterm) {
        params.search = this.searchterm;
      }

      this.axios
        .get(this.route, {
          params: params,
        })
        .then(this.onResponse)
        .catch(this.onError);
    },

    onResponse(response) {
      if (response.data.status !== 'success') {
        if (response.data.message) {
          throw response.data.message;
        } else {
          throw 'An unknown error occurred.';
        }
      }

      this.selected_items = filter(
        map(response.data.data.items, function (item) {
          return item.id;
        }),
        (item) => {
          return includes(this.selected_items, item);
        }
      );

      this.$nextTick(function () {
        if (this.$refs.table) {
          this.$refs.table.checkToggleAll();
        }
      });

      this.items = response.data.data.items;
      this.total_items = response.data.data.total;
      this.is_loading = false;
      this.request_id = null;
      this.scrollToTop();
    },

    onError(error) {
      this.request_id = null;

      if (get(error, 'response.status', false) === 422) {
        if (get(error, 'response.data.errors')) {
          this.errors = get(error, 'response.data.errors', []);
        } else {
          this.errors = get(error, 'response.data', []);
        }
      } else {
        let error_msg = get(error, 'response.data.message', error.toString());

        swal({
          title: 'Oops...',
          text: 'Something went wrong! ' + error_msg,
          type: 'error',
        });
      }

      this.is_loading = false;
      this.error_loading = true;
    },

    updateOrder(item, change) {
      if (!change) {
        return;
      }

      change = this.order.toLowerCase() === 'desc' ? -change : change;

      if (this.paginate) {
        this.page = 1;
      }

      this.axios
        .post(this.crud.route + '/' + item.id + '/reorder', {
          change: change,
        })
        .then(
          function (response) {
            this.getItems();
          }.bind(this)
        )
        .catch(function (error) {
          swal({
            title: 'Oops...',
            text: error,
            type: 'error',
          });
        });
    },

    onAxiosComplete(response) {
      if (response.data.status === 'success') {
        this.getItems();
      } else if (response.data.status === 'error') {
        swal({
          title: 'Oops!',
          text: response.data.error,
          type: 'error',
        });
      } else {
        swal({
          title: 'Oops!',
          text: 'Something went wrong. Please try again.',
          type: 'error',
        });
      }
    },

    onDragEnd(e) {
      this.updateOrder(this.items[e.newIndex], e.newIndex - e.oldIndex);
    },

    getHashPageNumber() {
      return parseInt(window.location.hash.replace('#', ''), 10);
    },

    onHashChange() {
      let page = this.getHashPageNumber();

      if (!isNaN(page)) {
        this.goToPage(page);
      }
    },

    goToNextPage() {
      this.setPage(this.page + 1);
      this.getItems();
    },

    goToPreviousPage() {
      this.setPage(Math.max(0, this.page - 1));
      this.getItems();
    },

    goToFirstPage() {
      this.goToPage(1);
    },

    goToLastPage() {
      this.goToPage(this.lastPage);
    },

    goToPage(page) {
      this.setPage(page);
      this.getItems();
    },

    setPage(page) {
      this.page = parseInt(page, 10);
      window.location.hash = this.page;
      localStorage.setItem(this.pageCacheName, this.page);
    },

    onSearchChange: debounce(function () {
      this.page = 1;
      this.getItems();
    }, 500),

    onChangeSelection(selection) {
      this.selected_items = selection;
    },

    getEmptyBulkActions() {
      return {
        actions: {},
        selections: {},
      };
    },

    setupFilters() {
      this.item_filters = get(enso.crud, 'item_filters', {});

      forEach(this.item_filters, (filter_item, name) => {
        let default_value = get(filter_item, 'default', undefined);
        if (default_value !== undefined) {
          this.filters[name] = default_value;
        }
      });
    },

    updateCurrentFilters(value) {
      this.setPage(1);
      this.filters = value;
      this.getItems();
    },

    scrollToTop() {
      window.scroll({
        top: 0,
        left: 0,
        behavior: 'smooth',
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.columns--align-bottom {
  align-items: flex-end;
}

.index-actions .field.has-addons {
  justify-content: flex-end;
}
</style>
