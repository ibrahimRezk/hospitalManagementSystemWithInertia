<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Layout from "@/admin/Layouts/Authenticated.vue";
import Container from "@/admin/Components/Container.vue";
import Card from "@/admin/Components/Card/Card.vue";
import Button from "@/admin/Components/Button.vue";
import InputGroup from "@/admin/Components/InputGroup.vue";
import SelectGroup from "@/admin/Components/SelectGroup.vue";
import CheckboxGroup from "@/admin/Components/CheckboxGroup.vue";


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

const form = useForm({
    // name: props.item.name ?? "",
    name_ar: props.item.name_ar ?? "",
    name_en: props.item.name_en ?? "", 
    email: props.item.email ?? "",
    password: "",
    passwordConfirmation: "",
    phone: props.item.phone ?? "",
    status: props.item.status ?? "",



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
                        <InputGroup
                            label="phone"
                            v-model="form.phone"
                            :error-message="form.errors.phone"
                            :required="!edit"
                        />

                        <div class="mt-3 mb-4">
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
                </form>
            </Card>
        </Container>
    </Layout>
</template>
