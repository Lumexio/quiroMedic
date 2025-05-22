<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { ref } from 'vue';

const props = defineProps<{
    title: string;
    description: string;
    confirmButtonText?: string;
    confirmButtonVariant?: 'default' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link';
    cancelButtonText?: string;
    dangerousAction?: boolean;
}>();

const emit = defineEmits<{
    confirm: [];
    cancel: [];
}>();

const isOpen = ref(false);

// Open the dialog
function open() {
    isOpen.value = true;
}

// Confirm the action
function confirm() {
    emit('confirm');
    isOpen.value = false;
}

// Cancel the action
function cancel() {
    emit('cancel');
    isOpen.value = false;
}

// Expose the open method to the parent component
defineExpose({ open });
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ props.title }}</DialogTitle>
                <DialogDescription>{{ props.description }}</DialogDescription>
            </DialogHeader>
            <DialogFooter class="gap-2 sm:gap-0">
                <DialogClose as-child>
                    <Button variant="secondary" @click="cancel">
                        {{ props.cancelButtonText || 'Cancel' }}
                    </Button>
                </DialogClose>
                <Button :variant="props.confirmButtonVariant || (props.dangerousAction ? 'destructive' : 'default')"
                    @click="confirm">
                    {{ props.confirmButtonText || 'Confirm' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
