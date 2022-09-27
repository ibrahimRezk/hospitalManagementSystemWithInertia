<script setup>
import { Head, useForm , Link } from "@inertiajs/inertia-vue3";
import { computed, onMounted, ref, watch } from "vue";
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
    ActionMenu: Array,
});

const opened = ref(0);
const method = ref("store");
const addReview = ref(false);
const diagnosOrReview = ref(false);
const radiologiesOrLaboratories = ref(false);
const laboratories = ref(false);
const showScreenExeptSubmenu = ref(false);
const routeResourceName =ref('');
const editMode = ref(false);
const invoice_status = ref()

const fireShowDeleteModal = (item) => {
    showDeleteModal(item);
    hideMenu();
};

////// open actions menu /////////////////////////
const openMenu = (id) => {
    showScreenExeptSubmenu.value = true;
    addReview.value = false;
    diagnosOrReview.value = false;
    radiologiesOrLaboratories.value = false;
    laboratories.value = false;
    form.reset();
    editMode.value = false;

    return opened.value != id ? (opened.value = id) : (opened.value = 0);
};

////// open actions menu end ////////////////////

const hideMenu = () => {
    showScreenExeptSubmenu.value = false;
    opened.value = 0;
};



const form = useForm({
    diagnose:  "",
    medicine: "",
    description : "",
    review_date:  null,
});

// const fillForm = (item)=>{

//         form.diagnose = '';
//         form.medicine = '';
//         form.review_date ='';
//     }

const fillForm = (item)=>{
    if(item.diagnose !== null){
        editMode.value = true;
        form.diagnose = item.diagnose.diagnose ;
        form.medicine = item.diagnose.medicine;
        form.review_date = item.diagnose.review_date ;
    }else{
        editMode.value = false;
        form.diagnose ='';
        form.medicine = '';
        form.review_date = null;
    }
}


const fireAddDiagnosisModal = (item) => {
    fillForm(item);
    invoice_status.value = 3;
    editMode.value == true ? method.value = "update" : 'store';
    diagnosOrReview.value = true;
    routeResourceName.value = 'diagnoses';
    hideMenu();
    return showDialogModal(item);
};

const fireAddReviewModal = (item) => {
    fillForm(item);
    invoice_status.value = 2;
    editMode.value == true ? method.value = "update" : 'store';
    addReview.value = true;
    diagnosOrReview.value = true;
    routeResourceName.value = 'diagnoses';
    hideMenu();
    return showDialogModal(item);
};

const fireToRadiologyModal = (item)=> {
    radiologiesOrLaboratories.value = true
    method.value = "store";
    routeResourceName.value = 'radiologies';
    hideMenu();
    return showDialogModal(item);
}
const fireToLaboratoryModal = (item)=> {
    radiologiesOrLaboratories.value = true
    method.value = "store";
    routeResourceName.value = 'laboratories';
    hideMenu();
    return showDialogModal(item);
}

