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
import { ref, onMounted } from 'vue';

interface Patient {
    id: number;
    name: string;
    last_name: string;
}

const props = defineProps<{
    patients?: Patient[];
    selectedPatientId?: string | number;
    bodyZone?: string;
}>();

// Preview for image upload
const imagePreview = ref<string | null>(null);
const imageFile = ref<File | null>(null);

// Form object interface with index signature
interface FormData {
    name: string;
    body_zone: string;
    value: string;
    unit: string;
    patient_id: string | number;
    description: string;
    image: File | null;
    [key: string]: string | number | File | null;
}

// Initialize form with empty values or pre-selected values
const form = ref<FormData>({
    name: '',
    body_zone: props.bodyZone || '',
    value: '',
    unit: '',
    patient_id: props.selectedPatientId || '',
    description: '',
    image: null,
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

// Set form title based on body zone if provided
const pageTitle = props.bodyZone
    ? `New ${props.bodyZone} Measurement`
    : 'Create New Measure';

// Handle image selection
function onImageSelected(event: Event): void {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        form.value.image = file;
        imageFile.value = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

// Suggest a measure name based on body zone
onMounted(() => {
    if (props.bodyZone) {
        form.value.name = `${props.bodyZone} measurement`;
    }
});

// Submit the form to create measure
function submit(): void {
    // Use FormData to handle file uploads
    const formData = new FormData();
    Object.keys(form.value).forEach(key => {
        const value = form.value[key];
        if (value !== null) {
            formData.append(key, value as string | Blob);
        }
    });

    router.post('/measures', formData, {
        forceFormData: true
    });
}

// Cancel and return to measures list
function cancel(): void {
    router.get('/measures');
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Create Measure" />

        <div class="container p-4">
            <Card>
                <CardHeader>
                    <CardTitle>{{ pageTitle }}</CardTitle>
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
                                        <SelectItem v-for="patient in props.patients" :key="patient?.id"
                                            :value="patient?.id">
                                            {{ patient?.name }} {{ patient?.last_name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <Label for="description">Description</Label>
                                <Textarea id="description" v-model="form.description" :rows="3" />
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
