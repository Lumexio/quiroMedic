<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { computed, onMounted, ref } from 'vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';

const props = defineProps({
    patients: Array,
});


const deleteConfirmDialog = ref(null);
const patientToDelete = ref(null);

const page = usePage();
const user = page.props.auth?.user;


onMounted(() => {
    console.log('User:', user);
    console.log('User permissions:', user?.permissions);
    console.log('User roles:', user?.roles);
    console.log('Has delete-patient permission:', canDelete.value);
});

// Check
const canCreate = computed(() => {
    if (!user?.permissions) return false;
    return user.permissions.some(p => p.name === 'create-patient');
});

const canEdit = computed(() => {
    if (!user?.permissions) return false;
    return user.permissions.some(p => p.name === 'edit-patient');
});

const canDelete = computed(() => {
    if (!user?.permissions) return false;
    // Check both the specific permission and if user has the admin role
    return user.permissions.some(p => p.name === 'delete-patient') ||
        user.roles.some(r => r.name === 'admin') ||
        user.roles.some(r => r.name === 'medic'); // Also allow medics
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Patients',
        href: '/patients',
    },
];

// Show the delete confirmation dialog
function confirmDeletePatient(patient) {
    patientToDelete.value = patient;
    deleteConfirmDialog.value.open();
}

// Delete the patient after confirmation
function deletePatient() {
    if (!patientToDelete.value) return;

    router.delete(`/patients/${patientToDelete.value.id}`, {}, {
        onSuccess: () => {
            console.log('Delete successful');
            // The page will automatically refresh due to Inertia
        },
        onError: (errors) => {
            console.error('Delete failed:', errors);
            alert('Failed to delete patient. Please try again.');
        }
    });
}

function editPatient(id) {
    router.get(`/patients/${id}/edit`);
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
