<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    lastname: '',
    email: '',
    password: '',
    password_confirmation: '',
    organization_name: '', // This will store the practice/organization name
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Create a Clinic Account" description="Enter your details to start your quiropractic practice">

        <Head title="Register Clinic" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <!-- Organization Information -->
                <div class="grid gap-2">
                    <Label for="organization_name">Clinic/Practice Name</Label>
                    <Input id="organization_name" type="text" required :tabindex="1" v-model="form.organization_name"
                        placeholder="Your quiropractic clinic name" />
                    <InputError :message="form.errors.organization_name" />
                    <p class="text-xs text-muted-foreground">
                        This creates your clinic's private workspace. All your team, patients, and measurements will be
                        stored securely.
                    </p>
                </div>

                <!-- Personal Information -->
                <div class="grid gap-2">
                    <Label for="name">First Name</Label>
                    <Input id="name" type="text" required :tabindex="2" autocomplete="name" v-model="form.name"
                        placeholder="First name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="lastname">Last Name</Label>
                    <Input id="lastname" type="text" required :tabindex="3" autocomplete="family-name"
                        v-model="form.lastname" placeholder="Last name" />
                    <InputError :message="form.errors.lastname" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input id="email" type="email" required :tabindex="4" autocomplete="email" v-model="form.email"
                        placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" required :tabindex="5" autocomplete="new-password"
                        v-model="form.password" placeholder="Password" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input id="password_confirmation" type="password" required :tabindex="6" autocomplete="new-password"
                        v-model="form.password_confirmation" placeholder="Confirm password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="7" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Create Clinic Account
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Already have a clinic account?
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="8">Log in</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
