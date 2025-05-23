<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { computed, onMounted, ref } from 'vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import { ActivitySquare } from 'lucide-vue-next';

interface Patient {
    id: number;
    name: string;
    last_name: string;
    gender: string;
    age: number;
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
    patients: Patient[];
}>();

const deleteConfirmDialog = ref<any>(null);
const patientToDelete = ref<Patient | null>(null);

const page = usePage<PageProps>();
const user = computed(() => page.props.auth?.user);

onMounted(() => {
    console.log('User:', user.value);
    console.log('User permissions:', user.value?.permissions);
    console.log('User roles:', user.value?.roles);
    console.log('Has delete-patient permission:', canDelete.value);
});

// Check permissions
const canCreate = computed(() => {
    if (!user.value?.permissions) return false;
    return user.value.permissions.some(p => p.name === 'create-patient');
});

const canEdit = computed(() => {
    if (!user.value?.permissions) return false;
    return user.value.permissions.some(p => p.name === 'edit-patient');
});

const canDelete = computed(() => {
    if (!user.value?.permissions) return false;
    // Check both the specific permission and if user has the admin role
    return user.value.permissions.some(p => p.name === 'delete-patient') ||
        user.value.roles.some(r => r.name === 'admin') ||
        user.value.roles.some(r => r.name === 'medic'); // Also allow medics
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Patients',
        href: '/patients',
    },
];

// Show the delete confirmation dialog
function confirmDeletePatient(patient: Patient): void {
    patientToDelete.value = patient;
    if (deleteConfirmDialog.value) {
        deleteConfirmDialog.value.open();
    }
}

// Delete the patient after confirmation
function deletePatient(): void {
    if (!patientToDelete.value) return;

    router.delete(`/patients/${patientToDelete.value.id}`, {});
}

function editPatient(id: number): void {
    router.get(`/patients/${id}/edit`);
}

// Navigate to body map for a patient
function navigateToBodyMap(id: number): void {
    router.get(`/body-map/${id}`);
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Patients" />

        <div class="container p-4">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Patients</h1>
                <Button v-if="canCreate" variant="default" as="a" href="/patients/create">Add Patient</Button>
            </div>

            <div class="rounded-md border">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr class="bg-muted/50">
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">ID</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Name</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Gender</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Age</th>
                            <th class="px-4 py-3.5 text-left text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border bg-background">
                        <tr v-for="patient in props.patients" :key="patient.id" class="hover:bg-muted/50">
                            <td class="px-4 py-3 text-sm">{{ patient.id }}</td>
                            <td class="px-4 py-3 text-sm">{{ patient.name }} {{ patient.last_name }}</td>
                            <td class="px-4 py-3 text-sm">{{ patient.gender }}</td>
                            <td class="px-4 py-3 text-sm">{{ patient.age }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <Button variant="outline" size="sm" @click="navigateToBodyMap(patient.id)">
                                        <ActivitySquare class="h-4 w-4 mr-1" />
                                        Body Map
                                    </Button>
                                    <Button v-if="canEdit" variant="outline" size="sm"
                                        @click="editPatient(patient.id)">Edit</Button>
                                    <Button v-if="canDelete" variant="destructive" size="sm"
                                        @click="confirmDeletePatient(patient)">Delete</Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!props.patients || props.patients.length === 0">
                            <td colspan="5" class="px-4 py-3 text-center text-sm text-muted-foreground">No patients
                                found</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Confirmation Dialog for Delete -->
            <ConfirmDialog ref="deleteConfirmDialog" title="Delete Patient"
                :description="`Are you sure you want to delete ${patientToDelete?.name} ${patientToDelete?.last_name}? This action cannot be undone.`"
                confirm-button-text="Delete" confirm-button-variant="destructive" :dangerous-action="true"
                @confirm="deletePatient" />
        </div>
    </AppLayout>
</template>
