<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { ref, computed } from 'vue';
import { patientsService } from '@/services/CrudService';

interface Measure {
    id: number;
    name: string;
    body_zone: string;
    value: number;
    unit: string;
}

interface Patient {
    id: number;
    name: string;
    last_name: string;
}

interface BodyPart {
    id: string;
    name: string;
    description: string;
}

const props = defineProps<{
    patient?: Patient;
    measures?: Measure[];
    patientId?: string | number;
    patients?: Patient[];
}>();

// Reference to the selected body part
const selectedBodyPart = ref('');
const hoverBodyPart = ref('');

// Navigate to measure creation with pre-selected body part and patient
function createMeasureForBodyPart(bodyPart: string): void {
    selectedBodyPart.value = bodyPart;
    // Redirect to create measure with patient and body part pre-selected
    window.location.href = `/measures/create?patient=${props.patientId || props.patient?.id}&body_zone=${bodyPart}`;
}

// Get patient name for title
const patientName = computed(() => {
    if (props.patient) {
        return `${props.patient.name} ${props.patient.last_name}`;
    }
    return 'Patient';
});

// Navigation breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Patients',
        href: '/patients',
    },
    {
        title: props.patient ? patientName.value : 'Body Measurement Map',
        href: props.patient ? `/patients/${props.patient.id}` : '#',
    },
    {
        title: 'Body Map',
        href: '#',
    },
];

// List of body parts with their positions on the SVG
const bodyParts: BodyPart[] = [
    { id: 'head', name: 'Head', description: 'Skull, facial features' },
    { id: 'neck', name: 'Neck', description: 'Cervical area' },
    { id: 'shoulders', name: 'Shoulders', description: 'Shoulder joints and area' },
    { id: 'chest', name: 'Chest', description: 'Pectoral area, sternum' },
    { id: 'abdomen', name: 'Abdomen', description: 'Abdominal muscles and area' },
    { id: 'back-upper', name: 'Upper Back', description: 'Upper thoracic area' },
    { id: 'back-lower', name: 'Lower Back', description: 'Lumbar area' },
    { id: 'arms', name: 'Arms', description: 'Biceps, triceps, forearms' },
    { id: 'hands', name: 'Hands', description: 'Wrists, fingers, palm' },
    { id: 'hips', name: 'Hips', description: 'Hip joints and iliac area' },
    { id: 'legs-upper', name: 'Upper Legs', description: 'Quadriceps, hamstrings' },
    { id: 'legs-lower', name: 'Lower Legs', description: 'Calves, shins' },
    { id: 'feet', name: 'Feet', description: 'Ankles, heel, toes' },
];

// View a patient's details
function viewPatientDetails(): void {
    if (props.patientId || props.patient?.id) {
        patientsService.get(Number(props.patientId || props.patient?.id));
    }
}

