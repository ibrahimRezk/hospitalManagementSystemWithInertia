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
        roles: {
            type: Array,
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
    
    const form = useForm({
        // name: props.item.name ?? "",
        name_ar: props.item.name_ar ?? "",
        name_en: props.item.name_en ?? "", 
        notes_ar: props.item.notes_ar ?? "",
        notes_en: props.item.notes_en ?? "", 
        insurance_code: props.item.insurance_code ?? "",
    
        discount_percentage:props.item.discount_percentage ?? "",
        Company_rate:props.item.Company_rate ?? "",
        status:props.item.status ??true
    
    
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
                        background: '#6699ff', // blue
                        // background: '#00a877       ', // green
                        // background: '#39ff14   ', // lime
                        // background: '#dc143c    ', // red
                    });
                  }
                  }
              )
            : form.post(route(`admin.${props.routeResourceName}.store`),{
                onSuccess: ()=>{
                    Toast.fire({
                        icon: "success",
                        title: "Item Created successfully",
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
                        <div class="grid md:grid-cols-2 gap-6">
                            <InputGroup
                                label="Name ar"
                                v-model="form.name_ar"
                                :error-message="form.errors.name"
                                required
                            />
                            <InputGroup
                                label="Name En"
                                v-model="form.name_en"
                                :error-message="form.errors.name"
                                required
                            />
                        </div>
                        <div class="grid md:grid-cols-3 gap-6">
                            
                            <InputGroup
                            label="insurance code"
                            v-model="form.insurance_code"
                            :error-message="form.errors.insurance_code"
                            required
                            />
                            
                            <InputGroup
                            type='number'
                            label="discount percentage"
                            v-model="form.discount_percentage"
                            :error-message="form.errors.discount_percentage"
                            />
                            
                            <InputGroup
                            type='number'
                            label="Company_rate"
                            v-model="form.Company_rate"
                            :error-message="form.errors.Company_rate"
                            />
                            
                            </div>
                            <div class="grid md:grid-cols-1 gap-6">


                            <InputGroup
                                label="notes ar"
                                v-model="form.notes_ar"
                                :error-message="form.errors.notes"
                                required
                            />
                            <InputGroup
                                label="notes En"
                                v-model="form.notes_en"
                                :error-message="form.errors.notes_ar"
                                required
                            />


                            </div>
                            <div class="mt-8 mb-4 mx-2">
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
                    </form>
                </Card>
            </Container>
        </Layout>
    </template>
    