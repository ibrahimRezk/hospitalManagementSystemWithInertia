<script setup>
import { onMounted, ref, watch, computed } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
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
import Filters from "./Filters.vue";

import useDeleteItem from "@/Composables/useDeleteItem.js";
import useFilters from "@/Composables/useFilters.js";

const props = defineProps({
    patient: {
        type: Object,
        default: () => ({}),
    },
    headers: {
        type: Array,
        default: () => [],
    },
    payments: {
        type: Array,
        default: () => [],
    },
    invoices: {
        type: Object,
        default: () => ({}),
    },
    statement: {
        type: Object,
        default: () => ({}),
    },
    receipts: {
        type: Array,
        default: () => [],
    },

    routeResourceName: {
        type: String,
        required: true,
    },
});

const currentImage = ref(props.patient.images?.[0]?.img.original_url ?? null);



const invoicesHeaders = ref([
    { label: "Service Name" },
    { label: "Invoice Date" },
    { label: "Total With Tax" },
    { label: "Type" },
]);

const paymentsReceiptsHeaders = ref([
    { label: "#" },
    { label: "Payment Data" },
    { label: "Amount" },
    { label: "description" },
]);

const statementHeaders = ref([
    { label: "#" },
    { label: "Date" },
    { label: "Description" },
    { label: "Debit" },
    { label: "Credit" },
]);

/////// to show tabs with it's number so easy way ////////
const activeTab = (num) => {
    return (tab.value = num);
};
const tab = ref(1);
////////////////////

// get invoices total invoices amount with tax///////
const InvoicesTotalWithTax = computed(() => {
    let sum = 0;
    let invoices = props.invoices;
    invoices.data.forEach((item) => {
        sum += parseInt(item.total_with_tax);
    });
    return sum;
});
////////////////////////////////////////////////////////////////////

