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
    
                <!-- <SelectGroup
                label="Gender"
                v-model="filters.gender"
                :items="[
                    { id: 1, name: 'Male' },
                    { id: 0, name: 'Female' },
                ]"
            />

                <SelectGroup
                label="Gender"
                v-model="filters.blood_group"
                :items="[
                    { id: 0, name: 'A+' },
                    { id: 1, name: 'B+' },
                    { id: 2, name: 'AB+' },
                    { id: 3, name: 'O+' },
                    { id: 4, name: 'A-' },
                    { id: 5, name: 'B-' },
                    { id: 6, name: 'AB-' },
                    { id: 7, name: '0-' },
                ]"
            /> -->
            </form>
    
        
        </Card>
    </template>