<template>
  <div :class="allFieldsetClasses">
    <label
      :for="id_attr"
      :class="labelClasses"
    >
      <slot name="label">{{ label }}</slot>
    </label>
    <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
    <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
    <div :class="controlClasses">
      <slot>
        <v-map
          :zoom=zoom
          :center="center"
          @l-zoomend="onZoomEnd"
          @l-click="onClickMap"
        >
          <v-tilelayer :url="tile_url"></v-tilelayer>
          <v-marker :lat-lng="location"></v-marker>
        </v-map>
      </slot>
    </div>
    <div v-if="show_coord_fields" class="columns is-clearfix">
      <div class="column">
        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label :for="id_attr + '-lat'" class="label">Lat</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="control">
                <input type="text" class="input" :value="location.lat" @change="onChangeLat">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label :for="id_attr + '-lng'" class="label">Lng</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="control">
                <input type="text" class="input" :value="location.lng" @change="onChangeLng">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import BaseInput from '../mixins/base-input';
  import clone from 'lodash/clone';

  export default {
    mixins: [BaseInput],

    props: {
      use_location: {
        type: Boolean,
        default: true,
      },
      use_boundary_box: {
        type: Boolean,
        default: false,
      },
      use_zoom: {
        type: Boolean,
        default: false,
      },
      use_polygon: {
        type: Boolean,
        default: false,
      },
      use_circle_radius: {
        type: Boolean,
        default: false,
      },
      default_location: {
        type: Object,
        default: null,
      },
      default_zoom: {
        type: Number,
        defuault: 13,
      },
      show_coord_fields: {
        type: Boolean,
        default: false,
      },
    },

    data() {
      return {
        tile_url: '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        input_classes: [],
        center: {
          lat: 0,
          lng: 0,
        },
      };
    },

    mounted() {
      // Force a reload event else some tiles won't show.
      setTimeout(function() {
        window.dispatchEvent(new Event('resize'));
      }, 250);

      this.centerOnPoint(this.value.location);
    },

    methods: {
      onClickMap(e) {
        let value = this.newValue;

        value.location = {
          lng: e.latlng.lng,
          lat: e.latlng.lat,
        };

        this.centerOnPoint(value.location);

        this.$emit('input', value);
      },

      onZoomEnd(e) {
        let value = this.newValue;
        value.zoom = e.target.getZoom();
        this.$emit('input', value);
      },

      onChangeLat(e, val) {
        let value = this.newValue;
        let lat = parseFloat(e.target.value);

        if (isNaN(lat) || lat > 90 || lat < -90) {
          return;
        }

        value.location = {
          lng: value.location.lng,
          lat: lat,
        };

        this.centerOnPoint(value.location);

        this.$emit('input', value);
      },

      onChangeLng(e, val) {
        let value = this.newValue;
        let lng = parseFloat(e.target.value);

        if (isNaN(lng) || lng > 180 || lng < -180) {
          return;
        }

        value.location = {
          lng: lng,
          lat: value.location.lat,
        };

        this.centerOnPoint(value.location);

        this.$emit('input', value);
      },

      centerOnPoint(point) {
        this.center = clone(point);
      }
    },

    computed: {
      controlClasses() {
        return ['map-field-wrapper'];
      },

      location() {
        if (this.value === null || typeof this.value.location === 'undefined' || this.value.location === null) {
          if (this.default_location) {
            return this.default_location;
          }

          return {lat:0, lng:0}; // @todo pick some nice defaults
        }

        return this.value.location;
      },

      boundaryBox() {
        if (this.value === null || typeof this.value.boundaryBox === 'undefined' || !Array.isArray(this.value.boundaryBox)) {
          return [{lat: 0, lng: 0}, {lat: 0, lng:0}]; // @todo pick some nice defaults
        }

        return this.value.boundaryBox;
      },

      polygon() {
        if (this.value === null || typeof this.value.polygon === 'undefined' || !Array.isArray(this.value.polygon)) {
          return [];
        }

        return this.value.polygon;
      },

      zoom() {
        // Check whether zoom has been set some how
        if (this.value === null || typeof this.value.zoom === 'undefined' || parseInt(this.value.zoom, 10) !== this.value.zoom) {
          // If we have a location chosen, zoom in to show that
          if (this.location) {
            return 13;
          }

          // If there's a default zoom level, use that
          if (this.default_zoom) {
            return this.default_zoom;
          }

          // Otherwise just default to 13
          return 13;
        }

        return this.value.zoom;
      },

      circleRadius() {
        if (this.value === null || typeof this.value.circleRadius === 'undefined' || parseFloat(this.value.circleRadius) !== this.value.circleRadius) {
          return 0; // @todo pick some nice defaults
        }

        return this.value.circleRadius;
      },

      newValue() {
        return {
          location: this.location,
          boundaryBox: this.boundaryBox,
          zoom: this.zoom,
          polygon: this.polygon,
          circleRadius: this.circleRadius,
        };
      },
    },
  };
</script>