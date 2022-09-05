<script setup>
import { onMounted, ref, watch , computed } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Layout from "@/admin/Layouts/Authenticated.vue";
import Container from "@/admin/Components/Container.vue";
import Card from "@/admin/Components/Card/Card.vue";
import Table from "@/admin/Components/Table/Table.vue";
import Td from "@/admin/Components/Table/Td.vue";
import Actions from "@/admin/Components/Table/Actions.vue";
import Button from "@/admin/Components/Button.vue";
import Modal from "@/admin/Components/ConfirmationModal.vue";

import Label from "@/admin/Components/Label.vue";
import Input from "@/admin/Components/Input.vue";
import AddNew from "@/admin/Components/AddNew.vue";
import Filters from "./Filters.vue";

import useDeleteItem from "@/admin/Composables/useDeleteItem.js";
import useFilters from "@/admin/Composables/useFilters.js";

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
        type: Array,
        default: () => [],
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


/////// to show tabs with it's number so easy way ////////
const activeTab= (num)=>{
    return tab.value = num
}   
const tab = ref()
////////////////////

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
                                class="inline-flex p-4 bg-slate-50 shadow-xl text-yellow-600 rounded-t-lg border-b-2 border-yellow-600 active group"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 text-yellow-600 group-hover:text-gray-500"
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
                                class="inline-flex p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 group"
                                aria-current="page"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 yellow-600"
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
                                class="inline-flex p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 group"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 text-gray-400 group-hover:text-gray-500"
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
                                class="inline-flex p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 group"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 text-gray-400 group-hover:text-gray-500"
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
                            @click="activeTab(5)"

                                href="#"
                                class="inline-flex p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 group"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 text-gray-400 group-hover:text-gray-500"
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
                                {{ $t("tables.Radiology") }}
                            </a>
                        </li>
                        <li class="mr-2">
                            <a
                            @click="activeTab(6)"

                                href="#"
                                class="inline-flex p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 group"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="mx-2 w-5 h-5 text-gray-400 group-hover:text-gray-500"
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
                                {{ $t("tables.Laboratory") }}
                            </a>
                        </li>
                        <li></li>
                    </ul>
                </div>
                <!-- //// main tabs  end ////// -->

                <!-- /////// patient information ///////////////// -->
          

                <table v-show="tab === 1"
                    class="flex flex-wrap justify-right items-center w-full align-top border-gray-200 text-slate-500 my-6"
                >
                    <thead class="align-bottom bg-gray-600">
                        <tr>
                            <th
                                class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
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
                                    <div class="flex-col justify-center">
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
                                    <div class="flex-col justify-center">
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
                                    <div class="flex-col justify-center">
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
                                    <div class="flex-col justify-center">
                                        <h6
                                            class="mb-0 leading-normal text-size-sm"
                                        >
                                            {{ props.patient.birth_date }}
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
                                    <div class="flex-col justify-center">
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
                                    <div class="flex-col justify-center">
                                        <h6
                                            class="mb-0 leading-normal text-size-sm"
                                        >
                                            {{ props.patient.blood_group }}
                                        </h6>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- /////// patient information end ///////////////// -->


<!-- /////////////////////////// invoices //////////////////////////////////////// -->

<table v-show="tab === 2"
class="items-center w-full align-top border-gray-200 text-slate-500 my-6"
>
<thead
    class="align-bottom bg-gray-600"
>
    <tr>
        <th
            class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
        >
            name
        </th>
       
    </tr>
</thead>
<tbody

    class="px-2 py-2 bg-neutral-300 hover:bg-neutral-400"
>
    <tr>
        <td
            class="px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
        >
            <div
                class="flex px-2 py-1"
            >
                <div
                    class="flex-col justify-center"
                >
                    <h6
                        class="mb-0 leading-normal text-size-sm"
                    >
                        {{
                        }}
                    </h6>
                </div>
            </div>
        </td>
       
    </tr>
</tbody>
</table>
<!-- /////////////////////////// invoices //////////////////////////////////////// -->

            </Card>
        </Container>
    </Layout>
</template>
