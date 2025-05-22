<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import GenericForm from '@/components/GenericForm.vue';
import { usersService } from '@/services/CrudService';

const props = defineProps({
    user: Object,
    roles: Array,
    userRoles: Array,
});

// Get current role
const currentRole = props.userRoles && props.userRoles.length > 0
    ? props.userRoles[0].name
    : 'medic';

// Create form data from user
const form = ref({
    name: props.user?.name || '',
    lastname: props.user?.lastname || '',
    email: props.user?.email || '',
    password: '',  // Empty by default for edit
    password_confirmation: '',
    role: currentRole,
});

// Navigation breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
    {
        title: 'Edit User',
        href: `/users/${props.user.id}/edit`,
    },
];

// Submit the form to update user
function submit() {
    usersService.update(props.user.id, form.value);
}

// Cancel and return to users list
function cancel() {
    usersService.navigateToList();
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Edit User" />

        <div class="container p-4">
            <GenericForm title="Edit User" description="Update user information" :isEdit="true"
                submitButtonText="Update User" @submit="submit" @cancel="cancel">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="name">First Name</Label>
                        <Input id="name" v-model="form.name" required />
                    </div>

                    <div class="space-y-2">
                        <Label for="lastname">Last Name</Label>
                        <Input id="lastname" v-model="form.lastname" required />
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" v-model="form.email" required />
                    </div>

                    <div class="space-y-2">
                        <Label for="password">Password (leave blank to keep current)</Label>
                        <Input id="password" type="password" v-model="form.password" />
                    </div>

                    <div class="space-y-2">
                        <Label for="password_confirmation">Confirm Password</Label>
                        <Input id="password_confirmation" type="password" v-model="form.password_confirmation" />
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <Label for="role">Role</Label>
                        <Select v-model="form.role">
                            <SelectTrigger>
                                <SelectValue placeholder="Select role" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="role in props.roles" :key="role.id" :value="role.name">
                                    {{ role.name.charAt(0).toUpperCase() + role.name.slice(1) }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
            </GenericForm>
        </div>
    </AppLayout>
</template>
