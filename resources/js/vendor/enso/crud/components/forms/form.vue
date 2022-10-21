<template>
  <form method="POST" :action="action" @submit.prevent="submitForm" :autocomplete="autocomplete">
    <input type="hidden" name="_token" :value="csrfToken" />
    <div v-if="errors" class="notification is-danger content">
      <h2 class="subtitle is-5">Something's not quite right...</h2>
      <ul>
        <template v-for="error in errors">
          <li v-for="message in error" :key="message">{{ message }}</li>
        </template>
      </ul>
    </div>
    <div class="tabs">
      <ul>
        <li
          v-for="(section, index) in form.sections"
          :key="index"
          :class="{ 'is-active': isActive(section)}"
        >
          <a @click="showTab(section.name)">{{ section.label }}</a>
        </li>
      </ul>
    </div>
    <template v-for="(section, section_name) in form.sections">
      <component
        :key="section_name"
        :is="section.component"
        :section="section"
        :item="item"
        :language="language"
        v-show="isActive(section)"
      ></component>
    </template>
    <div class="crud-form-action-bar">
      <button
        type="submit"
        class="button is-success is-pulled-right"
        title="Save your changes"
        :class="{'is-loading': submitting}"
        :disabled="submitting"
      >Save</button>
      <button
        v-if="publishable && published"
        type="button"
        class="button is-danger is-pulled-right is-outlined"
        title="Save your changes and unpublish the item"
        :class="{'is-loading': submitting}"
        :disabled="submitting"
        @click.prevent="saveAndUnpublish"
      >Save and Unpublish</button>
      <button
        v-else-if="publishable && !published"
        type="button"
        class="button is-success is-pulled-right is-outlined"
        title="Save your changes and publish the item immediately"
        :class="{'is-loading': submitting}"
        :disabled="submitting"
        @click.prevent="saveAndPublish"
      >Save and publish</button>
      <a
        href="javascript:history.back()"
        class="button is-primary is-outlined is-pulled-right"
        title="Go back to the item list without saving"
      >Cancel</a>
    </div>
  </form>
</template>

<script>
import swal from 'sweetalert2/dist/sweetalert2.js';
import compact from 'lodash/compact';
import get from 'lodash/get';
import map from 'lodash/map';

export default {
  props: {
    csrfToken: {
      type: String,
      default: '',
    },
    action: {
      type: String,
      default: '/',
    },
    method: {
      type: String,
      default: 'POST',
    },
    language: {
      type: String,
      default: 'en',
    },
    autocomplete: {
      type: String,
      default: 'off',
    },
  },

  created() {
    this.active_tab = Object.keys(this.form.sections).map(
      (item) => this.form.sections[item]
    )[0].name;
  },

  data() {
    return {
      form: {},
      active_tab: null,
      item: enso.crud.item,
      form: enso.crud.form,
      submitting: false,
      errors: null,
      published: enso.item_is_published,
      publishable: enso.crud.publishable,
    };
  },

  methods: {
    showTab(index) {
      this.active_tab = index;

      // @todo this is a hack to make map components work if they're not
      // visible on page load. Other than passing events up and down the chain
      // I can't find a better way to do this.
      setTimeout(function () {
        window.dispatchEvent(new Event('resize'));
      }, 250);
    },

    isActive(section) {
      return this.form.sections[this.active_tab] === section;
    },

    submitForm() {
      this.save();
    },

    saveAndUnpublish() {
      this.save({
        publish_actions: {
          unpublish: true,
        },
      });
    },

    saveAndPublish() {
      this.save({
        publish_actions: {
          publish: true,
        },
      });
    },

    save(extra_data) {
      if (typeof extra_data === 'undefined') {
        extra_data = {};
      }

      this.submitting = true;
      this.errors = null;

      let data = Object.assign(this.item, extra_data);
      data.csrf_token = this.csrf_token;

      this.axios
        .request({
          data,
          method: this.method,
          url: this.action,
        })
        .then(this.onResponse)
        .catch(this.onError);
    },

    reloadContent(item, form, published) {
      this.item = item;
      this.form = form;
      this.published = published;
    },

    onResponse(response) {
      this.submitting = false;

      if (response.data.status === 'success') {
        swal({
          title: 'Success!',
          html: this.sweetAlertHtml('Your changes were saved successfully.', response),
          type: 'success',
          onOpen: (swal_elem) => {
            for (let i = 0; i < get(response, 'data.buttons', []).length; i++) {
              let button_elements = swal_elem.getElementsByClassName('js-swal-button-' + i);
              for (let j = 0; j < button_elements.length; j++) {
                button_elements[j].addEventListener(
                  'click',
                  () => {
                    let url = get(response, `data.buttons[${i}].url`, '');
                    let item = get(response, 'data.data.item');
                    let form = get(response, 'data.data.form');
                    let published = get(response, 'data.data.published');

                    if (item && (!url || url === window.location.href)) {
                      this.reloadContent(item, form, published);
                      swal.close();
                    } else {
                      window.location = get(response, `data.buttons[${i}].url`, '');
                    }
                  },
                  false
                );
              }
            }
          },
          allowOutsideClick: this.method === 'PATCH',
          allowEscapeKey: this.method === 'PATCH',
          showCancelButton: false,
          showConfirmButton: false,
        });
      } else if (response.data.status === 'error') {
        swal({
          title: 'Oops!',
          text: response.data.message,
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

    onError(error) {
      this.submitting = false;

      if (get(error, 'response.status', false) === 422) {
        if (get(error, 'response.data.errors')) {
          this.errors = get(error, 'response.data.errors', []);
        } else {
          this.errors = get(error, 'response.data', []);
        }

        window.scrollTo(0, 0);
      } else {
        swal({
          title: 'Oops...',
          text: get(error, 'response.data.message', 'Something went wrong! ' + error),
          type: 'error',
        });
      }
    },

    sweetAlertHtml(message, response) {
      let buttons = get(response, 'data.buttons', []);

      if (buttons.length === 0) {
        return message;
      }

      return (
        message +
        '<br><div class="crud-button-list">' +
        map(buttons, function (button, index) {
          let classes = compact([
            'button',
            get(button, 'class', ''),
            'js-swal-button-' + index,
          ]).join(' ');

          let icon = get(button, 'icon', '');

          if (icon.length > 0) {
            icon = '<i class="' + icon + '"></i>';
          }

          return (
            '<button type="button" role="button" tabindex="0" class="' +
            classes +
            '">' +
            icon +
            ' ' +
            get(button, 'label', '') +
            '</button>'
          );
        }).join('') +
        '</div>'
      );
    },
  },
};
</script>
