<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Layout from "@/admin/Layouts/Authenticated.vue";
import Container from "@/admin/Components/Container.vue";
import Card from "@/admin/Components/Card/Card.vue";
import Button from "@/admin/Components/Button.vue";
import InputGroup from "@/admin/Components/InputGroup.vue";
import SelectGroup from "@/admin/Components/SelectGroup.vue";
import CheckboxGroup from "@/admin/Components/CheckboxGroup.vue";
import { ref } from "@vue/reactivity";
import { computed, watch } from "@vue/runtime-core";
import Table from "@/admin/Components/Table/Table.vue";
import Trash from "@/admin/Components/Icons/Trash.vue";

const props = defineProps({
    edit: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
    },
    item: {
        type: Object,
        default: () => ({}),
    },
    routeResourceName: {
        type: String,
        required: true,
    },
    services: {
        type: Object,
        default: () => ({}),
    },
});
const form = useForm({
    // name: props.item.name ?? "",
    name_ar: props.item.name_ar ?? "",
    name_en: props.item.name_en ?? "",
    notes_ar: props.item.notes_ar ?? "",
    notes_en: props.item.notes_en ?? "",
    status: props.item.status ?? "",
    discount_value: props.item.discount_value ?? "",
    Total_before_discount: props.item.Total_before_discount ?? "",
    Total_after_discount: props.item.Total_after_discount ?? "",
    tax_rate: props.item.tax_rate ?? "",
    Total_with_tax: props.item.Total_with_tax ?? "",
    services: props.item.services ?? "",
});


const quantity = ref()

const choosenService = ref({});

const addedServices = ref([]);

watch(
    () => choosenService.value,
    () => addService.value
);

const addService = computed(() => {
    if (!choosenService.value) return [];

    let singleService = props.services.find(
        (c) => c.id == choosenService.value
    );
    if (!singleService) return [];

    addedServices.value.push(singleService);
});

const removeServiceFromList = (index) => {
    console.log(index);
    addedServices.value.splice(index, 1);
};



const submit = () => {
    props.edit
        ? form.put(
              route(`admin.${props.routeResourceName}.update`, {
                  id: props.item.id,
              })
          )
        : form.post(route(`admin.${props.routeResourceName}.store`));
};

const show = ref(false);
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
            <Card>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <InputGroup
                            label="Name ar"
                            v-model="form.name_ar"
                            :error-message="form.errors.name"
                            required
                        />
                        <InputGroup
                            label="Name en"
                            v-model="form.name_en"
                            :error-message="form.errors.name"
                            required
                        />
                    </div>
                    <div class="grid grid-cols-1 gap-6 mb-6">
                        <InputGroup
                            label="notes ar"
                            v-model="form.notes_ar"
                            :error-message="form.errors.notes_ar"
                            required
                        />
                        <InputGroup
                            label="notes en"
                            v-model="form.notes_en"
                            :error-message="form.errors.notes_en"
                            required
                        />
                    </div>

                    <hr
                        class="h-px mt-10 bg-black bg-gradient-horizontal-dark"
                    />

                    <button
                        @click="show = true"
                        class="bg-cyan-900 px-3 py-2 rounded-md text-gray-100 mt-8 mb-8"
                    >
                        Add single service
                    </button>

  <div v-if="show">
                    <SelectGroup
                        label="services"
                        v-model="choosenService"
                        :items="services"
                        :error-message="form.errors.services"
                    />
<table
                        class="items-center w-full  align-top border-gray-200 text-slate-500 my-6  "
                    >
                        <thead class="align-bottom bg-gray-600">
                            <tr>
                                <th
                                    class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                >name</th>
                                 <th
                                    class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                >number</th>
                                 <th
                                    class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                >actions</th>
                            </tr>
                        </thead>
                        <tbody v-for="(service, index) in addedServices"
                                class="px-2 py-2 bg-neutral-300 hover:bg-neutral-400">
                            <tr>
                                <td
                                    class="px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                >
                                    <div class="flex px-2 py-1">
                                        <div class="flex-col justify-center">
                                            <h6
                                                class="mb-0 leading-normal text-size-sm"
                                            > {{ service.name }}</h6>
                                        </div>
                                    </div>
                                </td>

                                <td
                                    class="px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                >
                                    <div class="flex px-2 py-1">
                                        <div class="flex-col justify-center">
                                             <input v-model="quantity" type="number" class=" rounded-lg w-30"/>
                                        </div>
                                    </div>
                                </td>

                                <td
                                    class="px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                >
                                    <Trash
                                        class="w-4 h-4 text-red-700"
                                        @click="removeServiceFromList(index)"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                  <hr
                        class="h-px mt-10 bg-black bg-gradient-horizontal-dark"
                    />

                    
                        <div class="grid grid-cols-2 gap-6 mb-6 mt-12">
                            <InputGroup
                                label="discount value"
                                v-model="form.discount_value"
                                :error-message="form.errors.discount_value"
                                required
                            />
                            <InputGroup
                                label="Total before discount"
                                v-model="form.Total_before_discount"
                                :error-message="
                                    form.errors.Total_before_discount
                                "
                                required
                            />
                            <InputGroup
                                label="Total after discount"
                                v-model="form.Total_after_discount"
                                :error-message="
                                    form.errors.Total_after_discount
                                "
                                required
                            />

                            <InputGroup
                                label="tax rate"
                                v-model="form.tax_rate"
                                :error-message="form.errors.tax_rate"
                                required
                            />
                            <InputGroup
                                label="Total with tax"
                                v-model="form.Total_with_tax"
                                :error-message="form.errors.Total_with_tax"
                                required
                            />

                            
                        </div>

                        <div class="mt-2 mb-4">
                                <CheckboxGroup
                                    label="Active"
                                    v-model:checked="form.status"
                                />
                            </div>
                        <div class="mt-4">
                            <Button :disabled="form.processing">
                                {{ form.processing ? "Saving..." : "Save" }}
                            </Button>
                        </div>
                    </div>
                </form>
            </Card>
        </Container>
    </Layout>
</template>
