<script setup>

import { computed } from "vue";
import { trans } from "laravel-vue-i18n";

const props = defineProps({
    label: {
        type: String,
        default: "",
    },
  
    active: Boolean, 
    default : false,
});

const sidebarlabel = computed(() => {
    return trans(`mainMenu.` + props.label);
});

const colorClasses = computed(() => { 
        return props.active ?  "text-yellow-50  " :  ""

}); 



</script>

<template>
    <div class=" group-hover:text-red-500"  :class="colorClasses" >
        {{ sidebarlabel }}
    </div>
</template>
