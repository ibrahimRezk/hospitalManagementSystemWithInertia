<script setup>
defineProps({
    isLoading: {
        type: Boolean,
        default: false,
    },
    noPadding: {
        type: Boolean,
        default: false, 
    },
});
</script>

<template> 
    <div class=""
    
    >
        <div v-if="isLoading" class="w-full bg-gray-200 h-1">
            <div class="bg-blue-600 h-1 animate-progress"></div>
        </div> 

        <div
            v-if="$slots.header"
            class="px-6 py-2 font-bold bg-gray-800 border-b text-white rounded-md shadow-md"
        >
            <slot name="header"></slot>
        </div>

        <div class="" :class="[noPadding ? '' : 'p-6']">
            <slot />
        </div>
    </div>
</template>
