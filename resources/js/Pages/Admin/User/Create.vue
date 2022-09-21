<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Authenticated.vue";
import Container from "@/Components/Container.vue";
import Card from "@/Components/Card/Card.vue";
import Button from "@/Components/Button.vue";
import InputGroup from "@/Components/InputGroup.vue";
import SelectGroup from "@/Components/SelectGroup.vue";
import ImageUpload from "@/Components/ImageUpload.vue";


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
    roles: {
        type: Array,
        required: true, 
    },
});

const form = useForm({
    // name: props.item.name ?? "",
    name_ar: props.item.name_ar ?? "",
    name_en: props.item.name_en ?? "", 
    email: props.item.email ?? "",
    password: "",
    passwordConfirmation: "",
    roleId: props.item.roles?.[0]?.id ?? "",

});

const submit = () => {
    props.edit
        ? form.put(
              route(`admin.${props.routeResourceName}.update`, {
                  id: props.item.id,
              })
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
                <div class="col-span-2">
                    <ImageUpload
                                model-type="product"
                                :model-id="item.id"
                                
                            />
                                                            </div>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-2 gap-6">
                        <InputGroup
                            label="Name ar"
                            v-model="form.name_ar"
                            :error-message="form.errors.name"
                            required
                        />
                        <InputGroup
                            label="Name"
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
                            :error-message="form.errors.passwordConfirmation"
                            :required="!edit"
                        />

                        <SelectGroup
                            label="Role"
                            v-model="form.roleId"
                            :items="roles"
                            :error-message="form.errors.roleId"
                            required
                        />
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
