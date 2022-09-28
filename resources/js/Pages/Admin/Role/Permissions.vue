<script setup>
    import { computed, ref } from "vue";
    import { Inertia } from "@inertiajs/inertia";
    import Container from "@/Components/Container.vue";
    import Card from "@/Components/Card/Card.vue";
    import Button from "@/Components/Button.vue";
    import Input from "@/Components/Input.vue";
    
    const props = defineProps({
        role: {
            type: Object,
            default: () => {
                permissions: [];
            },
        },
        permissions: {
            type: Array,
        },
    });
    
    const search = ref("");
    
    const filteredPermissions = computed(() => {
        return props.permissions.filter((p) =>
            p.name.toLowerCase().includes(search.value.toLowerCase())
        );
    });
    
    const roleHasPermission = (permission) => { /// return boolean 
        return props.role.permissions.some((p) => p.id == permission.id);
    };
    
    const attachPermission = (permission) => {
        Inertia.post(
            route("admin.roles.attach-permission"),
            {
                roleId: props.role.id,
                permissionId: permission.id,
            },
            {
                preserveScroll: true,
                preserveState: true,
            }
        );
    };
    
    const detachPermission = (permission) => {
        Inertia.post(
            route("admin.roles.detach-permission"),
            {
                roleId: props.role.id,
                permissionId: permission.id,
            },
            {
                preserveScroll: true,
                preserveState: true,
            }
        );
    };
    </script>
    
    <template>
        <Container>
            <Card>
                <template #header> Permissions </template>
    
                <div class="w-1/4">
                    <Input
                        type="text"
                        class="mt-1 block w-full shadow-md"
                        v-model="search"
                        placeholder="Search ....."
                    />
                </div>
    
                <ul class="  mt-4 font-semibold capitalize align-middle bg-white border border-gray-100 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-md rounded-xl ">
                    <li
                        v-for="(permission, index) in filteredPermissions"
                        :key="permission.id"
                        class=" flex items-center justify-between px-8 py-1 hover:bg-gray-200  "
                        :class="{
                            'border-b': index < permissions.length - 1, 
                        }"
                    >
                        <div class="drop-shadow-md "
                            :class="{
                                ' text-slate-900  font-extrabold drop-shadow-md ':
                                    roleHasPermission(permission),
                            }"
                        >
                            {{ permission.name }}
                        </div>
                        <Button
                            v-if="roleHasPermission(permission)"
                            @click="detachPermission(permission)"
                            color="white"
                            >Detach</Button
                        >
                        <Button v-else @click="attachPermission(permission)"
                            color="black" >Attach</Button
                        >
                    </li>
                </ul>
            </Card>
        </Container>
    </template>
    