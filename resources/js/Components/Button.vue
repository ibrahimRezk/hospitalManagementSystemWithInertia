<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({ 
    type: {
        type: String,
        default: "submit",
    },
    href: {
        type: String,
        default: "",
    },
    color: {
        type: String,
        default: "black",
    },
    small: {
        type: Boolean,
        default: false,
    },
});

const colorClasses = computed(() => { 
    return {
        black: "bg-gray-800 hover:bg-gray-700 active:bg-gray-900 focus:border-gray-900 focus:shadow-outline-gray text-white  shadow-md",
        red: "bg-red-600 hover:bg-red-500 active:bg-red-700 focus:border-red-700 focus:shadow-outline-red text-white",
        green: "bg-green-400 hover:bg-green-300 active:bg-green-700 focus:border-green-700 focus:shadow-outline-green text-black shadow-md",
        blue: "bg-sky-600 hover:bg-blue-500 active:bg-blue-700 focus:border-blue-700 focus:shadow-outline-blue text-white shadow-xl",
        white: "bg-gray-200 hover:bg-gray-50 active:bg-gray-50 focus:border-gray-500 focus:shadow-outline-gray text-black border border-black  font-bold drop-shadow-md",
        yellow: "bg-yellow-200 hover:bg-yellow-50 active:bg-red-500 focus:border-yellow-800 focus:shadow-outline-black text-black font-bold"
    }[props.color];
});

const sizeClasses = computed(() => { 
    return props.small ? "px-1 rounded" : "px-4 py-2 rounded-md";
});

const classes = `inline-flex items-center  border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none transition ease-in-out duration-150 ${colorClasses.value} ${sizeClasses.value}`;
</script>

<template>
    <Link v-if="href"
          :href="href"
          :class="classes">
    <slot />
    </Link>
    <button v-else
            :type="type"
            :class="classes">
        <slot />
    </button>
</template>
