<template>
  <nav class="pagination is-centered" v-if="lastPageNumber > 1">
    <button type="button" class="pagination-previous" @click="previous" :disabled="page < 2">Previous</button>

    <p class="pagination-list has-text-centered has-text-grey-light" v-if="showPageCount">
        Page {{ page }} of {{ lastPageNumber }}
    </p>
    <ul class="pagination-list" v-else-if="showNumberButtons">
      <li><button type="button" class="pagination-link" :class="{'is-current': page === 1}" @click="firstPage" :disabled="page < 2">1</button></li>
      <li><span class="pagination-ellipsis" v-if="pageNumbers[0] !== 2">&hellip;</span></li>
      <li><button type="button" class="pagination-link" :class="{'is-current': page === i}" @click="goToPage(i)" v-for="i in pageNumbers" :key="i" :disabled="page === i">{{ i }}</button></li>
      <li><span class="pagination-ellipsis" v-if="pageNumbers[pageNumbers.length-1] !== lastPageNumber - 1">&hellip;</span></li>
      <li><button type="button" class="pagination-link" :class="{'is-current': page === lastPageNumber}" @click="lastPage" :disabled="page === lastPageNumber">{{ lastPageNumber }}</button></li>
    </ul>

    <button type="button" class="pagination-next" @click="next" :disabled="page === lastPageNumber">Next</button>
  </nav>
</template>

<script>
export default {
  props: {
    page: {
      type: Number,
      required: true,
    },
    totalItems: {
      type: Number,
      required: true,
    },
    perPage: {
      type: Number,
      required: true,
    },
    showPageCount: {
      type: Boolean,
      default: false,
    },
    showNumberButtons: {
      type: Boolean,
      default: true,
    },
    maxPageNumbers: {
      type: Number,
      default: 6,
    },
  },

  methods: {
    goToPage(page) {
      this.$emit('go-to-page', page);
    },

    previous() {
      this.$emit('previous');
    },

    next() {
      this.$emit('next');
    },

    firstPage() {
      this.$emit('go-to-page', 1)
    },

    lastPage() {
      this.$emit('go-to-page', this.lastPageNumber)
    },
  },

  computed: {
    pageNumbers() {
      if (!this.lastPageNumber) {
        return [];
      }

      let pages = [];
      let first_allowed = 2; // Page 1 always displays
      let last_allowed = this.lastPageNumber - 1; // As will the last page
      let start = Math.max(first_allowed, this.page - Math.floor((this.maxPageNumbers - 2) / 2));
      let max = Math.min(start + this.maxPageNumbers - 3, last_allowed);

      for (let i = start; i <= max; i++) {
        pages.push(i);
      }

      return pages;
    },

    lastPageNumber() {
      return Math.ceil(this.totalItems / this.perPage);
    },
  },
};
</script>