const {
    closeDialogModal,
    dialogModal,
    itemToSave,
    isSaving,
    showDialogModal,
    handleSavingItem,
} = useDialogModal({
    routeResourceName: routeResourceName,
    form: form,
    opened,
    showScreenExeptSubmenu,
    method,
    editMode,
    invoice_status,
    
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

// if you want to make color changed at once make the function in a new page not a modal
const color = (item) => {
    return item.invoice_status == "completed"
        ? "green"
        : item.invoice_status == "reviewing"
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
                        <Td >
                            <template #image>
                                <div class="w-8 h-8  rtl:ml-1 ltr:mr-1">


                                    <div
                                    v-for="(image, index) in item.patient.images" :key="index"
                                    v-html="image.html"
                                    class="[&_img]:h-full [&_img]:w-full [&_img]:object-contain shadow-xl "
                                >
                                </div>
                                </div>
                            </template> 
                            <Link  class=" text-blue-600"
                            :href=" route(`doctor.patient_details`, { id: item.patient.id }) ">
                            
                            {{ item.patient.name }}
                        </Link>
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
                            <Button small :color="color(item)">
                                {{ item.invoice_status }}
                            </Button>
                        </Td>

                        <!-- <Td  >  -->
                        <Td NoShadow>
                            <!-- This example requires Tailwind CSS v2.0+ -->
                            <div class="inline-block text-left">
                                <!-- <div class=" inline-block text-left overflow-visible"> -->
                                <div>
                                    <button
                                        @click="openMenu(item.id)"
                                        type="button"
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
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
                                <!-- class="rtl:left-5 ltr:right-5 -translate-x-0 transform origin-top-right mt-2 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none" -->
                                <div
                                    v-show="item.id == opened"
                                    class="rtl:left-7 ltr:right-7 -translate-x-0 transform origin-top-right mt-2 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
                                    :class="{
                                        ' absolute z-10': item.id == opened,
                                    }"
                                    role="menu"
                                    aria-orientation="vertical"
                                    aria-labelledby="menu-button"
                                    tabindex="-1"
                                >
                                    <div class="py-1" role="none">
                                        <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                        <a
                                            @click="fireAddDiagnosisModal(item)"
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
                                            {{ $t("doctor.Add Diagnosis") }}
                                        </a>
                                        <a
                                            @click="fireAddReviewModal(item)"
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
                                            {{ $t("doctor.Add Review") }}
                                        </a>
                                    </div>
                                    <div class="py-1" role="none">
                                        <a
                                        @click="fireToRadiologyModal(item)"
                                        
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
                                            {{ $t("doctor.To Radiology") }}
                                        </a>
                                        <a
                                        @click="fireToLaboratoryModal(item)"
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
                                            {{ $t("doctor.To Laboratory") }}
                                        </a>
                                    </div>

                                    
                                </div>
                            </div>
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
    <!-- /////////////////////////////////////////////////////////// -->
    <!-- <Modal
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
    </Modal> -->

    <!-- //////////////////////////Dialog Modal/////////////////////////////////////// -->

    <DialogModal
        :title="`Add ${itemToSave.name}`"
        :show="dialogModal"
        @close="closeDialogModal"
    >
        <template #title>
            <div class="bg-gray-800 px-4 py-2 text-white shadow-lg">
                add new diagnose
            </div>
        </template>

        <template #content>
            <form @submit.prevent="handleSavingItem">
                <div  v-if="radiologiesOrLaboratories">
                <label
                    class="mb-2 mx-4 bg-red-200 px-5 py1 rounded-lg shadow-md"
                    >description</label
                >
                <textarea
                    v-model="form.description"
                    class="w-full resize border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    name="Text1"
                    cols="40"
                    rows="5"
                />
                <div>
                    <InputError class="mt-1" :message="props.errors.description" />
                </div>
                <br />

            </div>
            <div  v-if="diagnosOrReview">
                <label
                    class="mb-2 mx-4 bg-red-200 px-5 py1 rounded-lg shadow-md"
                    >diagnose</label
                >
                <textarea
                    v-model="form.diagnose"
                    class="w-full resize border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    name="Text1"
                    cols="40"
                    rows="5"
                />
                <div>
                    <InputError class="mt-1" :message="props.errors.diagnose" />
                </div>
                <br />
                <hr class="mt-5 h-px bg-black" />

            </div>
            <div  v-if="diagnosOrReview">
                <label
                    class="mt-5 mb-2 mx-4 bg-green-200 px-5 py1 rounded-lg shadow-md"
                    >medicine</label
                >
                <textarea 
                    v-model="form.medicine"
                    class="w-full resize border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    name="Text1"
                    cols="40"
                    rows="5"
                />
                <div>
                    <InputError class="mt-1" :message="props.errors.medicine" />
                </div>

                <hr class="mt-5 h-px bg-black" />

                <br />
            </div>

                <div v-if="addReview">
                    <input
                        type="date"
                        v-model="form.review_date"
                        class="w-1/2 mb-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    />
                    <div>
                        <InputError
                            class="mt-1"
                            :message="props.errors.review_date"
                        />
                    </div>

                    <br />
                </div>

                <!-- <input type="hidden" v-model="form.patient_id">
            <input type="hidden" v-model="form.doctor_id" >
            <input type="hidden" v-model="form.patient_id"> -->

                <Button :disabled="form.processing">
                    {{ form.processing ? "Saving..." : "Save" }}
                </Button>
            </form>
        </template>

        <template #footer> </template>
    </DialogModal>



</template>

<!-- extract heroicons  -->
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









<!-- // delete icon in sub <menu>
    <div
                                        class="py-1"
                                        role="none"
                                        @click="fireShowDeleteModal(item)"
                                    >
                                        <a
                                            href="#"
                                            class="text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                                            role="menuitem"
                                            tabindex="-1"
                                            id="menu-item-6"
                                        >
                                            <-- Heroicon name: solid/trash -->
                                            <!-- <svg
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

                                            {{ $t("doctor.Delete") }}
                                        </a>
                                    </div>  -->
