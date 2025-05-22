<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { computed } from 'vue';
import { measuresService } from '@/services/CrudService';
import { ArrowLeft, Pencil } from 'lucide-vue-next';

const props = defineProps({
    measure: Object,
    patient: Object,
});

// Navigation breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Measures',
        href: '/measures',
    },
    {
        title: 'Measure Details',
        href: `/measures/${props.measure.id}`,
    },
];

// Function to get full image URL
function getImageUrl(path) {
    if (!path) return null;
    return `/storage/${path}`;
}

// Check if measure has an image
const hasImage = computed(() => {
    return !!props.measure.image_path;
});

// Format date to readable format
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Navigate back to measures list
function goBack() {
    measuresService.navigateToList();
}

// Navigate to edit page
function editMeasure() {
    measuresService.navigateToEdit(props.measure.id);
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head :title="`Measure: ${measure.name}`" />

        <div class="container p-4">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="icon" @click="goBack">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <h1 class="text-2xl font-bold">{{ measure.name }}</h1>
                </div>
                <Button variant="outline" @click="editMeasure">
                    <Pencil class="mr-2 h-4 w-4" />
                    Edit Measure
                </Button>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Measure Details -->
                <div class="md:col-span-2 rounded-lg border p-4 shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Measurement Details</h2>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Body Zone</p>
                            <p class="text-base">{{ measure.body_zone }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Value</p>
                            <p class="text-base">{{ measure.value }} {{ measure.unit }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Created</p>
                            <p class="text-base">{{ formatDate(measure.created_at) }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Last Updated</p>
                            <p class="text-base">{{ formatDate(measure.updated_at) }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-muted-foreground">Description</p>
                            <p class="text-base">{{ measure.description || 'No description provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Patient Information -->
                <div class="rounded-lg border p-4 shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Patient Information</h2>

                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Name</p>
                            <p class="text-base">{{ patient.name }} {{ patient.last_name }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Gender / Age</p>
                            <p class="text-base">{{ patient.gender }}, {{ patient.age }} years</p>
                        </div>

                        <div class="pt-2">
                            <Button variant="outline" as="a" :href="`/patients/${patient.id}`" class="w-full">
                                View Patient Details
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body Image Section -->
            <div v-if="hasImage" class="mt-6 rounded-lg border p-4 shadow-sm">
                <h2 class="text-xl font-semibold mb-4">Body Image</h2>

                <div class="flex justify-center">
                    <div class="max-w-xl rounded-md overflow-hidden border">
                        <img :src="getImageUrl(measure.image_path)" alt="Body area measurement"
                            class="w-full object-contain" />
                    </div>
                </div>
            </div>

            <div v-else class="mt-6 rounded-lg border p-4 bg-muted/20 shadow-sm">
                <p class="text-center text-muted-foreground">No image available for this measurement</p>
            </div>
        </div>
    </AppLayout>
</template>
