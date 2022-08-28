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
    services :{
        type: Object,
        default: () => ({}), 
    }
 
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

const  choosenService = ref({})

  const addedServices = ref([]);



  const addService = computed(()=>{

    if (!choosenService.value) return [];

    let singleService = props.services.find((c) => c.id == choosenService.value);
    if (!singleService) return [];

    addedServices.value.push(singleService)
  })



  



const submit = () => {
    props.edit
        ? form.put(
              route(`admin.${props.routeResourceName}.update`, {
                  id: props.item.id,
              })
          )
        : form.post(route(`admin.${props.routeResourceName}.store`));
};


const show = ref(false)


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
                    <div class="grid grid-cols-1 gap-6  mb-6">

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
                        

                                    <hr class="h-px  mt-10 bg-black bg-gradient-horizontal-dark " />



<button @click="show= true" class=" bg-cyan-900 px-3 py-2 rounded-md text-gray-100 mt-8 mb-8">Add single service</button>



                                <SelectGroup    
                                    label="services"
                                    v-model="choosenService"
                                    :items="services"
                                    :error-message="form.errors.services"
                                />

<table class=" w-full bg-slate-300 mt-8 border-stone-800 border px-2 py-2 " >
<thead class="px-2 py-2 bg-slate-200">
<th  class="px-2 py-2">name</th>
<th  class="px-2 py-2">quantity</th>
<th  class="px-2 py-2">actions</th>
</thead>
<tbody v-for="(service , index ) in addedServices"   class="px-2 py-2">
<td  class="px-2 py-2">
{{ service.name}}
</td>
<td  class="px-2 py-2">
<input type="text">
</td>
<td  class="px-2 py-2">
edit/delete
</td>

</tbody>
</table>



                    <div v-if="show">
                    <div class="grid grid-cols-5 gap-6  mb-6">

                            <InputGroup
                            label="discount_value"
                            v-model="form.discount_value"
                            :error-message="form.errors.discount_value"
                            required
                        />
                            <InputGroup
                            label="Total_before_discount"
                            v-model="form.Total_before_discount"
                            :error-message="form.errors.Total_before_discount"
                            required
                        />
                            <InputGroup
                            label="Total_after_discount"
                            v-model="form.Total_after_discount"
                            :error-message="form.errors.Total_after_discount"
                            required
                        />


                            <InputGroup
                            label="tax_rate"
                            v-model="form.tax_rate"
                            :error-message="form.errors.tax_rate"
                            required
                        />
                            <InputGroup
                            label="Total_with_tax"
                            v-model="form.Total_with_tax"
                            :error-message="form.errors.Total_with_tax"
                            required
                        />


                        <div class="mt-6 mb-4 ">
                            <CheckboxGroup
                                label="Active"
                                v-model:checked="form.status" 
                            />

                    </div>

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
