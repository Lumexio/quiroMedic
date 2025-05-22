<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';

const props = defineProps({
    patient: Object,
});

// Create form data from patient or initialize with defaults
const form = ref({
    name: props.patient?.name || '',
    last_name: props.patient?.last_name || '',
    age: props.patient?.age || '',
    gender: props.patient?.gender || 'male',
    weight: props.patient?.weight || '',
    education: props.patient?.education || '',
    sport: props.patient?.sport || '',
    rest_hours: props.patient?.rest_hours || '',
});

// Navigation breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Patients',
        href: '/patients',
    },
    {
        title: props.patient ? 'Edit Patient' : 'New Patient',
        href: props.patient ? `/patients/${props.patient.id}/edit` : '/patients/create',
    },
];

// Submit the form to update patient
function submit() {
    if (props.patient) {
        router.put(`/patients/${props.patient.id}`, form.value);
    } else {
        router.post('/patients', form.value);
    }
}

// Cancel and return to patients list
function cancel() {
    router.get('/patients');
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head :title="patient ? 'Edit Patient' : 'Create Patient'" />

        <div class="container p-4">
            <Card>
                <CardHeader>
                    <CardTitle>{{ patient ? 'Edit Patient' : 'Create New Patient' }}</CardTitle>
                    <CardDescription>
                        {{ patient ? 'Update patient information' : 'Enter details for the new patient' }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="name">Name</Label>
                                <Input id="name" v-model="form.name" required />
                            </div>

                            <div class="space-y-2">
                                <Label for="last_name">Last Name</Label>
                                <Input id="last_name" v-model="form.last_name" required />
                            </div>

                            <div class="space-y-2">
                                <Label for="age">Age</Label>
                                <Input id="age" type="number" v-model="form.age" required />
                            </div>

                            <div class="space-y-2">
                                <Label for="gender">Gender</Label>
                                <Select v-model="form.gender">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select gender" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="male">Male</SelectItem>
                                        <SelectItem value="female">Female</SelectItem>
                                        <SelectItem value="other">Other</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="space-y-2">
                                <Label for="weight">Weight (kg)</Label>
                                <Input id="weight" type="number" step="0.01" v-model="form.weight" required />
                            </div>

                            <div class="space-y-2">
                                <Label for="education">Education</Label>
                                <Input id="education" v-model="form.education" required />
                            </div>

                            <div class="space-y-2">
                                <Label for="sport">Sport/Activity</Label>
                                <Input id="sport" v-model="form.sport" required />
                            </div>

                            <div class="space-y-2">
                                <Label for="rest_hours">Rest Hours (per day)</Label>
                                <Input id="rest_hours" type="number" v-model="form.rest_hours" required />
                            </div>
                        </div>
                    </form>
                </CardContent>
                <CardFooter class="flex justify-between">
                    <Button variant="outline" @click="cancel">Cancel</Button>
                    <Button @click="submit">{{ patient ? 'Update Patient' : 'Create Patient' }}</Button>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>
