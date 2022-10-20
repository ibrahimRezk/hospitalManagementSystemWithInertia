<script setup>
    import { onMounted, ref, watch } from "vue";
    import { Head } from "@inertiajs/inertia-vue3";
    import { Inertia } from "@inertiajs/inertia";
    import Layout from "@/Layouts/Authenticated.vue";
    import Container from "@/Components/Container.vue";
    import Card from "@/Components/Card/Card.vue";
    import Table from "@/Components/Table/Table.vue";
    import Td from "@/Components/Table/Td.vue";
    import Actions from "@/Components/Table/Actions.vue";
    import Button from "@/Components/Button.vue";
    import Modal from '@/Components/ConfirmationModal.vue'
    // import Modal from '@/Components/DialogModal.vue'

    
    import Label from "@/Components/Label.vue";
    import Input from "@/Components/Input.vue";
    import AddNew from "@/Components/AddNew.vue";
    import YesNoLabel from "@/Components/YesNoLabel.vue";
    // import Filters from "./Filters.vue";
    
    import useDeleteItem from "@/Composables/useDeleteItem.js";
    import useFilters from "@/Composables/useFilters.js";
    import Translations from "@/Components/translations/Translations.vue";
    
    
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
    
    const { filters, isLoading } = useFilters({
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
                <AddNew>
                    <Button  color="blue"
                        v-if="can.create"
                        :href="route(`admin.${routeResourceName}.create`)"
                        >Add New</Button
                    >
    
                    <template #filters>
                        <Filters v-model="filters" />
                    </template>
                </AddNew>
    
                <Card class="mt-2" :is-loading="isLoading" no-padding>
    
                    <Table :headers="headers" :items="items">
                    <template #section>
                    <div class="p-6 pb-0 mb-0 bg-gray-300 border-b-0 border-b-solid rounded-t-2xl border-b-transparent ">
                    <h6>{{ props.title }}</h6>
                    </div>
                    </template> 
    
                        <template v-slot="{ item }">
    
                            <Td>
                                {{ item.name }}
                                                        <!-- <Translations :label="item.name" menu="roles" /> -->
    
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
                <Button color="red" @click="handleDeleteItem" :disabled="isDeleting">
                    <span v-if="isDeleting">Deleting</span>
                    <span v-else>Delete</span>
                </Button>
            </template>
        </Modal>
    </template>
    