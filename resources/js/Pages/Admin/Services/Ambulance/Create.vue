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

    });
    
    const form = useForm({
        // name: props.item.name ?? "",
        driver_name_ar: props.item.driver_name_ar ?? "",
        driver_name_en: props.item.driver_name_en ?? "", 
        notes_ar: props.item.notes_ar ?? "",
        notes_en: props.item.notes_en ?? "", 

        car_number: props.item.car_number ?? "",
        car_model: props.item.car_model ?? "",
        car_year_made: props.item.car_year_made ?? "",
        driver_license_number: props.item.driver_license_number ?? "",
        driver_phone: props.item.driver_phone ?? "",
        is_available: props.item.is_available ?? true,
        car_type: props.item.car_type ?? "",

    
    
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
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <InputGroup
                            label="car number"
                            type='number'
                            min="55555"
                            v-model="form.car_number"
                            :error-message="form.errors.car_number"
                            required
                            />
                            <InputGroup
                            label="car model"
                            v-model="form.car_model"
                            :error-message="form.errors.car_model"
                            />
                            
                            <InputGroup
                            type='number'
                            label="car year made"
                            v-model="form.car_year_made"
                            :error-message="form.errors.car_year_made"
                            />
                            <InputGroup
                            label="car type"
                            v-model="form.car_type"
                            type='number'
                            :error-message="form.errors.car_type"
                            />

                            </div>
                            <div class="grid md:grid-cols-2 gap-6">

                            <InputGroup
                                label="Driver Name ar"
                                v-model="form.driver_name_ar"
                                :error-message="form.errors.driver_name_ar"
                                required
                            />
                            <InputGroup
                                label="Driver Name En"
                                v-model="form.driver_name_en"
                                :error-message="form.errors.driver_name_en"
                                required
                            />
                        </div>
                        <div class="grid md:grid-cols-2 gap-6">
                            

                            
                            <InputGroup
                            type='number'
                            label="driver license number"
                            v-model="form.driver_license_number"
                            :error-message="form.errors.driver_license_number"
                            />
                            <InputGroup
                            type='number'
                            label="driver phone"
                            v-model="form.driver_phone"
                            :error-message="form.errors.driver_phone"
                            />
                        

                            </div>
                            <div class="grid grid-cols-1 gap-6">


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
                                            label="is available"
                                            v-model:checked="form.is_available"
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
    