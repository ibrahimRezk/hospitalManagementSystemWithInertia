<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Layout from "@/admin/Layouts/Authenticated.vue";
import Container from "@/admin/Components/Container.vue";
import Card from "@/admin/Components/Card/Card.vue";
import Button from "@/admin/Components/Button.vue";
import InputGroup from "@/admin/Components/InputGroup.vue";
import SelectGroup from "@/admin/Components/SelectGroup.vue";
import CheckboxGroup from "@/admin/Components/CheckboxGroup.vue";
import { ref } from "@vue/reactivity";
import { computed, onMounted, watch } from "@vue/runtime-core";
import Table from "@/admin/Components/Table/Table.vue";
import Trash from "@/admin/Components/Icons/Trash.vue";

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
    services: {
        type: Object,
        default: () => ({}),
    },
    servicesWithinGroup: {
        type:Object,
                default: () => ({}),

    }
});



// const quantity = ref(1)

const Total_before_discount = ref(0);
const discount_value = ref(0);
const Total_after_discount = ref(0);
const tax_rate = ref(14);
const Total_with_tax = ref(0);
const status = ref(true);

const choosenService = ref({});

const addedServices = ref([]);


if(props.edit ==  true) {
props.servicesWithinGroup.forEach((element)=> {
element.quantity = element.pivot.quantity
addedServices.value.push(element)
});
}

onMounted(()=>{   // we did this here because in edit mode we have to bring data from database not refs
    if(props.edit){
        Total_before_discount.value = props.item.Total_before_discount;
        Total_after_discount.value = props.item.Total_after_discount;
        Total_with_tax.value =props.item.Total_with_tax;
        discount_value.value = props.item.discount_value;
        tax_rate.value = props.item.tax_rate


    }
})

// remember to fix total befor discount and total after discount and total with tax to be just numbers not input becase any one can change it from page inspect

const form = useForm({
    // name: props.item.name ?? "",
    name_ar: props.item.name_ar ?? "",
    name_en: props.item.name_en ?? "",
    notes_ar: props.item.notes_ar ?? "",
    notes_en: props.item.notes_en ?? "",

    status: props.item.status ?? status,
    addedServices: addedServices.value ?? [],
    
    discount_value:  discount_value,
    tax_rate: tax_rate,
    Total_before_discount: Total_before_discount, 
    Total_after_discount:Total_after_discount,
    Total_with_tax: Total_with_tax,

    
});



watch(
    () => choosenService.value,
    () => addService.value
);

watch(
    () => choosenService.value,
    () => primaryTotalBeforeDiscount.value
);


const addService = computed(() => {

    if (!choosenService.value) return [];

    let singleService = props.services.find( 
        (c) => c.id == choosenService.value
    );
    if (!singleService) return [];
        singleService.quantity = 1  // new field added to collect numbers of item

    let RepeatedService = addedServices.value.find((c)=> c.id == singleService.id)
    if (RepeatedService) return ;
    
    addedServices.value.push(singleService);
    console.log(addedServices)
    choosenService.value = {}
});

watch(
    addedServices.value,
    () => {primaryTotalBeforeDiscount.value} , { deeep:true}
);
// watch(
//     ()=>discount_value.value,
//     () => {primaryTotalBeforeDiscount.value} , { deeep:true}
// );



    ///// to add numbers of service
    // we added a new field to single service called quantity   in addService function
    // then we bind it in v model in th v-for in template down with it's place in the array of addedServices like this
    //  v-model="addedServices[index].quantity" 
    // we added watch on addedeSrvices for any change on numbers and we make it "deep" because it is an array
 




const primaryTotalBeforeDiscount = computed(() => {
    Total_before_discount.value = 0; // we have to make it zero because we will calculate all items again
    addedServices.value.forEach((element) => {
        Total_before_discount.value = Total_before_discount.value += ((parseInt(element.price)) * parseInt(element.quantity));
        // Total_before_discount.value = Total_before_discount.value += (parseInt(element.price)) * (parseInt(element.quantity));
         // we use parseint here because it is saved as string in array
        Total_after_discount.value = Total_before_discount.value;
    });
});
watch(
    () => discount_value.value,
    () => primaryTotalAfterDiscount.value
);
const primaryTotalAfterDiscount = computed(() => {
    Total_after_discount.value =
        Total_before_discount.value - discount_value.value;
});

const removeServiceFromList = (index) => {
    addedServices.value.splice(index, 1);
    choosenService.value = {};
};

watch(
    () => Total_after_discount.value,
    () => finalTotalWithTax.value
);
watch(
    () => tax_rate.value,
    () => finalTotalWithTax.value
);
watch(
    () => Total_before_discount.value,
    () => primaryTotalAfterDiscount.value
);


