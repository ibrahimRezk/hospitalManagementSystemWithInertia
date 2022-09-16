<script setup>
import { Head, useForm, Link } from "@inertiajs/inertia-vue3";
import { onMounted, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Layout from "@/Layouts/Authenticated.vue";
import Container from "@/Components/Container.vue";
import Card from "@/Components/Card/Card.vue";
import Table from "@/Components/Table/Table.vue";
import Td from "@/Components/Table/Td.vue";
import Actions from "@/Components/Table/Actions.vue";
import Button from "@/Components/Button.vue";
import Modal from "@/Components/ConfirmationModal.vue";
import DialogModal from "@/Components/DialogModal.vue";
import JetDropdown from "@/components/Dropdown.vue";
import JetDropdownLink from "@/components/DropdownLink.vue";
import InputError from "@/Components/InputError.vue";

import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import AddNew from "@/Components/AddNew.vue";
import YesNoLabel from "@/Components/YesNoLabel.vue";
// import Filters from "./Filters.vue";

import useDeleteItem from "@/Composables/useDeleteItem.js";
import useDialogModal from "@/Composables/useDialogModal.js";
import useFilters from "@/Composables/useFilters.js";

const props = defineProps({
    errors: Object,
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
    // filters: {
    //     type: Object,
    //     default: () => ({}),
    // },
    routeResourceName: {
        type: String,
        required: true,
    },
    // can: Object,

    method: String,
});

const { filters, isLoading, isFilled } = useFilters({
    filters: props.filters,
    routeResourceName: props.routeResourceName,
    method: props.method,
});

// if you want to make color changed at once make the function in a new page not a modal
const color = (item) => {
    return item.status == "completed"
        ? "green"
        : item.status == "reviewing"
        ? "yellow"
        : "red";
};
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
            <!-- <AddNew :show="isFilled">
                        <template #filters>
                            <Filters v-model="filters" :sections="sections" />
                        </template>
                    </AddNew> -->

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
                            {{ item.patient.name }}
                        </Td>
                        <Td>
                            <Button color="blue" small>
                                {{ item.description }}
                            </Button>
                        </Td>

                        <Td>
                            <Button color="blue" small>
                                {{ item.doctor.name }}
                            </Button>
                        </Td>

                        <Td>
                            {{ item.created_at }}
                        </Td>
                        
                        <Td>
                            <Button small :color="color(item)">
                                {{ item.status }}
                            </Button>
                        </Td>

                        <Td>
                            <Link   :href=" route(`radiologist.radiologies.create`, { id: item.id }) ">
                                <Button small >
                            Report
                        </Button>
                        </Link>
                        </Td>
                    </template>
                </Table>
            </Card>
        </Container>
    </Layout>

    <!-- //// importan when click on actions menu and submenu appears it suppose to disappear when clickin away this dive is for this with hideMenu function -->
    <div
        v-show="showScreenExeptSubmenu"
        class="fixed inset-0 transform transition-all"
        @click="hideMenu"
    >
        <div class="absolute inset-0 opacity-95" />
    </div>

</template>