// Get measures for specific body part
function getMeasuresForBodyPart(bodyPart: string): Measure[] {
    if (!props.measures) return [];
    return props.measures.filter(m => m.body_zone === bodyPart);
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head :title="`Body Measurement Map - ${patientName}`" />

        <div class="container p-4">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Body Measurement Map</h1>
                    <p class="text-muted-foreground">
                        {{ props.patient ? `${patientName}` : 'Select a body part to add measurements' }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button v-if="props.patient || props.patientId" variant="outline" @click="viewPatientDetails">
                        View Patient Details
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Body Map SVG -->
                <div class="md:col-span-2 rounded-lg border p-4 shadow-sm bg-white">
                    <div class="flex justify-center">
                        <div class="relative w-full max-w-md">
                            <!-- SVG Body Map - Front View -->
                            <svg viewBox="0 0 200 500" class="w-full">
                                <!-- Body silhouette - simplified human body outline -->
                                <path
                                    d="M100,20 C120,20 130,30 130,40 C130,50 120,60 100,70 C80,60 70,50 70,40 C70,30 80,20 100,20"
                                    class="body-part" :class="{ active: hoverBodyPart === 'head' }"
                                    @click="createMeasureForBodyPart('head')" @mouseenter="hoverBodyPart = 'head'"
                                    @mouseleave="hoverBodyPart = ''" />

                                <path d="M90,70 L110,70 L110,90 L90,90 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'neck' }"
                                    @click="createMeasureForBodyPart('neck')" @mouseenter="hoverBodyPart = 'neck'"
                                    @mouseleave="hoverBodyPart = ''" />

                                <path d="M60,90 L140,90 L140,110 L60,110 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'shoulders' }"
                                    @click="createMeasureForBodyPart('shoulders')"
                                    @mouseenter="hoverBodyPart = 'shoulders'" @mouseleave="hoverBodyPart = ''" />

                                <path d="M70,110 L130,110 L130,160 L70,160 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'chest' }"
                                    @click="createMeasureForBodyPart('chest')" @mouseenter="hoverBodyPart = 'chest'"
                                    @mouseleave="hoverBodyPart = ''" />

                                <path d="M70,160 L130,160 L130,210 L70,210 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'abdomen' }"
                                    @click="createMeasureForBodyPart('abdomen')" @mouseenter="hoverBodyPart = 'abdomen'"
                                    @mouseleave="hoverBodyPart = ''" />

                                <path d="M50,90 L60,90 L60,170 L40,170 L40,150 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'arms' }"
                                    @click="createMeasureForBodyPart('left arm')" @mouseenter="hoverBodyPart = 'arms'"
                                    @mouseleave="hoverBodyPart = ''" />

                                <path d="M150,90 L140,90 L140,170 L160,170 L160,150 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'arms' }"
                                    @click="createMeasureForBodyPart('right arm')" @mouseenter="hoverBodyPart = 'arms'"
                                    @mouseleave="hoverBodyPart = ''" />

                                <path d="M30,170 L50,170 L50,230 L30,230 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'hands' }"
                                    @click="createMeasureForBodyPart('left hand')" @mouseenter="hoverBodyPart = 'hands'"
                                    @mouseleave="hoverBodyPart = ''" />

                                <path d="M170,170 L150,170 L150,230 L170,230 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'hands' }"
                                    @click="createMeasureForBodyPart('right hand')"
                                    @mouseenter="hoverBodyPart = 'hands'" @mouseleave="hoverBodyPart = ''" />

                                <path d="M70,210 L130,210 L140,240 L60,240 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'hips' }"
                                    @click="createMeasureForBodyPart('hips')" @mouseenter="hoverBodyPart = 'hips'"
                                    @mouseleave="hoverBodyPart = ''" />

                                <path d="M60,240 L90,240 L90,330 L60,330 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'legs-upper' }"
                                    @click="createMeasureForBodyPart('left thigh')"
                                    @mouseenter="hoverBodyPart = 'legs-upper'" @mouseleave="hoverBodyPart = ''" />

                                <path d="M140,240 L110,240 L110,330 L140,330 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'legs-upper' }"
                                    @click="createMeasureForBodyPart('right thigh')"
                                    @mouseenter="hoverBodyPart = 'legs-upper'" @mouseleave="hoverBodyPart = ''" />

                                <path d="M60,330 L90,330 L90,430 L60,430 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'legs-lower' }"
                                    @click="createMeasureForBodyPart('left calf')"
                                    @mouseenter="hoverBodyPart = 'legs-lower'" @mouseleave="hoverBodyPart = ''" />

                                <path d="M140,330 L110,330 L110,430 L140,430 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'legs-lower' }"
                                    @click="createMeasureForBodyPart('right calf')"
                                    @mouseenter="hoverBodyPart = 'legs-lower'" @mouseleave="hoverBodyPart = ''" />

                                <path d="M60,430 L90,430 L90,480 L60,480 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'feet' }"
                                    @click="createMeasureForBodyPart('left foot')" @mouseenter="hoverBodyPart = 'feet'"
                                    @mouseleave="hoverBodyPart = ''" />

                                <path d="M140,430 L110,430 L110,480 L140,480 Z" class="body-part"
                                    :class="{ active: hoverBodyPart === 'feet' }"
                                    @click="createMeasureForBodyPart('right foot')" @mouseenter="hoverBodyPart = 'feet'"
                                    @mouseleave="hoverBodyPart = ''" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-center text-sm text-muted-foreground">
                        Click on a body part to add a measurement
                    </p>
                </div>

                <!-- Body Parts Legend -->
                <div class="rounded-lg border p-4 shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Body Regions</h2>
                    <div class="space-y-2">
                        <div v-for="part in bodyParts" :key="part.id"
                            class="rounded-md border p-2 hover:bg-muted/50 cursor-pointer"
                            :class="{ 'bg-primary/10 border-primary': hoverBodyPart === part.id }"
                            @click="createMeasureForBodyPart(part.name.toLowerCase())"
                            @mouseenter="hoverBodyPart = part.id" @mouseleave="hoverBodyPart = ''">
                            <p class="font-medium">{{ part.name }}</p>
                            <p class="text-xs text-muted-foreground">{{ part.description }}</p>

                            <div v-if="props.measures && getMeasuresForBodyPart(part.name.toLowerCase()).length > 0"
                                class="mt-1 text-xs text-primary">
                                {{ getMeasuresForBodyPart(part.name.toLowerCase()).length }} measurements recorded
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.body-part {
    fill: #f1f5f9;
    /* Light color */
    stroke: #64748b;
    /* Border color */
    stroke-width: 1;
    transition: all 0.2s ease;
    cursor: pointer;
}

.body-part:hover,
.body-part.active {
    fill: #dbeafe;
    /* Light blue on hover */
    stroke: #2563eb;
    /* Primary color border */
    stroke-width: 2;
}
</style>
