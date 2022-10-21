<template>
  <div class="enso-media-list">
    <div class="field">
      <div class="control">
        <label class="label" for="searchTerm">Search</label>
        <input
          id="searchTerm"
          type="text"
          class="input"
          name="search"
          v-model="search"
          @keyup="filterList"
        />
      </div>
    </div>
    <div class="notification is-warning" v-if="files.length === 0 && !isLoading">No files.</div>
    <div v-else-if="files.length === 0 && isLoading" :style="{ opacity: isLoading ? 0.6 : 1 }">
      <div v-for="i in perPage" :key="i" class="enso-file enso-file--fake"></div>
    </div>
    <div v-else :style="{ opacity: isLoading ? 0.6 : 1 }" class="enso-file-list">
      <file-preview
        v-for="file in files"
        :filename="file.filename"
        :key="file.id"
        :id="file.id"
        :preview="file.preview"
        @delete="onDelete(file)"
        @click="onClickFile(file)"
      ></file-preview>
    </div>
    <pagination
      :page="page"
      :total-items="totalItems"
      :per-page="perPage"
      @previous="goToPreviousPage"
      @next="goToNextPage"
      @go-to-page="goToPage"
    ></pagination>

    <hr class="hr" />

    <portal to="modals" v-if="showFileDetailsModal">
      <file-details-modal
        :show="showFileDetailsModal"
        :file="fileForModal"
        @close="showFileDetailsModal = false"
        @deleted="getFiles"
      ></file-details-modal>
    </portal>
  </div>
</template>

<script>
import FilePreview from './FilePreview';
import swal from 'sweetalert2';
import FileDetailsModal from './FileDetailsModal.vue';
import Pagination from '../Pagination.vue';
import debounce from 'lodash/debounce';

export default {
  components: {
    FilePreview,
    FileDetailsModal,
    Pagination,
  },

  props: {
    // The type of media list to show. Can be 'manager' or 'picker'.
    mode: {
      type: String,
      default: 'manager',
    },

    overrideOrderBy: {
      type: String,
      default: null,
    },

    overrideOrder: {
      type: String,
      default: null,
    },

    overridePerPage: {
      type: Number,
    },
  },

  data() {
    return {
      files: [],
      page: 1,
      perPage: this.overridePerPage ? this.overridePerPage : enso.crud.per_page,
      orderBy: this.overrideOrderBy ? this.overrideOrderBy : enso.crud.orderby,
      order: this.overrideOrder ? this.overrideOrder : enso.crud.order,
      search: '',
      isLoading: false,
      totalItems: 0,
      showFileDetailsModal: false,
      fileForModal: null,
    };
  },

  created() {
    this.getFiles();
  },

  methods: {
    closeModal() {},

    filterList: debounce(function () {
      this.page = 1;
      this.getFiles();
    }, 250),

    getFiles() {
      this.isLoading = true;

      this.axios
        .get('/admin/media', {
          params: {
            page: this.page,
            per_page: this.perPage,
            orderby: this.orderBy,
            order: this.order,
            search: this.search,
            table: true,
          },
        })
        .then((response) => {
          this.files = response.data.data.items;
          this.totalItems = response.data.data.total;
        })
        .catch((error) => {
          swal('Oops...', 'Something went wrong!', 'error');
        })
        .then(() => {
          this.isLoading = false;
        });
    },

    // @todo this is duplicated in FileDetails.vue. Once we start using VueX
    // this can go away completely
    onDelete(file) {
      swal({
        title: 'Are you sure?',
        text: 'You will not be able to undo this!',
        type: 'warning',
        confirmButtonText: 'Ok',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.axios
            .delete('/admin/media/delete/' + file.id)
            .then((response) => {
              if (response.data.status === 'fail') {
                swal({
                  title: 'Oops',
                  text: response.data.message,
                  type: 'error',
                });
              }

              this.getFiles();
            })
            .catch((error) => {
              //@todo this isn't showing
              swal({
                title: 'Oops',
                text: error.response.data.message,
                type: 'error',
              });
            });
        }
      });
    },

    goToNextPage() {
      this.setPage(this.page + 1);
      window.location.hash = this.page;
      this.getFiles();
    },

    goToPreviousPage() {
      this.setPage(this.page - 1);

      if (this.page < 1) {
        this.page = 1;
      }

      window.location.hash = this.page;
      this.getFiles();
    },

    goToFirstPage() {
      this.goToPage(1);
    },

    goToLastPage() {
      this.goToPage(this.lastPage);
    },

    goToPage(page) {
      this.setPage(page);
      this.getFiles();
    },

    setPage(page) {
      this.page = page;
      localStorage.setItem(this.pageCacheName, page);
    },

    onClickFile(file) {
      if (this.mode === 'manager') {
        this.fileForModal = file;
        this.showFileDetailsModal = true;
        return;
      }

      this.$emit('selected-files', [file]);
    },
  },
};
</script>
