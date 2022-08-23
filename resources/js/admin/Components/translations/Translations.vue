<script setup>

import { computed } from "vue";
import { trans } from "laravel-vue-i18n";

const props = defineProps({
    label: {
        type: String,
        default: "",
    },
});

const sidebarlabel = computed(() => {
    return trans(`mainMenu.` + props.label);


});
</script>

<template>
    <div>
        {{ sidebarlabel }}
    </div>
</template>
