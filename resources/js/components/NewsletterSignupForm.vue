<template>
  <div>
    <form
      class="flex flex-col sm:flex-row group"
      :class="classes"
      action="#"
      @submit.prevent="onSubmit"
      :disabled="sending"
      v-if="!sent"
    >
      <input type="hidden" name="type" value="signupform" />
      <input
        :id="id + '-email'"
        type="email"
        class="grow shrink inline-block px-4 py-3 border-2 border-orange-500 group-hover:border-orange-700"
        placeholder="Email address..."
        required
        v-model="email"
      >
      <button
        type="submit"
        class="font-title font-bold grow-0 shrink-0 inline-block px-4 button bg-orange-500 group-hover:bg-orange-700 text-white"
        :disabled="sending"
      >
        Join the Newsletter
      </button>
    </form>
    <div
      v-else
      :class="successClasses"
    >
      {{ successMessage }}
    </div>
  </div>
</template>

<script>

export default {
  props: {
    classes: {
      type: String,
      default: '',
    },

    csrfToken: {
      type: String,
      required: true,
    },

    submitRoute: {
      type: String,
      default: '/newsletters',
    },

    successClasses: {
      type: String,
      default: 'bg-blue-100 mt-4 p-4 text-center text-sm border-blue-400 border border-solid',
    },

    successMessage: {
      type: String,
      default: 'Thanks for signing up!',
    },
  },

  data() {
    return {
      email: '',
      errors: null,
      id: null,
      sending: false,
      sent: false,
    };
  },

  methods: {
    clearForm() {
      this.email = null;
    },

    onSubmit() {
      if (this.sending || this.sent) {
        return;
      }

      this.sending = true;
      this.errors = null;

      fetch(
        this.submitRoute,
        {
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": this.csrfToken,
          },
          method: 'POST',
          body: JSON.stringify({
            type: 'signupform',
            email: this.email,
          })
        }
      ).then((response) => {
          if (response.status === 200) {
            this.onSuccess();
            this.clearForm();
            return;
          }

          throw 'There was a problem';
      }).catch((error) => {
        let status = get(error, 'response.stastus');
        switch (status) {
          case 422:
            this.errors = error.response.data;
            break;
          case 500:
            this.errors = { error: ['There was a problem. Please try again.'] };
            break;
          case null:
          default:
            this.errors = { error: [error] };
            break;
        }
      }).finally(() => {
        this.sending = false;
      });
    },

    onSuccess() {
      this.sent = true;
    },
  },

  mounted() {
    this.id = this._uid
  }
};

</script>
