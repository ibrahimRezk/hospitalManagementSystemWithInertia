<script setup>
import { onMounted, ref, watch, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Layout from "@/Layouts/Authenticated.vue";
import Container from "@/Components/Container.vue";
import Card from "@/Components/Card/Card.vue";
import Table from "@/Components/Table/Table.vue";
import Td from "@/Components/Table/Td.vue";
import Actions from "@/Components/Table/Actions.vue";
import Button from "@/Components/Button.vue";
import Modal from "@/Components/ConfirmationModal.vue";

import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import AddNew from "@/Components/AddNew.vue";

import useDeleteItem from "@/Composables/useDeleteItem.js";
import useDialogModal from "@/Composables/useDialogModal.js";
import DialogModal from "@/Components/DialogModal.vue";

const props = defineProps({
    patient: {
        type: Object,
        default: () => ({}),
    },
    patient_records: {
        type: Array,
        default: () => [],
    },
    patient_radiologies: {
        type: Array,
        default: () => [],
    },
    patient_laboratories: {
        type: Array,
        default: () => [],
    },

    headers: {
        type: Array,
        default: () => [],
    },
});

const currentImage = ref(props.patient.images?.[0]?.img.original_url ?? null);

const patientInformationHeaders = ref([
    { label: "Name" },
    { label: "Phone Number" },
    { label: "Email" },
    { label: "Birth Date" },
    { label: "Gender" },
    { label: "Blood Group" },
]);

// const patientRecordstHeaders = ref([
//     {label: "#"},
//     {label: "Date"},
//     {label: "Description"},
//     {label: "Debit"},
//     {label: "Credit"},
// ])

const patientRadiologiesHeaders = ref([
    { label: "#" },
    { label: "Description" },
    { label: "Doctor Name" },
    { label: "Employee Name" },
    { label: "Status" },
    { label: "Actions" },
]);

const patientLaboratoriesHeaders = ref([
    { label: "#" },
    { label: "Description" },
    { label: "Doctor Name" },
    { label: "Employee Name" },
    { label: "Status" },
    { label: "Actions" },
]);

/////// to show tabs with it's number so easy way ////////
const activeTab = (num) => {
    return (tab.value = num);
};
const tab = ref(1);
////////////////////

const color = (item) => {
    return item.status == "completed" ? "green" : "red";
};

/////////////////////////// to edit radiology ////////////////////
const radiologiesOrLaboratories = ref(false);
const editMode = ref();
const method = ref("");
const routeResourceName = ref("");
const showScreenExeptSubmenu = ref();

const form = useForm({
    description: "",
});

const fillForm = (item) => {
    form.description = item.description;
};

const fireEditRadiologyModal = (item) => {
    fillForm(item);
    editMode.value = true;
    radiologiesOrLaboratories.value = true;
    method.value = "update";
    routeResourceName.value = "radiologies";
    return showDialogModal(item);
};
/////////////////////////// end edit radiology ////////////////////
const fireEditLaboratoryModal = (item) => {
    fillForm(item);
    editMode.value = true;
    radiologiesOrLaboratories.value = true;
    method.value = "update";
    routeResourceName.value = "laboratories";
    return showDialogModal(item);
};

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
    showScreenExeptSubmenu,
    method,
    editMode,
});
</script>
<!-- {{ $t('Welcome') }} -->
<template>
    <Head :title="title" />

    <Layout>
        <Container>
            <Card class="mt-2" :is-loading="isLoading" no-padding>
                <!-- //// main tabs ////// -->
                <div class="border-b border-yellow-500">
                    <ul
                        class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-700"
                    >
                        <li class="mr-2">
                            <a
                                @click="activeTab(1)"
                                href="#"
                                class="inline-flex p-4 rounded-t-lg group"
                                :class="{
                                    'text-yellow-600 border-b-2 border-yellow-600 bg-slate-50  shadow-xl hover:text-yellow-500 ':
                                        tab == 1,
                                }"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 group-hover:text-gray-500"
                                    :class="{
                                        'text-yellow-600 group-hover:text-yellow-500':
                                            tab == 1,
                                    }"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                {{ $t("tables.Patient Information") }}
                            </a>
                        </li>
                        <li class="mr-2">
                            <a
                                @click="activeTab(2)"
                                href="#"
                                class="inline-flex p-4 rounded-t-lg group"
                                :class="{
                                    'text-yellow-600 border-b-2 border-yellow-600 bg-slate-50  shadow-xl hover:text-yellow-500 ':
                                        tab == 2,
                                }"
                                aria-current="page"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 group-hover:text-gray-500"
                                    :class="{
                                        'text-yellow-600 group-hover:text-yellow-500':
                                            tab == 2,
                                    }"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                                    ></path>
                                </svg>
                                {{ $t("tables.Patient Record") }}
                            </a>
                        </li>

                        <li class="mr-2">
                            <a
                                @click="activeTab(3)"
                                href="#"
                                class="inline-flex p-4 rounded-t-lg group"
                                :class="{
                                    'text-yellow-600 border-b-2 border-yellow-600 bg-slate-50  shadow-xl hover:text-yellow-500 ':
                                        tab == 3,
                                }"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5 mx-2"
                                    :class="{
                                        'text-yellow-600 group-hover:text-yellow-500':
                                            tab == 6,
                                    }"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                                    />
                                </svg>

                                {{ $t("tables.Radiology") }}
                            </a>
                        </li>
                        <li class="mr-2">
                            <a
                                @click="activeTab(4)"
                                href="#"
                                class="inline-flex p-4 rounded-t-lg group"
                                :class="{
                                    'text-yellow-600 border-b-2 border-yellow-600 bg-slate-50  shadow-xl hover:text-yellow-500 ':
                                        tab == 4,
                                }"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5 mx-2"
                                    :class="{
                                        'text-yellow-600 group-hover:text-yellow-500':
                                            tab == 7,
                                    }"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"
                                    />
                                </svg>

                                {{ $t("tables.Laboratory") }}
                            </a>
                        </li>
                        <li></li>
                    </ul>
                </div>
                <!-- //// main tabs  end ////// -->

                <!-- /////// patient information ///////////////// -->
                <div class="flex flex-row" v-show="tab === 1">
                    <div>
                        <table
                            v-show="tab === 1"
                            class="flex rtl:justify-right ltr:justify-left items-center w-full align-top border-gray-200 text-slate-500 my-6"
                        >
                            <thead class="align-bottom bg-gray-600">
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bo ld rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                    >
                                        name
                                    </th>
                                </tr>
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                    >
                                        phone number
                                    </th>
                                </tr>
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                    >
                                        email
                                    </th>
                                </tr>
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                    >
                                        birth date
                                    </th>
                                </tr>
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                    >
                                        gender
                                    </th>
                                </tr>
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                    >
                                        blood group
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="px-2 py-2 bg-neutral-300">
                                <tr>
                                    <td
                                        class="hover:bg-neutral-400 px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                    >
                                        <div class="flex px-2 py-1">
                                            <div
                                                class="flex-col justify-center"
                                            >
                                                <h6
                                                    class="mb-0 leading-normal text-size-sm"
                                                >
                                                    {{ props.patient.name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        class="hover:bg-neutral-400 px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                    >
                                        <div class="flex px-2 py-1">
                                            <div
                                                class="flex-col justify-center"
                                            >
                                                <h6
                                                    class="mb-0 leading-normal text-size-sm"
                                                >
                                                    {{ props.patient.phone }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        class="hover:bg-neutral-400 px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                    >
                                        <div class="flex px-2 py-1">
                                            <div
                                                class="flex-col justify-center"
                                            >
                                                <h6
                                                    class="mb-0 leading-normal text-size-sm"
                                                >
                                                    {{ props.patient.email }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        class="hover:bg-neutral-400 px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                    >
                                        <div class="flex px-2 py-1">
                                            <div
                                                class="flex-col justify-center"
                                            >
                                                <h6
                                                    class="mb-0 leading-normal text-size-sm"
                                                >
                                                    {{
                                                        props.patient.birth_date
                                                    }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        class="hover:bg-neutral-400 px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                    >
                                        <div class="flex px-2 py-1">
                                            <div
                                                class="flex-col justify-center"
                                            >
                                                <h6
                                                    class="mb-0 leading-normal text-size-sm"
                                                >
                                                    {{ props.patient.gender }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        class="hover:bg-neutral-400 px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                    >
                                        <div class="flex px-2 py-1">
                                            <div
                                                class="flex-col justify-center"
                                            >
                                                <h6
                                                    class="mb-0 leading-normal text-size-sm"
                                                >
                                                    {{
                                                        props.patient
                                                            .blood_group
                                                    }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mx-5">
                        <img
                            style="border-radius: 10%"
                            width="300"
                            id="output"
                            :src="currentImage"
                            class="shadow-lg rounded p-1"
                        />
                    </div>
                </div>
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->

                <div
                    class="mt-2"
                    v-show="tab === 2"
                    v-for="patient_record in patient_records"
                    :key="patient_record.id"
                >
                    <div class="container bg-gray-200 mx-auto w-full h-full">
                        <div class="relative wrap overflow-hidden p-10 h-full">
                            <!-- First timeline -->
                            <div
                                class="mb-8 flex justify-between items-center w-full"
                            >
                                <div class="order-2 w-6/12"></div>
                                <div class="z-20">
                                    <div
                                        class="my-4 rounded-full h-10 w-10 flex items-center bg-indigo-300 ring-4 ring-indigo-400 ring-opacity-30"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-10 w-10 text-green-600"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <div
                                    class="order-1 bg-gray-300 rounded-lg shadow-xl sm:w-9/12 px-6 py-4"
                                >
                                    <div class="flex flex-row">
                                        Date : {{ patient_record.date }}
                                    </div>

                                    <p
                                        class="text-base leading-snug tracking-wide text-gray-900 text-opacity-100"
                                    >
                                        Diagnosis :
                                        {{ patient_record.diagnose }}
                                    </p>

                                    <p
                                        class="text-base leading-snug tracking-wide text-gray-900 text-opacity-100"
                                    >
                                        Medicine : {{ patient_record.medicine }}
                                    </p>

                                    <p
                                        class="text-base leading-snug tracking-wide text-gray-900 text-opacity-100"
                                    >
                                        Doctor Name :
                                        {{ patient_record.doctor.name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Second timeline -->
                            <!-- <div class="mb-8 flex justify-between items-center w-full">
            <div class="order-2 w-6/12"></div>
            <div class="z-20">
              <div class="my-4 rounded-full h-10 w-10 flex items-center bg-indigo-300 ring-4 ring-indigo-400 ring-opacity-30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            <div class="order-1 bg-red-200 rounded-lg shadow-xl w-5/12 px-6 py-4">
              <div class="flex flex-row">
                <h3 class="mb-3 font-bold text-gray-800 text-xl">Status</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="red">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
                <h5 class="mb-3 font-bold text-gray-800 text-xl">Loc</h5>
              </div>
      
              <p class="text-base leading-snug tracking-wide text-gray-900 text-opacity-100">statusinfo helooooooooooooooooooooooo</p>
            </div>
          </div> -->

                            <!-- you can add more time line from here :) -->
                        </div>
                    </div>
                </div>

                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->

                <div class="mt-2" v-show="tab === 3">
                    <Table
                        :headers="patientRadiologiesHeaders"
                        :items="patient_radiologies"
                    >
                        <template v-slot="{ item }">
                            <Td>
                                {{ item.id }}
                            </Td>
                            <Td>
                                {{ item.description }}
                            </Td>
                            <Td>
                                {{ item.doctor.name }}
                            </Td>

                            <Td>
                                {{ item.employee.name ?? "--" }}
                            </Td>
                            <Td>
                                <Button small :color="color(item)">
                                    {{ item.status }}
                                </Button>
                            </Td>
                            <Td>
                                <Link
                                    v-if="item.employee_description"
                                    :href="
                                        route(`doctor.radiologies.show`, {
                                            id: item.id,
                                        })
                                    "
                                >
                                    <Button small> view result </Button>
                                </Link>

                                <span v-else>
                                    <Button
                                        small
                                        color="blue"
                                        @click="fireEditRadiologyModal(item)"
                                    >
                                        Edit
                                    </Button>
                                </span>
                            </Td>
                        </template>
                    </Table>
                </div>

                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <div class="mt-2" v-show="tab === 4">
                    <Table
                        :headers="patientLaboratoriesHeaders"
                        :items="patient_laboratories"
                    >
                        <template v-slot="{ item }">
                            <Td>
                                {{ item.id }}
                            </Td>
                            <Td>
                                {{ item.description }}
                            </Td>
                            <Td>
                                {{ item.doctor.name }}
                            </Td>

                            <Td>
                                {{ item.employee.name ?? "--" }}
                            </Td>
                            <Td>
                                <Button small :color="color(item)">
                                    {{ item.status }}
                                </Button>
                            </Td>
                            <Td>
                                <Link
                                    v-if="item.employee_description"
                                    :href="
                                        route(`doctor.laboratories.show`, {
                                            id: item.id,
                                        })
                                    "
                                >
                                    <Button small color="blue">
                                        view results
                                    </Button>
                                </Link>

                                <span v-else>
                                    <Button
                                        small
                                        color="blue"
                                        @click="fireEditLaboratoryModal(item)"
                                    >
                                        Edit
                                    </Button>
                                </span>
                            </Td>
                        </template>
                    </Table>
                </div>
            </Card>
        </Container>
    </Layout>

    <DialogModal :title="`Edit`" :show="dialogModal" @close="closeDialogModal">
        <template #title>
            <div class="bg-gray-800 px-4 py-2 text-white shadow-lg">
                add new diagnose
            </div>
        </template>

        <template #content>
            <form @submit.prevent="handleSavingItem">
                <div v-if="radiologiesOrLaboratories">
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
                        <!-- <InputError class="mt-1" :message="props.errors.description" /> -->
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
