<script setup>
    import { Head, useForm } from "@inertiajs/inertia-vue3";
    import Layout from "@/Layouts/Authenticated.vue";
    import Container from "@/Components/Container.vue";
    import Card from "@/Components/Card/Card.vue";
    import Button from "@/Components/Button.vue";
    import InputGroup from "@/Components/InputGroup.vue";
    import SelectGroup from "@/Components/SelectGroup.vue";
    
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

        appointments: {
            type: Array,
            required: false, 
        },
    });
    
    const form = useForm({
        // name: props.item.name ?? "",
        name_ar: props.item.name_ar ?? "",
        name_en: props.item.name_en ?? "", 
        address_ar: props.item.address_ar ?? "",
        address_en: props.item.address_en ?? "", 
        email: props.item.email ?? "",

        


        birth_date: props.item.birth_date ?? "",
        phone: props.item.phone ?? "",
        blood_group: props.item.blood_group ?? "",
        gender: props.item.gender ?? "",
        
        roleId:4,
        password:props.item.phone ?? 'password',
        passwordConfirmation : props.item.phone ?? 'password',
    
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
                                label="Address ar"
                                v-model="form.address_ar"
                                :error-message="form.errors.address_ar"
                                required
                            />
                            <InputGroup
                                label="Address en"
                                v-model="form.address_en"
                                :error-message="form.errors.address_en"
                                required
                            />
                            </div>
                            <div class="grid grid-cols-3 gap-6">

                            <InputGroup
                                type="email"
                                label="Email"
                                v-model="form.email"
                                :error-message="form.errors.email"
                                required
                            />
    
                            <InputGroup
                                type="date"
                                label="birth date"
                                v-model="form.birth_date"
                                :error-message="form.errors.birth_date"
                            />
    
                        
                            <InputGroup
                                label="phone"
                                v-model="form.phone"
                                :error-message="form.errors.phone"
                            />
                            <div class="grid grid-cols-2 gap-6">

                            <SelectGroup
                label="Gender"
                v-model="form.gender"
                :items="[
                    { id: 'Male', name: 'Male' },
                    { id: 'Female', name: 'Female' },
                ]"
            />

                <SelectGroup
                label="Blood Groups"
                v-model="form.blood_group"
                :items="[
                    { id: 'A+',  name: 'A+' },
                    { id: 'B+',  name: 'B+' },
                    { id: 'AB+', name: 'AB+' },
                    { id: 'O+',  name: 'O+' },
                    { id: 'A-',  name: 'A-' },
                    { id: 'B-',  name: 'B-' },
                    { id: 'AB-', name: 'AB-' },
                    { id: '0-',  name: '0-' },
                ]"
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
    


    