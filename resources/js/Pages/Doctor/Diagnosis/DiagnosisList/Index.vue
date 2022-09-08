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
import Modal from "@/Components/ConfirmationModal.vue";
import JetDropdown from "@/components/Dropdown.vue";
import JetDropdownLink from "@/components/DropdownLink.vue";


import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import AddNew from "@/Components/AddNew.vue";
import YesNoLabel from "@/Components/YesNoLabel.vue";
// import Filters from "./Filters.vue";

import useDeleteItem from "@/Composables/useDeleteItem.js";
import useFilters from "@/Composables/useFilters.js";

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
    ActionMenu: Array,
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
    method: props.method,
});


const fireShowDeleteModal =(item)=>{
    showDeleteModal(item)
    hideMenu()
}

////// open actions menu /////////////////////////
const openMenu = (id) => {
    showScreenExeptSubmenu.value = true;
    return opened.value != id ? (opened.value = id) : (opened.value = 0);
};
const opened = ref(0);
////// open actions menu end ////////////////////



const showScreenExeptSubmenu = ref(false)

const hideMenu = ()=>{
    showScreenExeptSubmenu.value = false
    return opened.value = 0
}

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
                <Table :headers="headers" :items="items" class=" relative">
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
                                        src="../../../../../../public/admin/assets/img/team-3.jpg"
                                        class="inline-flex items-center justify-center text-white transition-all duration-200 ease-soft-in-out text-size-sm h-9 w-9 rounded-xl"
                                        alt="user1"
                                    />
                                </div>
                            </template>
                            {{ item.patient.name }}
                        </Td>
                        <Td>
                            <Button color="blue" small>
                                {{ item.service.name }}

                            </Button>
                        </Td>

                        <Td>
                            {{ item.created_at_formatted }}
                        </Td>

                        <Td>
                            <Button color="blue" small>
                                {{ item.price }}
                            </Button>
                        </Td>
                        <Td>
                            <Button color="blue" small>
                                {{ item.discount_value }}
                            </Button>
                        </Td>
                        <Td>
                            <Button color="blue" small>
                                {{ item.tax_rate }}
                            </Button>
                        </Td>
                        <Td>
                            <Button color="blue" small>
                                {{ item.tax_value }}
                            </Button>
                        </Td>
                        <Td>
                            <Button color="blue" small>
                                {{ item.total_with_tax }}
                            </Button>
                        </Td>

                        <Td>
                            <Button color="red" small>
                                {{ item.invoice_type }}
                            </Button>
                        </Td>

                        <!-- <Td  >  -->
                        <Td NoShadow  > 
                            <!-- This example requires Tailwind CSS v2.0+ -->
                            <div class="inline-block text-left  ">
                                <!-- <div class=" inline-block text-left overflow-visible"> -->
                                <div>
                                    <button
                                        @click="openMenu(item.id)"
                                        
                                        type="button"
                                        class=" inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                                        id="menu-button"
                                        aria-expanded="true"
                                        aria-haspopup="true"
                                    >
                                        Options
                                        <!-- Heroicon name: solid/chevron-down -->
                                        <svg
                                            class="mx-2 ml-2 h-5 w-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <!--
    Dropdown menu, show/hide based on menu state.

    Entering: "transition ease-out duration-100"
      From: "transform opacity-0 scale-95"
      To: "transform opacity-100 scale-100"
    Leaving: "transition ease-in duration-75"
      From: "transform opacity-100 scale-100"
      To: "transform opacity-0 scale-95"
  -->
                               
                                    <!-- // to add functionality of close submenu on click out side we added ref  -->
                                <div
                                    v-show="item.id == opened"
                                    class=" rtl:left-5 ltr:right-5 -translate-x-0 transform  origin-top-right   mt-2  rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
                                    :class="{' absolute z-10' : item.id == opened}"
                                    role="menu"
                                    aria-orientation="vertical"
                                    aria-labelledby="menu-button"
                                    tabindex="-1"
                                >
                                    <div class="py-1" role="none">
                                        <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                        <a
                                            href="#"
                                            class="text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                                            role="menuitem"
                                            tabindex="-1"
                                            id="menu-item-0"
                                        >
                                            <!-- Heroicon name: solid/pencil-alt -->
                                            <svg
                                                class="mx-2 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"
                                                />
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            {{ $t('doctor.Add Diagnosis') }}
                                        </a>
                                        <a
                                            href="#"
                                            class="text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                                            role="menuitem"
                                            tabindex="-1"
                                            id="menu-item-1"
                                        >
                                            <!-- Heroicon name: solid/duplicate -->
                                            <svg
                                                class="mx-2 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z"
                                                />
                                                <path
                                                    d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z"
                                                />
                                            </svg>
                                            {{ $t('doctor.Add Review') }}
                                        </a>
                                    </div>
                                    <div class="py-1" role="none">
                                        <a
                                            href="#"
                                            class="text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                                            role="menuitem"
                                            tabindex="-1"
                                            id="menu-item-2"
                                        >
                                            <!-- Heroicon name: solid/archive -->
                                            <svg
                                                class="mx-2 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"
                                                />
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            {{ $t('doctor.To Radiology') }}
                                        </a>
                                        <a
                                            href="#"
                                            class="text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                                            role="menuitem"
                                            tabindex="-1"
                                            id="menu-item-3"
                                        >
                                            <!-- Heroicon name: solid/arrow-circle-right -->
                                            <svg
                                                class="mx-2 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            {{ $t('doctor.To Laboratory') }}
                                        </a>
                                    </div>
                                    
                                    <div class="py-1" role="none" @click="fireShowDeleteModal(item)">
                                        <a
                                        
                                            href="#"
                                            class="text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                                            role="menuitem"
                                            tabindex="-1"
                                            id="menu-item-6"
                                        >
                                            <!-- Heroicon name: solid/trash -->
                                            <svg
                                                class="mx-2 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                
                                    
                                


                                            {{ $t('doctor.Delete') }}
                                        </a>
                                    </div>
                                </div>

                               
                            </div>
                        </Td>

                     

                        <!-- <Td>
                                <Actions
                                    :edit-link="
                                        route(`doctor.${routeResourceName}.edit`, {
                                            id: item.id,
                                        })
                                    "
                                    :show-edit="item.can.edit"
                                    :show-delete="item.can.delete"
                                    @deleteClicked="showDeleteModal(item)"
                                />
                            </Td> -->
                    </template>
                </Table>

          
            </Card>
        </Container>
    </Layout>


    <div v-show="showScreenExeptSubmenu"  class="fixed inset-0 transform transition-all" @click="hideMenu">
        <div class="absolute inset-0  opacity-95" />
    </div>



    
    
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






<!-- heroicons  -->
<!-- 
<div class="py-1" role="none">
    <a
        href="#"
        class="text-gray-700 group flex items-center px-4 py-2 text-sm"
        role="menuitem"
        tabindex="-1"
        id="menu-item-4"
    > -->
        <!-- Heroicon name: solid/user-add -->
        <!-- <svg
            class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
        > -->
            <!-- <path
                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"
            />
        </svg>
        Share
    </a> -->
    <!-- <a
        href="#"
        class="text-gray-700 group flex items-center px-4 py-2 text-sm"
        role="menuitem"
        tabindex="-1"
        id="menu-item-5"
    > -->
        <!-- Heroicon name: solid/heart -->
        <!-- <svg
            class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
        > -->
            <!-- <path
                fill-rule="evenodd"
                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                clip-rule="evenodd"
            />
        </svg>
        Add to favorites
    </a> -->
<!-- </div> -->