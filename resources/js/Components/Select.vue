<script setup>
import { computed, onMounted, ref } from "vue";

const props = defineProps({
    modelValue: {},
    items: Array,
    itemText: {
        type: String,
        default: "name",
    },
    itemValue: {
        type: String,
        default: "id",
    },
    withoutSelect: { 
        type: Boolean,
        default: false,
    },
});

defineEmits(["update:modelValue"]);

const options = computed(() => {
    
    if (props.withoutSelect) return props.items;

    return [
        { [props.itemText]: "Select", [props.itemValue]: "" },
        ...props.items,
    ];
});

const select = ref(null);

onMounted(() => {
    if (select.value.hasAttribute("autofocus")) {  
        select.value.focus();
    }
});
</script>

<template>
    <select :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            ref="select"
            class="border-gray-300 focus:border-indigo-300 py focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full block ">
        <option v-for="item in options" class=" text-slate-900"
                :key="item[itemValue]"
                :value="item[itemValue]">
            {{ item[itemText] }}  
            <!-- this line above has a response of showing option name in menu and selected one witch come from database -->
        </option>
    </select> 
</template>
