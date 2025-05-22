<script setup lang="ts">
import { router, Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { computed, ref } from 'vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';

const props = defineProps({
    users: Array,
});

// Create refs for the confirmation dialog and the user to delete
const deleteConfirmDialog = ref(null);
const userToDelete = ref(null);

const page = usePage();
const user = page.props.auth?.user;

// Check if the user has specific permissions
const canCreate = computed(() => {
    if (!user?.permissions) return false;
    return user.permissions.some(p => p.name === 'create-user');
});

const canEdit = computed(() => {
    if (!user?.permissions) return false;
    return user.permissions.some(p => p.name === 'edit-user');
});

const canDelete = computed(() => {
    if (!user?.permissions) return false;
    return user.permissions.some(p => p.name === 'delete-user');
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

// Show the delete confirmation dialog
function confirmDeleteUser(userItem) {
    userToDelete.value = userItem;
    deleteConfirmDialog.value.open();
}

// Delete the user after confirmation
function deleteUser() {
    if (!userToDelete.value) return;

    router.delete(`/users/${userToDelete.value.id}`, {}, {
        onSuccess: () => {
            console.log('Delete successful');
            // The page will automatically refresh due to Inertia
        },
        onError: (errors) => {
            console.error('Delete failed:', errors);
            alert('Failed to delete user. Please try again.');
        }
    });
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Users" />

        <div class="container p-4">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Users</h1>
                <Button v-if="canCreate" variant="default" as="a" href="/users/create">Add User</Button>
            </div>

            <div class="rounded-md border">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr class="bg-muted/50">
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">ID</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Name</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Email</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border bg-background">
                        <tr v-for="u in props.users" :key="u.id" class="hover:bg-muted/50">
                            <td class="px-4 py-3 text-sm">{{ u.id }}</td>
                            <td class="px-4 py-3 text-sm">{{ u.name }}</td>
                            <td class="px-4 py-3 text-sm">{{ u.email }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <Button v-if="canEdit" variant="outline" size="sm" as="a"
                                        :href="`/users/${u.id}/edit`">Edit</Button>
                                    <Button v-if="canDelete" variant="destructive" size="sm"
                                        @click="confirmDeleteUser(u)">Delete</Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!props.users || props.users.length === 0">
                            <td colspan="4" class="px-4 py-3 text-center text-sm text-muted-foreground">No users found
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Confirmation Dialog for Delete -->
        <ConfirmDialog ref="deleteConfirmDialog" title="Delete User"
            :description="`Are you sure you want to delete ${userToDelete?.name}? This action cannot be undone.`"
            confirm-button-text="Delete" confirm-button-variant="destructive" :dangerous-action="true"
            @confirm="deleteUser" />
    </AppLayout>
</template>
