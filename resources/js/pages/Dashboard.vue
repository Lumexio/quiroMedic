<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';
import { Users, Activity, UserPlus, FileText } from 'lucide-vue-next';

// Get current user and organization
const page = usePage();
const user = computed(() => page.props.auth?.user);
const organization = computed(() => page.props.organization);
const stats = computed(() => page.props.stats || {});

// Check user roles
const isAdmin = computed(() => {
    if (!user.value?.roles) return false;
    return user.value.roles.some(role => role.name === 'admin');
});
</script>

<template>
    <AppLayout>

        <Head title="Dashboard" />

        <div class="container p-4">
            <div class="mb-6">
                <h1 class="text-2xl font-bold">Dashboard</h1>
                <p class="text-muted-foreground">Welcome to {{ organization?.name || 'your clinic' }}</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Patients</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.patientCount || 0 }}</div>
                        <CardDescription>Active patients in your clinic</CardDescription>
                    </CardContent>
                    <CardFooter>
                        <Button variant="link" as="a" href="/patients">View All</Button>
                    </CardFooter>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Measures</CardTitle>
                        <Activity class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.measureCount || 0 }}</div>
                        <CardDescription>Measurements recorded</CardDescription>
                    </CardContent>
                </Card>

                <Card v-if="isAdmin">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Team Members</CardTitle>
                        <UserPlus class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.userCount || 0 }}</div>
                        <CardDescription>Admins and practitioners</CardDescription>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Recent Activity</CardTitle>
                        <FileText class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.recentActivityCount || 0 }}</div>
                        <CardDescription>Actions in the last 24h</CardDescription>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6">
                <h2 class="mb-4 text-lg font-semibold">Quick Actions</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <Button class="h-auto flex-col items-center justify-center gap-2 p-4" as="a"
                        href="/patients/create">
                        <Users class="h-6 w-6" />
                        <span>Add New Patient</span>
                    </Button>

                    <Button class="h-auto flex-col items-center justify-center gap-2 p-4" as="a"
                        href="/measures/create">
                        <Activity class="h-6 w-6" />
                        <span>Record Measurement</span>
                    </Button>

                    <Button v-if="isAdmin" class="h-auto flex-col items-center justify-center gap-2 p-4" as="a"
                        href="/users/create">
                        <UserPlus class="h-6 w-6" />
                        <span>Add Team Member</span>
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
