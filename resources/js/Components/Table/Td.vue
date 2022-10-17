<script setup>
import { computed } from "vue";

// dropshadow here make problem in showing submenu inside a table it shows the submenu nunder other element so we remove it if we have link with submenu inside a table

const props = defineProps({
    NoShadow: {
        type: Boolean,
        default: false,
    },
});

const viewShadow = computed(() => {
    return props.NoShadow ? "" : "drop-shadow-md";
});

const classes = `  py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500  ${viewShadow.value} `;
</script>

<template>
    <!-- <td
        class="py-1 px-4 capitalize border-b-solid tracking-none whitespace-nowrap text-slate-700 opacity-70"
    > -->

    <td :class="classes">
        <!-- :class="{
            'text-gray-400 cursor-not-allowed': isDisabled(link),
            'text-yellow-300 ': !isDisabled(link),
            'bg-blue-800 font-bold':link.active
        }
            " -->

        <div class="flex  py-1  ">
            <!-- ////////////////////////////////////////// remember here and  add text-center to yes no label  -->
            <!-- <div class="">  -->

                <div class="mx-1">
            <slot name="image" />
        </div>
            <div class="flex-col ">
                <h6 class="mb-0 leading-normal text-size-sm">
                    <slot />
                </h6>
                <p class="mb-0 leading-tight text-size-xs text-slate-400">
                    <slot name="second-line" />
                </p>
            </div>
        </div>
    </td>
</template>
