<template>
  <div :class="allFieldsetClasses">
    <label :for="id" :class="labelClasses" v-if="label">
      <slot name="label">{{ label }}</slot>
    </label>
    <ul v-if="value.length > 0">
      <li v-for="item in value" :key="getListKey(item)">
        <a v-if="item.url" :href="item.url" v-bind="getListAttributes(item)">{{ item.name }}</a>
        <div v-else>{{ item.id }}</div>
      </li>
    </ul>
    <div v-else class="list-field__empty-msg">Nothing here.</div>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';
import get from 'lodash/get';

export default {
  mixins: [BaseInput],

  methods: {
    getListAttributes(item) {
      let $attributes = get(item, 'attributes', []);

      if (get($attributes, 'target') === '_blank' && get($attributes, 'rel', null) === null) {
        $attributes.rel = 'noopener noreferrer';
      }

      return $attributes;
    },

    getListKey($item) {
      return $item.url !== 'undefined' ? $item.url + $item.name : $item.id;
    },
  },
};
</script>

<style>
.list-field__empty-msg {
  color: #bbb;
}
</style>
