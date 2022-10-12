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
        patients: {
            type: Array,
            default:[], 
        },
        routeResourceName: {
            type: String,
            required: true,
        },

    });
    
    const form = useForm({
        // name: props.item.name ?? "",
        patient_id: props.item.patient?.id ?? "",
        amount: props.item.amount ?? "", 
        description: props.item.description ?? "",
        
    
    
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
                        background: '#1cac78        ', // green
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
                        <div class="grid md:grid-cols-1 gap-6">

                            <SelectGroup
                            label="Patient"
                            v-model="form.patient_id"
                            :items="patients"
                            :error-message="form.errors.patient_id"
                            
                        />

                            <InputGroup
                            label="amount"
                            type="number"
                            v-model="form.amount"
                            :error-message="form.errors.amount"
                            />
                            <InputGroup
                            label="description"
                            v-model="form.description"
                            :error-message="form.errors.description"
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
    