const finalTotalWithTax = computed(() => {
    Total_with_tax.value = Math.round(
        Total_after_discount.value *
            (1 + (tax_rate.value ? tax_rate.value : 0) / 100)
    );
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

const show = ref(props.edit);


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
                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <InputGroup
                                label="notes ar"
                                v-model="form.notes_ar"
                                :error-message="form.errors.notes_ar"
                                required
                            />
                            <InputGroup
                                label="notes en"
                                v-model="form.notes_en"
                                :error-message="form.errors.notes_en"
                                required
                            />
                        </div>

                        <hr
                            class="h-px mt-10 bg-black bg-gradient-horizontal-dark"
                        />
                    </div>
                    <div>
                        <button
                        type="button"
                            @click="show = true"
                            class="bg-cyan-900 px-3 py-2 rounded-md text-gray-100 mt-8 mb-8"
                        >
                            Add single service
                        </button>

                        <div v-if="show " >
                            <div class="grid grid-cols-1 sm:grid-cols-2">
                                <div
                                    class="md:px-20 border border-gray-400 rounded-md p-5 mx-2 my-1"
                                >
                                    <div class="w-full md:w-1\2">
                                        <SelectGroup
                                        withoutSelect
                                            label="services"
                                            v-model="choosenService"
                                            :items="services"
                                            :error-message="
                                                form.errors.services
                                            "
                                        />

                                        <table
                                            class="items-center w-full align-top border-gray-200 text-slate-500 my-6"
                                        >
                                            <thead
                                                class="align-bottom bg-gray-600"
                                            >
                                                <tr>
                                                    <th
                                                        class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                                    >
                                                        name
                                                    </th>
                                                    <th
                                                        class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                                    >
                                                        quantity
                                                    </th>

                                                    <th
                                                        class="px-6 py-3 font-bold rtl:text-right ltr:text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-200 opacity-90"
                                                    >
                                                        actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                v-for="(
                                                    service, index
                                                ) in addedServices"
                                                class="px-2 py-2 bg-neutral-300 hover:bg-neutral-400"
                                            >
                                                <tr>
                                                    <td
                                                        class="px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                                    >
                                                        <div
                                                            class="flex px-2 py-1"
                                                        >
                                                            <div
                                                                class="flex-col justify-center"
                                                            >
                                                                <h6
                                                                    class="mb-0 leading-normal text-size-sm"
                                                                >
                                                                    {{
                                                                        service.name
                                                                    }}
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                                    >
                                                        <div
                                                            class="flex px-2 py-1"
                                                        >
                                                            <div
                                                                class="flex-col justify-center"
                                                            >
<input v-model="addedServices[index].quantity" type="number" min="1" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-30 ">
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td
                                                        class="px-4 py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg"
                                                    >
                                                        <Trash
                                                            class="w-4 h-4 text-red-700"
                                                            @click="
                                                                removeServiceFromList(
                                                                    index
                                                                )
                                                            "
                                                        />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div
                                    class="md:px-20 border border-gray-400 rounded-md p-5 mx-2 my-1"
                                >
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 w-full md:w-1\2 gap-6 mb-6"
                                    >
                                        <InputGroup
                                            type="Number"
                                            label="discount value"
                                            v-model="form.discount_value"
                                            :error-message="
                                                form.errors.discount_value
                                            "
                                            required
                                        />

                                        <InputGroup
                                            type="Number"
                                            label="tax rate"
                                            v-model="form.tax_rate"
                                            :error-message="
                                                form.errors.tax_rate
                                            "
                                            required
                                        />
                                    </div>

                                    <label
                                        for=""
                                        class="bg-slate-700 py-1 rounded-md w-full md:w-1\2 px-4 text-white my-2"
                                    >
                                        Total Before Discount :
                                        {{ form.Total_before_discount }}
                                    </label>
                                    <br />
                                    <label
                                        for=""
                                        class="bg-slate-700 py-1 rounded-md w-full md:w-1\2 px-4 text-white my-2"
                                    >
                                        Total After Discount :
                                        {{ form.Total_after_discount }}
                                    </label>
                                    <br />
                                    <hr
                                        class="h-px mt-4 bg-black bg-gradient-horizontal-dark"
                                    />
                                    <label
                                        for=""
                                        class2="bg-slate-700 px-3 py-1 rounded-md w-full md:w-1/2 text-yellow-500 my-2 mt-5"
                                        class="bg-slate-700 py-1 rounded-md w-full md:w-1\2 px-4 text-yellow-500 my-2 mt-5"
                                    >
                                        Total With Tax :
                                        {{ form.Total_with_tax }}
                                    </label>

                                    <div class="mt-2 mb-4">
                                        <CheckboxGroup
                                            label="Active"
                                            v-model:checked="form.status"
                                        />
                                    </div>
                                    <div class="mt-8">
                                        <Button :disabled="form.processing">
                                            {{
                                                form.processing
                                                    ? "Saving..."
                                                    : "Save"
                                            }}
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </Card>
        </Container>
    </Layout>
</template>
