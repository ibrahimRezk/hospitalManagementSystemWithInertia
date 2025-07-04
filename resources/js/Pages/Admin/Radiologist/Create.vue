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
import { watch, ref, computed , onMounted } from "@vue/runtime-core";

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
    phone: props.item.phone ?? "",
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


const submit = () => {
    props.edit
        ? form.post(
              //   route(`admin.${props.routeResourceName}.update`, {  // not working with multipart/formData   can not update files or images
              route(`admin.${props.routeResourceName}.updateRadiologist`, {
                  _method: "put", // we use it like this and modify route to be post not put and re enter _mothod :put because when uploading files like images it is not supported in inetia  ,, remember to create new put route in routes
                  id: props.item.id,
              }),{
                onSuccess: ()=>{
                    Toast.fire({
                        icon: "success",
                        title: "Radiologist Updated successfully",
                        iconColor: 'white',
                        color:'black',  // text color
                        background: '#6699ff', // blue
                        // background: '#00a877       ', // green
                        // background: '#39ff14   ', // lime
                        // background: '#dc143c    ', // red
                    });
                  }
              },
              {
                  preserveState: true,
              }
          )
        : form.post(route(`admin.${props.routeResourceName}.store`),{
            onSuccess: ()=>{
                    Toast.fire({
                        icon: "success",
                        title: "Radiologist Created successfully",
                        iconColor: 'white',
                        color:'black',  // text color
                        background: '#6699ff', // blue
                        // background: '#00a877       ', // green
                        // background: '#39ff14   ', // lime
                        // background: '#dc143c    ', // red
                    });
                  }
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
                <form @submit.prevent="submit">
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="grid md:grid-cols-2 col-span-2 gap-6">
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

                                <input
                                class=" w-60"
                                    type="file"
                                    @input="form.image = $event.target.files[0]"
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
                            <div class="mx-auto">
                                <img
                                    style="border-radius: 10%"
                                    width="300"
                                    id="output"
                                    :src="currentImage"
                                    class="shadow-lg rounded p-1"
                                />
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
