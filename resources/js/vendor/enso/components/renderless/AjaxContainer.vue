<script>
  import axios from 'axios';
  import get from 'lodash/get';
  import assign from 'lodash/assign';
  import filter from 'lodash/filter';

  // Without this, Laravel won't recognise axios requests as AJAX
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

  export default {
    props: {
      route: {
        required: true,
        type: String,
      },

      method: {
        type: String,
        default: 'GET',
      },

      data: {
        type: Object,
        default: () => {
          return {};
        },
      },

      initialLoad: {
        type: Boolean,
        default: true,
      },

      perPage: {
        type: Number,
        default: 25,
      },

      orderBy: {
        type: String,
        default: 'id',
      },

      order: {
        type: String,
        default: 'desc',
      },
    },

    data() {
      return {
        items: [],
        total_items: 0,
        current_page: 1,
        is_loading: false,
      };
    },

    mounted() {
      if (this.initialLoad) {
        this.getItems();
      }
    },

    computed: {
      containerParams() {
        return {
          page: this.current_page,
          per_page: this.perPage,
          orderby: this.orderBy,
          order: this.order,
        };
      },

      pagination() {
        return filter([
          this.getFirstPageLink(),
          this.getRelativePageLink(-3),
          this.getRelativePageLink(-2),
          this.getRelativePageLink(-1),
          this.getRelativePageLink(0),
          this.getRelativePageLink(1),
          this.getRelativePageLink(2),
          this.getRelativePageLink(3),
          this.getLastPageLink(),
        ]);
      },

      lastPage() {
        return Math.ceil(this.total_items / this.perPage);
      }
    },

    methods: {
      previousPage() {
        this.current_page = this.current_page - 1;
        this.getItems();
      },

      setPage(page_number) {
        this.current_page = page_number,
        this.getItems();
      },

      nextPage() {
        this.current_page = this.current_page + 1;
        this.getItems();
      },

      getFirstPageLink() {
        if (this.current_page <= 1) {
          return null;
        }

        return {
          name: 'First Page',
          action: () => {
            this.setPage(1);
          }
        };
      },

      getRelativePageLink(offset) {
        let new_page = offset + this.current_page;
        
        if (
          new_page > this.lastPage
          || new_page <= 0
        ) {
          return null;
        }

        return {
          name: "" + new_page,
          action: () => {
            this.setPage(new_page);
          }
        };
      },

      getLastPageLink() {
        if (this.current_page >= this.lastPage) {
          return null;
        }

        return {
          name: 'Last Page',
          action: () => {
            this.setPage(this.lastPage);
          }
        };
      },

      getItems() {
        if (this.is_loading) {
          return;
        }

        this.$set(this, 'is_loading', true);
        
        let params = assign({}, this.containerParams, this.data);
        
        axios.request({
            url: this.route,
            method: this.method,
            params: params,
          })
          .then(this.onResponse)
          .catch(this.onError)
      },

      onResponse(response) {
        this.$set(this, 'items', get(response, 'data.items', []));
        this.$set(this, 'total_items', get(response, 'data.total', 0));
        this.$set(this, 'is_loading', false);
        this.$emit('update-success', response);
      },

      onError(error) {
        this.$set(this, 'is_loading', false);
        this.$emit('update-error', error);
      },
    },

    render: function(createElement) {
      if (get(this.$scopedSlots, 'default', null)) {
        return this.$scopedSlots.default({
          items: this.items,
          totalItems: this.total_items,
          currentPage: this.current_page,
          totalPages: Math.max(this.lastPage, 1),
          refresh: this.getItems,
          firstPage: () => {
            this.setPage(1);
          },
          prevPage: this.previousPage,
          setPage: (page_number) => {
            this.setPage(page_number);
          },
          nextPage: this.nextPage,
          lastPage: () => {
            this.setPage(this.lastPage);
          },
          pagination: this.pagination,
          isLoading: this.is_loading,
        });
      } else {
        return createElement('div', 'You must add a slot-scope to the root element of this sortable list.');
      }
    },
  }
</script>
