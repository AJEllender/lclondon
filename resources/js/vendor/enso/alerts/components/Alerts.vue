<template>
    <div class="alerts">
        <enso-alert v-for="alert in alerts" :alert="alert" :key="alert.id"></enso-alert>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        data() {
            return {
                alerts: this.$root.alerts.alerts
            }
        },
    };
</script>
