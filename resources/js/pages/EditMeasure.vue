<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import GenericForm from '@/components/GenericForm.vue';
import { measuresService } from '@/services/CrudService';

const props = defineProps({
    measure: Object,
    patients: Array,
});

// Create form data from measure
const form = ref({
    name: props.measure?.name || '',
    body_zone: props.measure?.body_zone || '',
    value: props.measure?.value || '',
    unit: props.measure?.unit || '',
    patient_id: props.measure?.patient_id || '',
});

// Navigation breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Measures',
        href: '/measures',
    },
    {
        title: 'Edit Measure',
        href: `/measures/${props.measure.id}/edit`,
    },
];

// Submit the form to update measure
function submit() {
    measuresService.update(props.measure.id, form.value);
}

// Cancel and return to measures list
function cancel() {
    measuresService.navigateToList();
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Edit Measure" />

        <div class="container p-4">
            <GenericForm title="Edit Measure" description="Update measurement information" :isEdit="true"
                submitButtonText="Update Measure" @submit="submit" @cancel="cancel">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="name">Measurement Name</Label>
                        <Input id="name" v-model="form.name" required />
                    </div>

                    <div class="space-y-2">
                        <Label for="body_zone">Body Zone</Label>
                        <Input id="body_zone" v-model="form.body_zone" required />
                    </div>

                    <div class="space-y-2">
                        <Label for="value">Value</Label>
                        <Input id="value" type="number" step="0.01" v-model="form.value" required />
                    </div>

                    <div class="space-y-2">
                        <Label for="unit">Unit</Label>
                        <Input id="unit" v-model="form.unit" required />
                    </div>

                    <div class="space-y-2">
                        <Label for="patient_id">Patient</Label>
                        <Select v-model="form.patient_id">
                            <SelectTrigger>
                                <SelectValue placeholder="Select patient" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="patient in props.patients" :key="patient.id" :value="patient.id">
                                    {{ patient.name }} {{ patient.last_name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
            </GenericForm>
        </div>
    </AppLayout>
</template>
