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
    sections: Array,
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

        <form class="grid grid-cols-4 gap-8"> 
            <InputGroup label="Name"
            v-model="filters.name" />
<InputGroup label="email"
            v-model="filters.email" />
<InputGroup label="phone"
            v-model="filters.phone" />

            <SelectGroup
            label="Active"
            v-model="filters.status"
            :items="[
                { id: 1, name: 'Yes' },
                { id: 0, name: 'No' },
            ]"
        />
        </form>
    </Card>
</template>