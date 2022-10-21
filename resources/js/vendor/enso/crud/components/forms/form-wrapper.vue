<template>
  <div>
    <div v-if="use_translations" class="level">
      <div class="level-left"></div>
      <div class="level-right">
        <div class="dropdown is-pulled-right is-right" :class="{'is-active': showLanguageDropdown}">
          <div class="dropdown-trigger">
            <button
              class="button"
              aria-haspopup="true"
              aria-controls="dropdown-menu"
              @click="showLanguageDropdown = !showLanguageDropdown"
            >
              <span>{{ languages[currentLanguage].name }}</span>
              <span class="icon is-small">
                <i class="fa fa-angle-down" aria-hidden="true"></i>
              </span>
            </button>
          </div>
          <div class="dropdown-menu" role="menu">
            <div class="dropdown-content">
              <a
                v-for="(language, code) in languages"
                :key="code"
                :href="'#'+code"
                class="dropdown-item"
                @click="chooseLanguage(code)"
              >{{ language.name }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <component
      :is="form_component"
      :action="action"
      :csrf-token="csrfToken"
      :method="method"
      :language="currentLanguage"
      :autocomplete="autocomplete"
    ></component>
  </div>
</template>

<script>
export default {
  props: ['action', 'csrfToken', 'method'],

  data() {
    return {
      showLanguageDropdown: false,
      currentLanguage: 'en',
      form_component: null,
      use_translations: enso.crud.use_translations,
      languages: enso.crud.languages,
      autocomplete: enso.crud.form.autocomplete,
    };
  },

  created() {
    this.form_component = enso.crud.form.component;
  },

  methods: {
    chooseLanguage(code) {
      this.currentLanguage = code;
      this.showLanguageDropdown = false;
    },
  },
};
</script>
