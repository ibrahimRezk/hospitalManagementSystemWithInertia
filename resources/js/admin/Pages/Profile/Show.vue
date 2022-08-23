<script setup>

import AppLayout from '@/admin/Layouts/Authenticated.vue';
// import AppLayout from '@/admin/Layouts/AppLayout.vue';
import DeleteUserForm from '@/admin/Pages/Profile/Partials/DeleteUserForm.vue';
import JetSectionBorder from '@/admin/Components/SectionBorder.vue';
import LogoutOtherBrowserSessionsForm from '@/admin/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import TwoFactorAuthenticationForm from '@/admin/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/admin/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/admin/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import Container from "@/admin/Components/Container.vue";


defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <AppLayout title="Profile">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profile
            </h2>
        </template>

<Container>


                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <UpdateProfileInformationForm :user="$page.props.user" />

                    <JetSectionBorder />
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <UpdatePasswordForm class="mt-10 sm:mt-0" />

                    <JetSectionBorder />
                </div>

                <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                    <TwoFactorAuthenticationForm 
                        :requires-confirmation="confirmsTwoFactorAuthentication"
                        class="mt-10 sm:mt-0" 
                    />

                    <JetSectionBorder />
                </div>

                <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <JetSectionBorder />

                    <DeleteUserForm class="mt-10 sm:mt-0 " />
                </template>
        </Container>
    </AppLayout>
</template>
