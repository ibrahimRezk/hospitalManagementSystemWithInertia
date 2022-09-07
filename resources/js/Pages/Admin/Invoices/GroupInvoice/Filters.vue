<script setup>
import { ref, watch } from "vue";

import Card from "@/Components/Card/Card.vue";
import InputGroup from "@/Components/InputGroup.vue";
import SelectGroup from "@/Components/SelectGroup.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({}),
    },
    // roles: Array,
});

const emits = defineEmits(["update:modelValue"]); 

const filters = ref({ ...props.modelValue });

watch(
    filters,
    () => {
        emits("update:modelValue", filters.value);
    },
    {
        deep: true,
    }
);
</script>

<template>
    <Card class="mb-4 bg-gray-100 rounded-md drop-shadow-lg border-b-white ">
        <template #header>  
            Filters
        </template> 

        <form class="grid grid-cols-3 gap-8"> 
            <InputGroup label="Patient Name"
            v-model="filters.patient_name" />
            <InputGroup label="Service Name"
            v-model="filters.service_name" />
            
            
        </form>
    </Card>
</template>