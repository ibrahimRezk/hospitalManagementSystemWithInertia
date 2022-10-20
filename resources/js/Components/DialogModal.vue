<script setup>
import Modal from './Modal.vue';

const emit = defineEmits(['close']);

defineProps({
    title: {
        type: String,
        default: '',
    },
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const close = () => {
    emit('close');
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class=" bg-white"> 
            <div class="text-lg bg-gray-200 py-2 px-3 shadow-sm">
                <slot name="title" />
            </div>

            <div class="mt-4 px-3">
                <slot name="content" />
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right ">
            <slot name="footer" />
        </div>
    </Modal>
</template>
