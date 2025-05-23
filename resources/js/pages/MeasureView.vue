<script setup lang="ts">
import { usePage, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { computed, ref } from 'vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import { measuresService } from '@/services/CrudService';

// Define TypeScript interfaces for our data
interface Measure {
    id: number;
    name: string;
    body_zone: string;
    value: number;
    unit: string;
    image_path?: string;
    patient_id: number;
    patient_name: string;
}

interface UserPermission {
    name: string;
}

interface UserRole {
    name: string;
}

interface User {
    permissions: UserPermission[];
    roles: UserRole[];
}

interface PageProps {
    auth: {
        user: User;
    };
    [key: string]: any;
}

const props = defineProps<{
    measures?: Measure[];
}>();

// Create refs for the confirmation dialog and the item to delete
const deleteConfirmDialog = ref<{ open: () => void } | null>(null);
const measureToDelete = ref<Measure | null>(null);

const page = usePage<PageProps>();
const user = computed(() => page.props.auth?.user);

// Check if the user has specific permissions
const canCreate = computed(() => {
    if (!user.value?.permissions) return false;
    return user.value.permissions.some((p: UserPermission) => p.name === 'create-measure');
});

const canEdit = computed(() => {
    if (!user.value?.permissions) return false;
    return user.value.permissions.some((p: UserPermission) => p.name === 'edit-measure');
});

const canDelete = computed(() => {
    if (!user.value?.permissions) return false;
    return user.value.permissions.some((p: UserPermission) => p.name === 'delete-measure');
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Measures',
        href: '/measures',
    },
];

// Function to get full image URL
function getImageUrl(path?: string): string | null {
    if (!path) return null;
    return `/storage/${path}`;
}

// Show the delete confirmation dialog
function confirmDeleteMeasure(measure: Measure): void {
    measureToDelete.value = measure;
    deleteConfirmDialog.value?.open();
}

// Delete the measure after confirmation
function deleteMeasure(): void {
    if (!measureToDelete.value) return;
    measuresService.delete(measureToDelete.value.id, {
        onSuccess: () => {
            console.log('Delete successful');
        },
        onError: (errors: any) => {
            console.error('Delete failed:', errors);
            alert('Failed to delete measure. Please try again.');
        }
    });
}

function editMeasure(id: number): void {
    measuresService.navigateToEdit(id);
}

// Safe image open function
function openImageInNewTab(url: string | null): void {
    if (url) window.open(url, '_blank');
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Measures" />

        <div class="container p-4">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Measures</h1>
                <Button v-if="canCreate" variant="default" as="a" href="/measures/create">Add Measure</Button>
            </div>

            <div class="rounded-md border">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr class="bg-muted/50">
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">ID</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Name</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Body Zone</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Value</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Unit</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Image</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Patient</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border bg-background">
                        <tr v-for="measure in props.measures" :key="measure.id" class="hover:bg-muted/50">
                            <td class="px-4 py-3 text-sm">{{ measure.id }}</td>
                            <td class="px-4 py-3 text-sm">{{ measure.name }}</td>
                            <td class="px-4 py-3 text-sm">{{ measure.body_zone }}</td>
                            <td class="px-4 py-3 text-sm">{{ measure.value }}</td>
                            <td class="px-4 py-3 text-sm">{{ measure.unit }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div v-if="measure.image_path" class="h-10 w-10 rounded-md overflow-hidden">
                                    <img :src="getImageUrl(measure.image_path) || undefined" alt="Body image"
                                        class="h-full w-full object-cover cursor-pointer"
                                        @click="openImageInNewTab(getImageUrl(measure.image_path))" />
                                </div>
                                <span v-else>No image</span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <!-- Display patient name instead of ID -->
                                <a :href="`/patients/${measure.patient_id}`" class="hover:underline text-primary">
                                    {{ measure.patient_name }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <Button v-if="canEdit" variant="outline" size="sm"
                                        @click="editMeasure(measure.id)">Edit</Button>
                                    <Button v-if="canDelete" variant="destructive" size="sm"
                                        @click="confirmDeleteMeasure(measure)">Delete</Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!props.measures || props.measures.length === 0">
                            <td colspan="8" class="px-4 py-3 text-center text-sm text-muted-foreground">No measures
                                found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Confirmation Dialog for Delete -->
        <ConfirmDialog ref="deleteConfirmDialog" title="Delete Measure"
            :description="`Are you sure you want to delete measure '${measureToDelete?.name}'? This action cannot be undone.`"
            confirm-button-text="Delete" confirm-button-variant="destructive" :dangerous-action="true"
            @confirm="deleteMeasure" />
    </AppLayout>
</template>

<style scoped></style>
