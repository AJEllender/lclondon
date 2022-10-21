import Vue from 'vue';

// Index Tables
import IndexTable from './crud/index/IndexTable.vue';
import EnsoCellBoolean from './crud/components/cells/EnsoCellBoolean.vue';
import EnsoCellPublish from './crud/components/cells/EnsoCellPublish.vue';
import EnsoCellText from './crud/components/cells/EnsoCellText.vue';
import EnsoCellTextLink from './crud/components/cells/EnsoCellTextLink.vue';
import EnsoCellThumbnail from './crud/components/cells/EnsoCellThumbnail.vue';

// Create/Edit Tables
import FormWrapper from './crud/components/forms/form-wrapper.vue';
import Form from './crud/components/forms/form.vue';
import Section from './crud/components/forms/section.vue';
import EnsoCrudTree from './crud/components/tree/EnsoCrudTree.vue';
import EnsoFieldAmazonProduct from './crud/components/fields/EnsoFieldAmazonProduct.vue';
import EnsoFieldAudioUpload from './crud/components/fields/EnsoFieldAudioUpload.vue';
import EnsoFieldCheckbox from './crud/components/fields/EnsoFieldCheckbox.vue';
import EnsoFieldCustomHtml from './crud/components/fields/EnsoFieldCustomHtml.vue';
import EnsoFieldDate from './crud/components/fields/EnsoFieldDate.vue';
import EnsoFieldDateTime from './crud/components/fields/EnsoFieldDateTime.vue';
import EnsoFieldDivider from './crud/components/fields/EnsoFieldDivider.vue';
import EnsoFieldEmail from './crud/components/fields/EnsoFieldEmail.vue';
import EnsoFieldFileUpload from './crud/components/fields/EnsoFieldFileUpload.vue';
import EnsoFieldFileUploadResumable from './crud/components/fields/EnsoFieldFileUploadResumable.vue';
import EnsoFieldFlexibleContent from './crud/components/fields/EnsoFieldFlexibleContent.vue';
import EnsoFieldGallery from './crud/components/fields/EnsoFieldGallery.vue';
import EnsoFieldImageUpload from './crud/components/fields/EnsoFieldImageUpload.vue';
import EnsoFieldList from './crud/components/fields/EnsoFieldList.vue';
import EnsoFieldMap from './crud/components/fields/EnsoFieldMap.vue';
import EnsoFieldMultiSelect from './crud/components/fields/EnsoFieldMultiSelect.vue';
import EnsoFieldOrderableRelationship from './crud/components/fields/EnsoFieldOrderableRelationship.vue';
import EnsoFieldPassword from './crud/components/fields/EnsoFieldPassword.vue';
import EnsoFieldRadio from './crud/components/fields/EnsoFieldRadio.vue';
import EnsoFieldRandomText from './crud/components/fields/EnsoFieldRandomText.vue';
import EnsoFieldRelationship from './crud/components/fields/EnsoFieldRelationship.vue';
import EnsoFieldSelect from './crud/components/fields/EnsoFieldSelect.vue';
import EnsoFieldSelectableUrl from './crud/components/fields/EnsoFieldSelectableUrl.vue';
import EnsoFieldSliderBasic from './crud/components/fields/EnsoFieldSliderBasic.vue';
import EnsoFieldSlug from './crud/components/fields/EnsoFieldSlug.vue';
import EnsoFieldStaticText from './crud/components/fields/EnsoFieldStaticText.vue';
import EnsoFieldText from './crud/components/fields/EnsoFieldText.vue';
import EnsoFieldTextarea from './crud/components/fields/EnsoFieldTextarea.vue';
import EnsoFieldVideoEmbed from './crud/components/fields/EnsoFieldVideoEmbed.vue';
import EnsoFieldVideoUpload from './crud/components/fields/EnsoFieldVideoUpload.vue';
import EnsoFieldWysiwyg from './crud/components/fields/EnsoFieldWysiwyg.vue';
import MediaBrowser from './crud/components/media/MediaBrowser.vue';

import OutsideClick from './crud/directives/OutsideClick';

import Vue2Leaflet from 'vue2-leaflet';
Vue.component('v-map', Vue2Leaflet.Map);
Vue.component('v-tilelayer', Vue2Leaflet.TileLayer);
Vue.component('v-marker', Vue2Leaflet.Marker);

import Quill from './crud/components/Quill';
Vue.use(Quill);

Vue.component('enso-crud-form-wrapper', FormWrapper);
Vue.component('enso-crud-form', Form);
Vue.component('enso-crud-index-table', IndexTable);
Vue.component('enso-crud-section', Section);
Vue.component('enso-cell-boolean', EnsoCellBoolean);
Vue.component('enso-cell-publish', EnsoCellPublish);
Vue.component('enso-cell-text', EnsoCellText);
Vue.component('enso-cell-text-link', EnsoCellTextLink);
Vue.component('enso-cell-thumbnail', EnsoCellThumbnail);
Vue.component('enso-crud-tree', EnsoCrudTree);
Vue.component('enso-field-amazon-product', EnsoFieldAmazonProduct);
Vue.component('enso-field-audio-upload', EnsoFieldAudioUpload);
Vue.component('enso-field-checkbox', EnsoFieldCheckbox);
Vue.component('enso-field-custom-html', EnsoFieldCustomHtml);
Vue.component('enso-field-date', EnsoFieldDate);
Vue.component('enso-field-datetime', EnsoFieldDateTime);
Vue.component('enso-field-divider', EnsoFieldDivider);
Vue.component('enso-field-email', EnsoFieldEmail);
Vue.component('enso-field-file-upload', EnsoFieldFileUpload);
Vue.component('enso-field-file-upload-resumable', EnsoFieldFileUploadResumable);
Vue.component('enso-field-flexible-content', EnsoFieldFlexibleContent);
Vue.component('enso-field-gallery', EnsoFieldGallery);
Vue.component('enso-field-image-upload', EnsoFieldImageUpload);
Vue.component('enso-field-list', EnsoFieldList);
Vue.component('enso-field-multi-select', EnsoFieldMultiSelect);
Vue.component('enso-field-map', EnsoFieldMap);
Vue.component('enso-field-orderable-relationship', EnsoFieldOrderableRelationship);
Vue.component('enso-field-password', EnsoFieldPassword);
Vue.component('enso-field-radio', EnsoFieldRadio);
Vue.component('enso-field-random-text', EnsoFieldRandomText);
Vue.component('enso-field-relationship', EnsoFieldRelationship);
Vue.component('enso-field-select', EnsoFieldSelect);
Vue.component('enso-field-selectable-url', EnsoFieldSelectableUrl);
Vue.component('enso-field-slider-basic', EnsoFieldSliderBasic);
Vue.component('enso-field-slug', EnsoFieldSlug);
Vue.component('enso-field-static-text', EnsoFieldStaticText);
Vue.component('enso-field-text', EnsoFieldText);
Vue.component('enso-field-textarea', EnsoFieldTextarea);
Vue.component('enso-field-video-embed', EnsoFieldVideoEmbed);
Vue.component('enso-field-video-upload', EnsoFieldVideoUpload);
Vue.component('enso-field-wysiwyg', EnsoFieldWysiwyg);
Vue.component('media-browser', MediaBrowser);

Vue.directive('outside-click', OutsideClick);

// Prevent a drag-and-dropped file from loading in the browser
// This prevents work being lost if the file uploader is missed
window.addEventListener(
  'dragover',
  function (e) {
    e = e || event;
    e.preventDefault();
  },
  false
);
window.addEventListener(
  'drop',
  function (e) {
    e = e || event;
    e.preventDefault();
  },
  false
);
