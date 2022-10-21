<template>
    <td
        :class="td_classes"
    >
        {{ value }}
    </td>
</template>

<script>
    import BaseCell from '../mixins/base-cell';

    export default {
        mixins: [BaseCell]
    };
</script>
