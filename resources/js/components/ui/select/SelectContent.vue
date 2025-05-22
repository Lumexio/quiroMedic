<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { ListboxOptions } from '@headlessui/vue'
import { computed } from 'vue'

const props = defineProps({
    class: {
        type: String,
        default: '',
    }
})

// Properly computed class string
const contentClass = computed(() => {
    return cn(
        'absolute z-50 min-w-[8rem] overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-md mt-1',
        'data-[side=bottom]:translate-y-1 data-[side=left]:-translate-x-1 data-[side=right]:translate-x-1 data-[side=top]:-translate-y-1',
        props.class
    );
});
</script>

<template>
    <div class="relative">
        <ListboxOptions :class="contentClass">
            <slot></slot>
        </ListboxOptions>
    </div>
</template>
