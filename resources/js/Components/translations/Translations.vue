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
    <div class=" hover:text-gray-300 text-gray-600">
        {{ sidebarlabel }}
    </div>
</template>
