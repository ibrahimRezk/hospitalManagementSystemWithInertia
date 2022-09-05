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
    
            <form class="grid grid-cols-3 gap-8"> 
                <InputGroup label="driver name"
                            v-model="filters.driver_name" />
                            
                <InputGroup label="car number"
                            v-model="filters.car_number" />

                <InputGroup label="car model"
                            v-model="filters.car_model" />
                            
                <InputGroup label="car_type"
                            v-model="filters.car_type" />

                <InputGroup label="driver license number"
                            v-model="filters.driver_license_number" />

         
        
                <SelectGroup
                    label="Active"
                    v-model="filters.is_available"
                    :items="[
                        { id: 1, name: 'Yes' },
                        { id: 0, name: 'No' },
                    ]"
                />
                
                
            </form>
        </Card>
    </template>