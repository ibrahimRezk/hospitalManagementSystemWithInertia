<script setup>
import { ref } from "vue";

import Button from "@/Components/Button.vue";
import FilterIcon from "@/Components/Icons/Filter.vue";

const props = defineProps({ 
    show: {
        type: Boolean,
        default: false,
    } 
})

const showFilters = ref(props.show ?? false);
</script>

<template>
    <div>
        <div class="flex items-center justify-start mx-5">
            <slot />

            <Button  v-if="$slots.filters"
                    class="mx-5"
                    color="red"
                    @click="showFilters=!showFilters">
                <FilterIcon class="w-20 h-4" />
            </Button>
        </div>

        <div v-if="showFilters"
            class="mt-2">
            <slot name="filters" />
        </div>
    </div>
</template>