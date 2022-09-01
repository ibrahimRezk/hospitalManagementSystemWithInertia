<script setup>
    import { ref, watch } from "vue";
    
    import Card from "@/admin/Components/Card/Card.vue";
    import InputGroup from "@/admin/Components/InputGroup.vue";
    import SelectGroup from "@/admin/Components/SelectGroup.vue";
    
    const props = defineProps({
        modelValue: {
            type: Object,
            default: () => ({}),
        },
        sections: Array,
    });
    
    const emits = defineEmits(["update:modelValue"]); 
    
    const filters = ref({ ...props.modelValue });
    
    watch(
        filters,
        () => {
            emits("update:modelValue", filters.value);
        },
        {
            deep: true,
        }
    );
    </script>
    
    <template>
        <Card class="mb-4 bg-gray-100 rounded-md drop-shadow-lg border-b-white ">
            <template #header>  
                Filters
            </template> 


            <form class="grid grid-cols-4 gap-8">
                <InputGroup label="Name" v-model="filters.name" />
                <InputGroup label="Email" v-model="filters.email" type="email" />
                <InputGroup label="Address" v-model="filters.address" />
                <InputGroup label="Phone" v-model="filters.phone" />
    
                <SelectGroup
                label="Gender"
                v-model="filters.gender"
                :items="[
                    { id: 'Male', name: 'Male' },
                    { id: 'Female', name: 'Female' },
                ]"
            />

                <SelectGroup
                label="Gender"
                v-model="filters.blood_group"
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
            </form>
    
        
        </Card>
    </template>