// get invoices total payments amount  ///////
const TotalReceipts = computed(() => {
    let sum = 0;
    let receipts = props.receipts;
    receipts.data.forEach((item) => {
        sum += parseInt(item.amount);
    });
    return sum;
});
////////////////////////////////////////////////////////////////////
// get invoices total payments amount  ///////
const TotalPayments = computed(() => {
    let sum = 0;
    let payments = props.payments;
    payments.data.forEach((item) => {
        sum += parseInt(item.amount);
    });
    return sum;
});
////////////////////////////////////////////////////////////////////
// get net total for all financial operations   ///////
const NetTotal = computed(() => {
    let sum = 0;
    let Debit = 0;
    let credit = 0;
    let statement = props.statement;
    statement.data.forEach((item) => {
        Debit += parseInt(item.Debit);
        credit += parseInt(item.credit);
        sum = Debit - credit;
    });
    return sum;
});
////////////////////////////////////////////////////////////////////
</script>
<!-- {{ $t('Welcome') }} -->
<template>
    <Head :title="title" />

    <Layout>
        <Container>
            <Card class="mt-4" :is-loading="isLoading" no-padding>
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
                                {{ $t("tables.Invoices") }}
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
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 group-hover:text-gray-500"
                                    :class="{
                                        'text-yellow-600 group-hover:text-yellow-500':
                                            tab == 3,
                                    }"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"
                                    ></path>
                                </svg>
                                {{ $t("tables.Payments") }}
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
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 group-hover:text-gray-500"
                                    :class="{
                                        'text-yellow-600 group-hover:text-yellow-500':
                                            tab == 4,
                                    }"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"
                                    ></path>
                                </svg>
                                {{ $t("tables.Receipts") }}
                            </a>
                        </li>
                        <li class="mr-2">
                            <a
                                @click="activeTab(5)"
                                href="#"
                                class="inline-flex p-4 rounded-t-lg group"
                                :class="{
                                    'text-yellow-600 border-b-2 border-yellow-600 bg-slate-50  shadow-xl hover:text-yellow-500 ':
                                        tab == 5,
                                }"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 group-hover:text-gray-500"
                                    :class="{
                                        'text-yellow-600 group-hover:text-yellow-500':
                                            tab == 5,
                                    }"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"
                                    ></path>
                                    <path
                                        fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                {{ $t("tables.Statement") }}
                            </a>
                        </li>
                        <li class="mr-2">
                            <a
                                @click="activeTab(6)"
                                href="#"
                                class="inline-flex p-4 rounded-t-lg group"
                                :class="{
                                    'text-yellow-600 border-b-2 border-yellow-600 bg-slate-50  shadow-xl hover:text-yellow-500 ':
                                        tab == 6,
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
                                @click="activeTab(7)"
                                href="#"
                                class="inline-flex p-4 rounded-t-lg group"
                                :class="{
                                    'text-yellow-600 border-b-2 border-yellow-600 bg-slate-50  shadow-xl hover:text-yellow-500 ':
                                        tab == 7,
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

                <!-- /////// patient information end ///////////////// -->

                <!-- /////////////////////////// invoices //////////////////////////////////////// -->
                <div class="mt-2" v-show="tab === 2">
                    <Table :headers="invoicesHeaders" :items="invoices">
                        <template v-slot="{ item }">
                            <Td>
                                {{ item.service?.name ?? item.group?.name }}
                            </Td>
                            <Td>
                                {{ item.created_at_formatted }}
                            </Td>

                            <Td>
                                {{ item.total_with_tax }}
                            </Td>
                            <Td>
                                {{ item.type }}
                            </Td>

                            <tr></tr>
                        </template>
                    </Table>

                    <!-- {{ number_format( $invoices->sum('total_with_tax') , 2)}} -->

                    <div
                        class="bg-gray-500 text-yellow-300 font-bold py-4 text-xl flex justify-center"
                    >
                        Total With Tax : {{ InvoicesTotalWithTax }}
                    </div>
                </div>

                <!-- /////////////////////////// invoices end //////////////////////////////////////// -->

                <!-- /////////////////////////// payments  //////////////////////////////////////// -->

                <div class="mt-2" v-show="tab === 3">
                    <Table :headers="paymentsReceiptsHeaders" :items="payments">
                        <template v-slot="{ item }">
                            <Td>
                                {{ item.id }}
                            </Td>
                            <Td>
                                {{ item.created_at_formatted }}
                            </Td>

                            <Td>
                                {{ item.amount }}
                            </Td>
                            <Td>
                                {{ item.description }}
                            </Td>

                            <tr></tr>
                        </template>
                    </Table>

                    <!-- {{ number_format( $invoices->sum('total_with_tax') , 2)}} -->

                    <div
                        class="bg-gray-500 text-yellow-300 font-bold py-4 text-xl flex justify-center"
                    >
                        Total Payments : {{ TotalPayments }}
                    </div>
                </div>

                <!-- /////////////////////////// payments end  //////////////////////////////////////// -->
                <!-- /////////////////////////// Receipts  //////////////////////////////////////// -->

                <div class="mt-2" v-show="tab === 4">
                    <Table :headers="paymentsReceiptsHeaders" :items="receipts">
                        <template v-slot="{ item }">
                            <Td>
                                {{ item.id }}
                            </Td>
                            <Td>
                                {{ item.created_at_formatted }}
                            </Td>

                            <Td>
                                {{ item.amount }}
                            </Td>
                            <Td>
                                {{ item.description }}
                            </Td>

                            <tr></tr>
                        </template>
                    </Table>

                    <!-- {{ number_format( $invoices->sum('total_with_tax') , 2)}} -->

                    <div
                        class="bg-gray-500 text-yellow-300 font-bold py-4 text-xl flex justify-center"
                    >
                        Total Receipts : {{ TotalReceipts }}
                    </div>
                </div>

                <!-- /////////////////////////// Receipts end  //////////////////////////////////////// -->

                <!-- /////////////////////////// statements  //////////////////////////////////////// -->

                <div class="mt-2" v-show="tab === 5">
                    <Table :headers="statementHeaders" :items="props.statement">
                        <template v-slot="{ item }">
                            <Td>
                                {{ item.id }}
                            </Td>
                            <Td>
                                {{ item.created_at_formatted }}
                            </Td>

                            <Td>
                                {{
                                    item.invoice?.service?.name ??
                                    item.invoice?.group?.name ??
                                    item.payment?.description ??
                                    item.receipt?.description
                                }}
                            </Td>
                            <Td>
                                {{ item.Debit }}
                            </Td>
                            <Td>
                                {{ item.credit }}
                            </Td>

                            <tr></tr>
                        </template>
                    </Table>

                    <!-- {{ number_format( $invoices->sum('total_with_tax') , 2)}} -->

                    <div
                        class="bg-gray-500 text-yellow-300 font-bold py-4 text-xl flex justify-center"
                    >
                        Net Total : {{ NetTotal }}
                    </div>
                </div>

                <!-- /////////////////////////// statements end  //////////////////////////////////////// -->

                
            </Card>
        </Container>
    </Layout>
</template>
