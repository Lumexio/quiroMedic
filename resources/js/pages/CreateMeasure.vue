<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';

const props = defineProps({
    patients: Array,
    selectedPatientId: [String, Number], // New prop for pre-selected patient
});

// Preview for image upload
const imagePreview = ref<string | null>(null);
const imageFile = ref<File | null>(null);

// Initialize form with empty values or pre-selected patient
const form = ref({
    name: '',
    body_zone: '',
    value: '',
    unit: '',
    patient_id: props.selectedPatientId || '',
    description: '',
    image: null as File | null,
});

// Navigation breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Measures',
        href: '/measures',
    },
    {
        title: 'New Measure',
        href: '/measures/create',
    },
];

// Handle image selection
function onImageSelected(event) {
    const file = event.target.files[0];
    if (file) {
        form.value.image = file;
        imageFile.value = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

// Submit the form to create measure
function submit() {
    // Use FormData to handle file uploads
    const formData = new FormData();
    Object.keys(form.value).forEach(key => {
        if (form.value[key] !== null) {
            formData.append(key, form.value[key]);
        }
    });

    router.post('/measures', formData, {
        forceFormData: true
    });
}

// Cancel and return to measures list
function cancel() {
    router.get('/measures');
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Create Measure" />

        <div class="container p-4">
            <Card>
                <CardHeader>
                    <CardTitle>Create New Measure</CardTitle>
                    <CardDescription>
                        Enter details for the new body measurement
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="name">Measure Name</Label>
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
                                <Select v-model="form.patient_id" required>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select patient" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="patient in props.patients" :key="patient.id"
                                            :value="patient.id">
                                            {{ patient.name }} {{ patient.last_name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <Label for="description">Description</Label>
                                <Textarea id="description" v-model="form.description" rows="3" />
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <Label for="image">Body Image</Label>
                                <div class="flex items-center space-x-4">
                                    <Input id="image" type="file" accept="image/*" @change="onImageSelected"
                                        class="flex-1" />
                                    <div v-if="imagePreview" class="h-24 w-24 rounded-md overflow-hidden border">
                                        <img :src="imagePreview" alt="Image preview"
                                            class="h-full w-full object-cover" />
                                    </div>
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    Upload an image of the body area being measured. Max size: 2MB.
                                </p>
                            </div>
                        </div>
                    </form>
                </CardContent>
                <CardFooter class="flex justify-between">
                    <Button variant="outline" @click="cancel">Cancel</Button>
                    <Button @click="submit">Create Measure</Button>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>
