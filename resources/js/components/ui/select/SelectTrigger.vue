<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { ListboxButton } from '@headlessui/vue'
import { ChevronDown } from 'lucide-vue-next'
import { inject, computed } from 'vue'

const props = defineProps({
    placeholder: {
        type: String,
        default: 'Select option',
    },
    class: {
        type: String,
        default: '',
    }
})

const select = inject('select', {
    value: computed(() => null),
    disabled: computed(() => false),
})
</script>

<template>
    <ListboxButton
        :class="[
            'flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm',
            'ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
            'disabled:cursor-not-allowed disabled:opacity-50',
            props.class
        ]"
        :disabled="select.disabled">
        <slot>
            <span class="block truncate">
                <slot name="value">
                    <span v-if="select.value" class="text-foreground">{{ select.value }}</span>
                    <span v-else class="text-muted-foreground">{{ placeholder }}</span>
                </slot>
            </span>
        </slot>
        <ChevronDown class="h-4 w-4 opacity-50" />
    </ListboxButton>
</template>
