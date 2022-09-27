<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Authenticated.vue";
import Container from "@/Components/Container.vue";
import Card from "@/Components/Card/Card.vue";
import Button from "@/Components/Button.vue";
import InputGroup from "@/Components/InputGroup.vue";
import SelectGroup from "@/Components/SelectGroup.vue";
import CheckboxGroup from "@/Components/CheckboxGroup.vue";

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
    employee_id: {
        type: Object,
        default: () => ({}),
    },
    routeResourceName: {
        type: String,
        required: true,
    },
});

const form = useForm({
    id:props.item.id,
    employee_id: props.item.employee_id ?? props.employee_id,
    employee_description: props.item.employee_description ?? "",
    patient_id: props.item.patient_id

    // name_ar: props.item.name_ar ?? "",
    // name_en: props.item.name_en ?? "",
    // email: props.item.email ?? "",
    // password: "",
    // passwordConfirmation: "",
    // // roleId: props.item.roles?.[0]?.id ?? "",   /// important   it will work only like this in create and edit cecase of the relation
    // phone: props.item.phone ?? "",
    // section_id: props.item.section?.id ?? "",  /// important   it will work only like this in create and edit cecase of the relation this is diffrent
    // appointments:props.item.appointments ?? "",
    // status: props.item.status ?? true,
});

const submit = () => {
    props.edit
        ? form.put(
              route(`admin.${props.routeResourceName}.update`, {
                  id: props.item.id,
              }),{
               
                onSuccess: ()=>{
                    Toast.fire({
                        icon: "success",
                        title: "Item Updated successfully",
                        iconColor: 'white',
                        color:'black',  // text color
                        background: '#1cac78        ', // green
                        // background: '#00a877       ', // green
                        // background: '#39ff14   ', // lime
                        // background: '#dc143c    ', // red
                    });
                  
              },
              },
          )
        : form.post(route(`radiologist.${props.routeResourceName}.store`),{
            onSuccess: ()=>{
                    Toast.fire({
                        icon: "success",
                        title: "Item Created successfully",
                        iconColor: 'white',
                        color:'black',  // text color
                        background: '#1cac78        ', // green
                        // background: '#00a877       ', // green
                        // background: '#39ff14   ', // lime
                        // background: '#dc143c    ', // red
                    });
                  
              },
        });
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
            <Card>
                <div>
                    <label
                        class=" mb-3 mx-4 bg-blue-200 px-5 py1 rounded-lg shadow-md"
                        >patient name :  {{ props.item.patient.name }}</label
                    >
    
                    <br />
                </div>
                <div>
                    <label
                        class="mt-1 mx-4 bg-green-200 px-5 py1 rounded-lg shadow-md"
                        >description : {{props.item.description}}</label
                    >
             

                    <hr class="mt-5 h-px bg-black" />

                </div>

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-6 mt-5">
                        <div>
                            <label
                                class=" mb-2 mx-4 bg-red-200 px-5 py1 rounded-lg shadow-md"
                                >employee description</label
                            >

                            <textarea
                                v-model="form.employee_description"
                                class="w-full resize border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                name="Text1"
                                cols="40"
                                rows="8"
                                :required="!edit"
                            />
                            <div>
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.employee_description"
                                />
                            </div>

                            <input type="file">

                            <hr class="mt-5 h-px bg-black" />

                            <br />
                        </div>
                    </div>

                    <div class="mt-4">
                        <Button :disabled="form.processing">
                            {{ form.processing ? "Saving..." : "Save" }}
                        </Button>
                    </div>
                </form>
            </Card>
        </Container>
    </Layout>
</template>
