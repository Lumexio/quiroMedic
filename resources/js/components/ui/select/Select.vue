<script setup lang="ts">

import { Listbox, ListboxLabel, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue'
import { ref, provide, computed, useSlots } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, Number, Object],
        default: undefined,
    },
    defaultValue: {
        type: [String, Number, Object],
        default: undefined,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    name: {
        type: String,
        default: undefined,
    }
})

const emit = defineEmits(['update:modelValue'])
const slots = useSlots()

const value = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val)
})

provide('select', {
    value,
    disabled: computed(() => props.disabled),
    name: computed(() => props.name),
})
</script>

<template>
    <Listbox v-model="value" :disabled="disabled" as="div" class="relative">
        <slot />
    </Listbox>
</template>
