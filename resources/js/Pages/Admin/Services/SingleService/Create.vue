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
    routeResourceName: { 
        type: String,
        required: true,
    },
    // roles: {
    //     type: Array,
    //     required: true, 
    // },
});

const form = useForm({
    // name: props.item.name ?? "",
    name_ar: props.item.name_ar ?? "",
    name_en: props.item.name_en ?? "",
    description_ar: props.item.description_ar ?? "",
    description_en: props.item.description_en ?? "",
    price: props.item.price ?? "",
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
                    <div class="grid grid-cols-1 gap-6">

                        <InputGroup
                            label="description ar"
                            v-model="form.description_ar"
                            :error-message="form.errors.description_ar"
                            required
                        />
                        <InputGroup
                            label="description en"
                            v-model="form.description_en"
                            :error-message="form.errors.description_en"
                            required
                        />

                        <InputGroup
                        type="number"
                            label="Price"
                            v-model="form.price"
                            :error-message="form.errors.price"
                            required
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
