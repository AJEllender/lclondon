<template>
    <div :class="alert.getClasses()" v-if="visible">
        <button class="delete" @click="visible = false"></button>
        <div v-html="alert.message"></div>
    </div>
</template>

<script>
export default {
    props: ['type', 'classes', 'alert'],

    data() {
        return {
            visible: true
        }
    },
};
</script>
