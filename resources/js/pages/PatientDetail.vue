<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { patientsService } from '@/services/CrudService';
import { ArrowLeft, Pencil, Plus } from 'lucide-vue-next';

const props = defineProps({
    patient: Object,
    measures: Array,
});

// Navigation breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Patients',
        href: '/patients',
    },
    {
        title: 'Patient Details',
        href: `/patients/${props.patient.id}`,
    },
];

// Navigate back to patients list
function goBack() {
    patientsService.navigateToList();
}

// Navigate to edit patient page
function editPatient() {
    patientsService.navigateToEdit(props.patient.id);
}

// Navigate to create measure page with patient preselected
function addMeasure() {
    window.location.href = `/measures/create?patient=${props.patient.id}`;
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head :title="`Patient: ${patient.name} ${patient.last_name}`" />

        <div class="container p-4">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="icon" @click="goBack">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <h1 class="text-2xl font-bold">{{ patient.name }} {{ patient.last_name }}</h1>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" @click="editPatient">
                        <Pencil class="mr-2 h-4 w-4" />
                        Edit Patient
                    </Button>
                    <Button variant="default" @click="addMeasure">
                        <Plus class="mr-2 h-4 w-4" />
                        Add Measure
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Patient Details -->
                <div class="rounded-lg border p-4 shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Personal Information</h2>

                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Name</p>
                            <p class="text-base">{{ patient.name }} {{ patient.last_name }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Gender</p>
                            <p class="text-base">{{ patient.gender }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Age</p>
                            <p class="text-base">{{ patient.age }} years</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Weight</p>
                            <p class="text-base">{{ patient.weight }} kg</p>
                        </div>
                    </div>
                </div>

                <!-- Lifestyle Information -->
                <div class="rounded-lg border p-4 shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Lifestyle</h2>

                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Education</p>
                            <p class="text-base">{{ patient.education }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Sport/Activity</p>
                            <p class="text-base">{{ patient.sport }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Rest Hours</p>
                            <p class="text-base">{{ patient.rest_hours }} hours/day</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Measures -->
                <div class="rounded-lg border p-4 shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Recent Measurements</h2>

                    <div v-if="measures && measures.length > 0" class="space-y-2">
                        <div v-for="measure in measures.slice(0, 5)" :key="measure.id"
                            class="rounded border p-2 hover:bg-muted/50">
                            <p class="font-medium">{{ measure.name }}</p>
                            <p class="text-sm text-muted-foreground">
                                {{ measure.body_zone }}: {{ measure.value }} {{ measure.unit }}
                            </p>
                            <Button variant="link" size="sm" as="a" :href="`/measures/${measure.id}`" class="px-0">
                                View details
                            </Button>
                        </div>

                        <Button v-if="measures.length > 5" variant="outline" as="a" href="/measures"
                            class="w-full mt-2">
                            View all measurements
                        </Button>
                    </div>

                    <div v-else class="flex flex-col items-center justify-center space-y-2 py-6">
                        <p class="text-center text-muted-foreground">No measurements recorded yet</p>
                        <Button @click="addMeasure">Add First Measurement</Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
