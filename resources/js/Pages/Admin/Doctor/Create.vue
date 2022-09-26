<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Authenticated.vue";
import Container from "@/Components/Container.vue";
import Card from "@/Components/Card/Card.vue";
import Button from "@/Components/Button.vue";
import InputGroup from "@/Components/InputGroup.vue";
import SelectGroup from "@/Components/SelectGroup.vue";
import CheckboxGroup from "@/Components/CheckboxGroup.vue";
import ImageUpload from "@/Components/ImageUpload.vue";
import { watch, ref } from "@vue/runtime-core";

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

    sections: {
        type: Array,
        required: true,
    },
    appointments: {
        type: Array,
        required: false,
    },
});
const maxUploadImageCount = 1;
const currentImage = ref(props.item.images?.[0]?.img.original_url ?? null);

const form = useForm({
    // name: props.item.name ?? "",
    name_ar: props.item.name_ar ?? "",
    name_en: props.item.name_en ?? "",
    email: props.item.email ?? "",
    password: "",
    passwordConfirmation: "",
    // roleId: props.item.roles?.[0]?.id ?? "",   /// important   it will work only like this in create and edit cecase of the relation
    phone: props.item.phone ?? "",
    section_id: props.item.section?.id ?? "", /// important   it will work only like this in create and edit cecase of the relation this is diffrent
    appointments: props.item.appointments ?? [],
    status: props.item.status ?? true,
    image: null,
});

var loadFile = function (event) {
    var output = document.getElementById("output");
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src); // free memory
    };
};

// Inertia.post(`/users/${user.id}`, {
//   _method: 'put',
//   avatar: form.avatar,
// })

const submit = () => {
    props.edit
        ? form.post(
              //   route(`admin.${props.routeResourceName}.update`, {  // not working with multipart/formData   can not update files or images
              route(`admin.${props.routeResourceName}.updateDoctor`, {
                  _method: "put", // we use it like this and modify route to be post not put and re enter _mothod :put because when uploading files like images it is not supported in inetia  ,, remember to create new put route in routes
                  id: props.item.id,
              }),
              {
                  preserveState: true,
              }
          )
        : form.post(route(`admin.${props.routeResourceName}.store`));
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
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="grid grid-cols-2 col-span-2 gap-6">
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

                            <InputGroup
                                type="email"
                                label="Email"
                                v-model="form.email"
                                :error-message="form.errors.email"
                                required
                            />
                            <InputGroup
                                label="phone"
                                v-model="form.phone"
                                :error-message="form.errors.phone"
                                :required="!edit"
                            />

                            <InputGroup
                                minlength="8"
                                type="password"
                                label="Password"
                                v-model="form.password"
                                :error-message="form.errors.password"
                                :required="!edit"
                            />

                            <InputGroup
                                minlength="8"
                                type="password"
                                label="Confirm Password"
                                v-model="form.passwordConfirmation"
                                :error-message="
                                    form.errors.passwordConfirmation
                                "
                                :required="!edit"
                            />

                            <SelectGroup
                                label="Section"
                                v-model="form.section_id"
                                :items="sections"
                                :error-message="form.errors.section"
                                required
                            />
                            <div class=" mt-7 mx-3 grid grid-cols-2  gap-6">
                                <div class="">
                                    <CheckboxGroup
                                    class=" bg-gray-100 rounded-md py-2 px-5  shadow-md"
                                        label="Active"
                                        v-model:checked="form.status"
                                    />
                                </div>

                                <div class="">
                                    <input
                                        type="file"
                                        @input="
                                            form.image = $event.target.files[0]
                                        "
                                        @change="loadFile($event)"
                                    />
                                    <progress
                                        v-if="form.progress"
                                        :value="form.progress.percentage"
                                        max="100"
                                    >
                                        {{ form.progress.percentage }}%
                                    </progress>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">

                            <div class="rtl:mr-6 ltr:ml-6">
                            <label 
                                >Appointments
                            </label>
                                <ul>
                                    <li
                                        v-for="(
                                            appointment, index
                                        ) in appointments"
                                        :key="index"
                                        class="bg-blue-300 rounded-lg mt-1 w-28"
                                    >
                                        <input
                                            type="checkbox"
                                            :id="appointment.id"
                                            :value="appointment.id"
                                            v-model="form.appointments"
                                            class="mx-1 rounded-xl"
                                        />
                                        <label :for="appointment.id">{{
                                            appointment.name
                                        }}</label>
                                    </li>
                                </ul>
                            </div>

                            <div class="mx-auto">
                                <img
                                    style="border-radius: 10%"
                                    width="200"
                                    id="output"
                                    :src="currentImage"
                                    class="shadow-lg rounded p-1"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <Button :disabled="form.processing">
                            {{ form.processing ? "Saving..." : "Save" }}
                        </Button>
                    </div>
                </form>
            </Card>
        </Container>
    </Layout>
</template>
