// require('./bootstrap');

import Vue from 'vue';
import PortalVue from 'portal-vue'
import EnsoCarousel from 'enso-carousel';
import Gallery from './components/Gallery.vue';
import EventCalendar from './components/EventCalendar.vue';
import NewsletterSignupForm from './components/NewsletterSignupForm.vue';

import 'vue-cal/dist/vuecal.css'
import VueEasyLightbox from 'vue-easy-lightbox';
import MainMenu from './enso/MainMenu.js';

Vue.use(PortalVue)

new Vue({
  el: '#app',
  components: {
    NewsletterSignupForm,
    EnsoCarousel,
    EventCalendar,
    Gallery,
    MainMenu,
    VueEasyLightbox,
  },
});
