<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import Edit from "@/admin/Components/Icons/Edit.vue";
import Trash from "@/admin/Components/Icons/Trash.vue";

defineProps({
    editLink: {
        type: String,
        default: () => "", 
    },
    showEdit: {
        type: Boolean,
        default: () => true,
    },
    showDelete: {
        type: Boolean,
        default: () => true, 
    },
});
</script> 

<template>
    <div class="flex items-center space-x-3">
        <button v-if="showEdit" class="  text-yellow-600 mx-2"> 
            <Link :href="editLink">
            <Edit class="w-4 h-4" />
            </Link>
        </button>
        <button v-if="showDelete" class=" text-red-700"
                @click="$emit('deleteClicked', $event)">
            <Trash class="w-4 h-4" />
        </button>
    </div>
</template>