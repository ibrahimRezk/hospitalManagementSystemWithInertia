<script setup>
    import { Head, useForm } from "@inertiajs/inertia-vue3";
    import Layout from "@/Layouts/Authenticated.vue";
    import Container from "@/Components/Container.vue";
    import Card from "@/Components/Card/Card.vue";
    import Button from "@/Components/Button.vue";
    import InputGroup from "@/Components/InputGroup.vue";
    import SelectGroup from "@/Components/SelectGroup.vue";
    import CheckboxGroup from "@/Components/CheckboxGroup.vue";
    import { computed, watch } from "@vue/runtime-core";
    
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
        groups: {
            type: Array,
            required: true,
        },
        doctors: {
            type: Array,
            required: true,
        },
        patients: {
            type: Array,
            required: true,
        },
        sections: {
            type: Array,
            required: true,
        },
        type: {
            type: Array,
            required: true,
        },
    });
    
    const form = useForm({

        patient: props.item.patient?.id ?? "", 
    doctor: props.item.doctor?.id ?? "",
    section: props.item.section?.id ?? "",
    type: props.item.type ?? "",

    group: props.item.group?.id ?? "", 


        discount_value: props.item.discount_value ?? 0,
    
        price: props.item.price ?? "",
        tax_rate: props.item.tax_rate ?? "",
        tax_value: props.item.tax_value ?? "",
        total_with_tax: props.item.total_with_tax ?? "",
    });
    
    // to add section automatically after choosing a doctor
    watch(
        () => form.doctor,
        () => findSection.value
    );
    
    const findSection = computed(() => {
        if (form.doctor !== "") {
            let doctor = props.doctors.find((doctor) => doctor.id == form.doctor);
            form.section = doctor.section.id;
        } else {
            return "";
        }
    });
    ////////////////////////////////////////////////////////////
    
    // to add all service items automatically after choosing a service
    watch(
        () => form.group,
        () => findServiceValues.value
    );
    watch(
        () => form.discount_value,
        () => findServiceValues.value
    );
    watch(
        () => form.tax_rate,
        () => findServiceValues.value
    );
    
    const findServiceValues = computed(() => { 
        if (form.group !== "") {
            let group = props.groups.find(
                (group) => group.id == form.group
            );
console.log(group)
            form.price = group.Total_before_discount;
            form.discount_value= group.discount_value
            form.tax_rate = group.tax_rate
            form.tax_value = ((group.Total_before_discount - form.discount_value)) *form.tax_rate  / 100;
            form.total_with_tax = group.Total_with_tax;
        } else {
            return "";
        }
    });
    // const findServiceValues = computed(() => { 
    //     if (form.group !== "") {
    //         let group = props.groups.find(
    //             (group) => group.id == form.group
    //         );
    //         console.log(group)
    //         let tax_value = ((group.Total_before_discount - form.discount_value)) *form.tax_rate  / 100;
    //         let total_with_tax =
    //             parseInt(group.Total_before_discount) + tax_value - form.discount_value;
    //         form.price = group.Total_before_discount;
    //         form.tax_value = tax_value;
    //         form.total_with_tax = total_with_tax;
    //     } else {
    //         return "";
    //     }
    // });
    ////////////////////////////////////////////////////////////
    
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
                        <div class="grid grid-cols-4 gap-6">
                            <SelectGroup
                                label="Patient"
                                v-model="form.patient"
                                :items="patients"
                                :error-message="form.errors.paitent"
                            />
    
                            <SelectGroup
                                label="Doctor"
                                v-model="form.doctor"
                                :items="doctors"
                                :error-message="form.errors.doctor"
                            />
    
                            <SelectGroup
                                disabled
                                label="Section"
                                v-model="form.section"
                                :items="sections"
                                :error-message="form.errors.section"
                            />
    
                            <SelectGroup
                                label="Invoice type"
                                v-model="form.type"
                                :items="type"
                                :error-message="form.errors.type"
                            />
                        </div>
    
                        <hr class="h-px mt-12 bg-slate-700" />
    
                        <div class="grid grid-cols-6 gap-6 mt-10">
                            <SelectGroup
                                withoutSelect
                                label="Services Groups"
                                v-model="form.group"
                                :items="groups"
                                :error-message="form.errors.group"
                            />
    
                            <InputGroup
                                disabled
                                type="number"
                                label="Price"
                                v-model="form.price"
                                :error-message="form.errors.price"
                            />
                            <InputGroup
                            disabled
                            min="0"
                                type="number"
                                label="discount value"
                                v-model="form.discount_value"
                                :error-message="form.errors.discount_value"
                            />
                            <InputGroup
                            disabled
                            min="0"
                                type="number"
                                label="tax rate"
                                v-model="form.tax_rate"
                                :error-message="form.errors.tax_rate"
                            />
                            <InputGroup
                            min="0"
                                disabled
                                type="number"
                                label="tax value"
                                v-model="form.tax_value"
                                :error-message="form.errors.tax_value"
                            />
                            <InputGroup
                            min="0"
                                disabled
                                type="number"
                                label="total with tax"
                                v-model="form.total_with_tax"
                                :error-message="form.errors.total_with_tax"
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
    