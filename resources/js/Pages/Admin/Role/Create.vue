<script setup>
    import { Head, useForm } from "@inertiajs/inertia-vue3";
    import Layout from "@/Layouts/Authenticated.vue";
    import Container from "@/Components/Container.vue";
    import Card from "@/Components/Card/Card.vue";
    import Button from "@/Components/Button.vue";
    import BreezeInput from "@/Components/Input.vue";
    import BreezeLabel from "@/Components/Label.vue";
    import InputError from "@/Components/InputError.vue";
    import Permissions from "./Permissions.vue";
    
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
        permissions: {
            type: Array,
        },
    });
    
    const form = useForm({
        name: props.item.name ?? "",
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
                        title: "Role Updated successfully",
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
                        title: "Role Created successfully",
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
                        <div>
                            <BreezeLabel value="Name" />
    
                            <BreezeInput
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                            />
    
                            <InputError class="mt-1" :message="form.errors.name" />
                        </div>
    
                        <div class="mt-4">
                            <Button :disabled="form.processing">
                                {{ form.processing ? "Saving..." : "Save" }}
                            </Button>
                        </div>
                    </form> 
                </Card>
            </Container>
    
            <Permissions
                v-if="edit"
                class="mt-6"
                :role="item"
                :permissions="permissions"
            />
        </Layout>
    </template>
    