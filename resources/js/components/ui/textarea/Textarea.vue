<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'

const props = defineProps<{
    defaultValue?: string | number
    modelValue?: string | number
    class?: HTMLAttributes['class']
    rows?: number
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
})
</script>

<template>
    <textarea v-model="modelValue" :rows="rows || 3" data-slot="textarea" :class="cn(
        'peer w-full resize-none rounded-md border-2 border-slate-300 bg-transparent p-2 text-sm text-slate-900 placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
        'data-[state=disabled]:border-slate-200 data-[state=disabled]:bg-slate-50 data-[state=disabled]:text-slate-500 data-[state=disabled]:placeholder:text-slate-400',
        'data-[state=invalid]:border-red-500 data-[state=invalid]:ring-red-500 data-[state=invalid]:focus-visible:ring-red-500',
        props.class,
    )"></textarea>
</template>
