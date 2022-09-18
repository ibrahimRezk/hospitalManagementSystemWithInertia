<script setup>
    import { Head, useForm, Link } from "@inertiajs/inertia-vue3";
    import { onMounted, ref, watch } from "vue";
    import { Inertia } from "@inertiajs/inertia";
    import Layout from "@/Layouts/Authenticated.vue";
    import Container from "@/Components/Container.vue";
    import Card from "@/Components/Card/Card.vue";
    import Table from "@/Components/Table/Table.vue";
    import Td from "@/Components/Table/Td.vue";
    import Actions from "@/Components/Table/Actions.vue";
    import Button from "@/Components/Button.vue";
    import Modal from "@/Components/ConfirmationModal.vue";
    import DialogModal from "@/Components/DialogModal.vue";
    import JetDropdown from "@/components/Dropdown.vue";
    import JetDropdownLink from "@/components/DropdownLink.vue";
    import InputError from "@/Components/InputError.vue";
    
    import Label from "@/Components/Label.vue";
    import Input from "@/Components/Input.vue";
    import AddNew from "@/Components/AddNew.vue";
    import YesNoLabel from "@/Components/YesNoLabel.vue";
    // import Filters from "./Filters.vue";
    
    import useDeleteItem from "@/Composables/useDeleteItem.js";
    import useDialogModal from "@/Composables/useDialogModal.js";
    import useFilters from "@/Composables/useFilters.js";
import { report } from "process";
import axios from "axios";
    
    const props = defineProps({
        errors: Object,
        title: {
            type: String,
            required: true,
        },
        items: {
            type: Object,
            default: () => ({}),
        },
        headers: {
            type: Array,
            default: () => [],
        },
        // filters: {
        //     type: Object,
        //     default: () => ({}),
        // },
        routeResourceName: {
            type: String,
            required: true,
        },
        // can: Object,
    
        method: String,
    });

    const data = ref('')

    const opened = ref(0);
    const showScreenExeptSubmenu = ref(false);


    // we can call a function to get the data usin axios not inertia like this if it is not in props  and  show it in template {{data.data.diagnose}}

    // const Report = (item)=>{
    //         axios.get(route(`patient.report`, {id : item.id }))
    //         .then(res =>{
    //             data.value = res
    //         // console.log(res)
    //         })  
    // }

    const {
    closeDialogModal,
    dialogModal,
    showDialogModal,
} = useDialogModal({
    opened,
    showScreenExeptSubmenu,
});

const fireOpenDialogModal = (item) => {
    // Report(item);  we can call this function if data is not in props but here we already have it
    data.value = item.diagnose
    return showDialogModal(item);
};


    
    const { filters, isLoading, isFilled } = useFilters({
        filters: props.filters,
        routeResourceName: props.routeResourceName,
        method: props.method,
    });
    
    // if you want to make color changed at once make the function in a new page not a modal
    const color = (item) => {
        return item.status == "completed"
            ? "green"
            : item.status == "reviewing"
            ? "yellow"
            : "red";
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
                <!-- <AddNew :show="isFilled">
                            <template #filters>
                                <Filters v-model="filters" :sections="sections" />
                            </template>
                        </AddNew> -->
    
                <Card class="mt-4" :is-loading="isLoading" no-padding>
                    <Table :headers="headers" :items="items">
                        <template #section>
                            <div
                                class="p-6 pb-0 mb-0 bg-gray-300 border-b-0 border-b-solid rounded-t-2xl border-b-transparent"
                            >
                                <h6>{{ props.title }}</h6>
                            </div>
                        </template>
                        <template v-slot="{ item }">
                        
                            <Td>
                                {{ item.service.name }}
                            </Td>
                            <Td>
                                <Button color="blue" small>
                                    {{ item.created_at_formatted }}
                                </Button>
                            </Td>
                            <Td>
                                <Button color="blue" small>
                                    {{ item.price }}
                                </Button>
                            </Td>
                            <Td>
                                <Button color="blue" small>
                                    {{ item.discount_value }}
                                </Button>
                            </Td>
                            <Td>
                                <Button color="blue" small>
                                    {{ item.tax_rate }}
                                </Button>
                            </Td>
                            <Td>
                                <Button color="blue" small>
                                    {{ item.tax_value }}
                                </Button>
                            </Td>
                            <Td>
                                <Button color="blue" small>
                                    {{ item.total_with_tax }}
                                </Button>
                            </Td>
                           
    
                            <Td>
                                <Button color="blue" small>
                                    {{ item.doctor.name }}
                                </Button>
                            </Td>


                         
    
                            <Td>
                                <Button small :color="color(item)">
                                    {{ item.invoice_status }}
                                </Button>
                            </Td>

                            <Td>
                                
                                    <Button small  @click="fireOpenDialogModal(item)" >
                                Report
                                </Button>
                            <!-- </Link> -->
                            </Td>
                        </template>
                    </Table>
                </Card>
            </Container>
        </Layout>
    
        <!-- //// importan when click on actions menu and submenu appears it suppose to disappear when clickin away this dive is for this with hideMenu function -->
        <div
            v-show="showScreenExeptSubmenu"
            class="fixed inset-0 transform transition-all"
            @click="hideMenu"
        >
            <div class="absolute inset-0 opacity-95" />
        </div>


        <!-- ///////////////modal///////////////////////////// -->

        <DialogModal
        :title="Report"
        :show="dialogModal"
        @close="closeDialogModal"
    >
        <template #title>
            <div class="bg-gray-800 px-4 py-2 text-white shadow-lg">
                Report
            </div>
        </template>

        <template #content>

            <label
                    class="mb-2 mx-4 bg-red-200 px-5 py1 rounded-lg shadow-md"
                    >diagnose</label
                >
                <label
                class=" py-4 w-full mb-2 bg-blue-200 px-5 py1 rounded-lg shadow-md">

               
                {{data.diagnose}}

                </label>
           
                <br />
                <hr class="mt-5 h-px  bg-black" />


                <label
                    class=" mt-5 mb-2 mx-4 bg-red-200 px-5 py1 rounded-lg shadow-md"
                    >medicine</label
                >
                <label
                class=" py-4 w-full mb-2 bg-blue-200 px-5 py1 rounded-lg shadow-md">

               
                {{ data.medicine }}

                </label>

        
        </template>

        <template #footer> </template>
    </DialogModal>
    
    </template>
    