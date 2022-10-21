<template>
    <td
        :class="td_classes"
    >
        <a
            :href="value.url"
            :target="value.target"
        >
            {{ value.text }}
        </a>
    </td>
</template>

<script>
    import BaseCell from '../mixins/base-cell';

    export default {
        mixins: [BaseCell],
    };
</script>
