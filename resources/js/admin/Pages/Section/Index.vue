<script setup>
import { onMounted, ref, watch } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Layout from "@/admin/Layouts/Authenticated.vue";
import Container from "@/admin/Components/Container.vue";
import Card from "@/admin/Components/Card/Card.vue";
import Table from "@/admin/Components/Table/Table.vue";
import Td from "@/admin/Components/Table/Td.vue";
import Actions from "@/admin/Components/Table/Actions.vue";
import Button from "@/admin/Components/Button.vue";
import Modal from '@/admin/Components/ConfirmationModal.vue'

import Label from "@/admin/Components/Label.vue";
import Input from "@/admin/Components/Input.vue";
import AddNew from "@/admin/Components/AddNew.vue";
import Filters from "./Filters.vue";

import useDeleteItem from "@/admin/Composables/useDeleteItem.js";
import useFilters from "@/admin/Composables/useFilters.js";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    items: {
        type: Object,
        default: () => ({}),
    },
    headers: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    routeResourceName: {
        type: String,
        required: true,
    },
    can: Object,
    // roles: Array,
});

const {
    close,
    deleteModal,
    itemToDelete,
    isDeleting,
    showDeleteModal,
    handleDeleteItem,
} = useDeleteItem({
    routeResourceName: props.routeResourceName,
});

const { filters, isLoading, isFilled } = useFilters({
    filters: props.filters,
    routeResourceName: props.routeResourceName,
});
</script>

<template>
    <Head :title="title" />

    <Layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ title }}
            </h2>
        </template>
        <Container>
            <AddNew :show="isFilled">
                <Button
                    v-if="can.create"
                    :href="route(`admin.${routeResourceName}.create`)"
                    >Add New</Button
                >

                <template #filters>
                    <Filters v-model="filters" />
                </template>
            </AddNew>

            <Card class="mt-4" :is-loading="isLoading" no-padding>
                <Table :headers="headers" :items="items">
                    <template #section>
                        <div
                            class="p-6 pb-0 mb-0 bg-gray-300 border-b-0 border-b-solid rounded-t-2xl border-b-transparent"
                        >
                            <h6>{{ props.title }}</h6>
                        </div>
                    </template>
                    <template v-slot="{ item }">
                        <Td>
                            <template #image>
                                <div class="w-8 h-8 mx-1">
                                    <img
                                        src="../../../../../public/admin/assets/img/team-3.jpg"
                                        class="inline-flex items-center justify-center text-white transition-all duration-200 ease-soft-in-out text-size-sm h-9 w-9 rounded-xl"
                                        alt="user1"
                                    />
                                </div>
                            </template>
                            {{ item.name }}
                            <template #second-line>
                                <!-- {{ item.description }} -->
                            </template>
                        </Td>
                        <Td>
                            {{ item.description }} 
                        </Td>
                     
                        
                        <Td>
                            {{ item.created_at_formatted }}
                        </Td>
                        <Td>
                            <Actions
                                :edit-link="
                                    route(`admin.${routeResourceName}.edit`, {
                                        id: item.id,
                                    })
                                "
                                :show-edit="item.can.edit"
                                :show-delete="item.can.delete"
                                @deleteClicked="showDeleteModal(item)"
                            />
                        </Td>
                    </template>
                </Table>
            </Card>
        </Container>
    </Layout>

    <!-- <Modal v-model="deleteModal" :title="`Delete ${itemToDelete.name}`">
        Are you sure you want to delete this item?

        <template #footer>
            <Button @click="handleDeleteItem" :disabled="isDeleting">
                <span v-if="isDeleting">Deleting</span>
                <span v-else>Delete</span>
            </Button>
        </template>
    </Modal> -->

    <Modal
        :title="`Delete ${itemToDelete.name}`"
        :show="deleteModal"
        @close="close"
    >
        <template #title> Delete </template>
        <template #content>
            Are you sure you want to delete this item?
        </template>
        <template #footer>
            <Button @click="handleDeleteItem" :disabled="isDeleting">
                <span v-if="isDeleting">Deleting</span>
                <span v-else>Delete</span>
            </Button>
        </template>
    </Modal>
</template>
