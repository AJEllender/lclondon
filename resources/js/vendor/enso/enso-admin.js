import 'path';

import Vue from 'vue';

import axios from 'axios'
import VueAxios from 'vue-axios'
import './enso-crud';
import Alerts from './alerts/Alerts';
import PortalVue from 'portal-vue';

Vue.use(VueAxios, axios);
Vue.use(PortalVue);

// Without this, Laravel won't recognise axios requests as AJAX
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Enso build-ins
import NavbarComponent from './navbar/EnsoNavbar.vue';
import AlertsComponent from './alerts/components/Alerts.vue';
import AlertComponent from './alerts/components/Alert.vue';
Vue.component('enso-navbar', NavbarComponent);
Vue.component('enso-alerts', AlertsComponent);
Vue.component('enso-alert', AlertComponent);

// Useful Enso Components
import AjaxTable from './components/AjaxTable.vue';
import ArrayBuilder from './components/ArrayBuilder.vue';
import PagePreviewer from './components/PagePreviewer.vue';
import QueryBuilder from './components/QueryBuilder.vue';
import QueryGroup from './components/QueryGroup.vue';
import QueryCondition from './components/QueryCondition.vue';
import ToggleInput from './components/ToggleInput.vue';

Vue.component('ajax-table', AjaxTable);
Vue.component('array-builder', ArrayBuilder);
Vue.component('page-previewer', PagePreviewer);
Vue.component('query-builder', QueryBuilder);
Vue.component('query-group', QueryGroup);
Vue.component('query-condition', QueryCondition);
Vue.component('toggle-input', ToggleInput);

enso.alerts = new Alerts(enso.alerts);

const App = {
  el: '#app',
  data: {
    alerts: enso.alerts
  },
};

export default App;
