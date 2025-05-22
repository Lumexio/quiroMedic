<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    description: {
        type: String,
        default: ''
    },
    isEdit: {
        type: Boolean,
        default: false
    },
    submitButtonText: {
        type: String,
        default: 'Save'
    },
    cancelButtonText: {
        type: String,
        default: 'Cancel'
    }
});

const emit = defineEmits(['submit', 'cancel']);

function handleSubmit() {
    emit('submit');
}

function handleCancel() {
    emit('cancel');
}
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>{{ props.title }}</CardTitle>
            <CardDescription>{{ props.description }}</CardDescription>
        </CardHeader>
        <CardContent>
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <slot></slot>
            </form>
        </CardContent>
        <CardFooter class="flex justify-between">
            <Button variant="outline" @click="handleCancel">{{ props.cancelButtonText }}</Button>
            <Button @click="handleSubmit">{{ props.submitButtonText }}</Button>
        </CardFooter>
    </Card>
</template>
