<script setup>
    import { onMounted, ref, watch, computed } from "vue";
    import { Head, Link } from "@inertiajs/inertia-vue3";
    import { Inertia } from "@inertiajs/inertia";
    import Layout from "@/Layouts/Authenticated.vue";
    import Container from "@/Components/Container.vue";
    import Card from "@/Components/Card/Card.vue";
    import Table from "@/Components/Table/Table.vue";
    import Td from "@/Components/Table/Td.vue";
    import Actions from "@/Components/Table/Actions.vue";
    import Button from "@/Components/Button.vue";
    import Modal from "@/Components/ConfirmationModal.vue";
    
    import Label from "@/Components/Label.vue";
    import Input from "@/Components/Input.vue";
    import AddNew from "@/Components/AddNew.vue";
    
    import useDeleteItem from "@/Composables/useDeleteItem.js"; 
    
    const props = defineProps({
        result: {
            type: Object,
            default: () => ({}),
        },
   
    }); 
    
 
    
    
    </script>
    <!-- {{ $t('Welcome') }} -->
    <template>
        <Head :title="title" />
    
        <Layout>
            <Container>
                <Card class="mt-2" :is-loading="isLoading" no-padding>

                    <div>
                        <label
                            class="  mb-3 mx-4 bg-blue-200 px-5 py-1 rounded-lg shadow-md"
                            >patient name :  {{ props.result.patient.name }}</label
                        >
        
                        <br />
                    </div>
                    <div>
                        <label
                        class="  mt-1 mx-4 bg-green-200 px-5 py-2 rounded-lg shadow-md"
                        >doctor name : {{props.result.doctor.name}}</label
                        >
                        <br>
                        <label
                        class="  mt-1 mx-4 bg-green-200 px-5 py-2 rounded-lg shadow-md"
                        >description : {{props.result.description}}</label
                        >
                    </div>
                    <hr class="mt-5 h-px bg-black" />
                    <label
                            class=" mt-4  mb-3 mx-4 bg-blue-200 px-5 py-1 rounded-lg shadow-md"
                            >employee name :  {{ props.result.employee.name }}</label
                        >
                    <Card class=" bg-white px-2 py-2 rounded-xl shadow-xl" :is-loading="isLoading" no-padding>

                    <div>
                        <label
                            class=" mt-2 mx-4 bg-red-200 px-5 py-2 rounded-lg shadow-md"
                            >description :                     
                            </label
                        >
                        <div class="  mt-3 px-3 py-3 bg-red-50 rounded-md shadow-lg font-bold text-red-700 mb-3">
                            {{ props.result.employee_description }}
                        </div>
                 
    
    
                    </div>
                </Card>
    

                </Card>
            </Container>
        </Layout>
    </template>
    