<script setup lang="ts">
import { ListboxOption } from '@headlessui/vue'
import { computed } from 'vue'
import { Check } from 'lucide-vue-next'

const props = defineProps({
    value: {
        type: [String, Number, Object],
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    class: {
        type: String,
        default: '',
    }
})
</script>

<template>
    <ListboxOption v-slot="{ active, selected }" :value="value" :disabled="disabled" as="template">
        <li 
            :class="[
                'relative flex cursor-default select-none items-center rounded-sm py-1.5 pl-8 pr-2 text-sm outline-none',
                'focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50',
                active ? 'bg-accent text-accent-foreground' : '',
                props.class
            ]"
            :data-disabled="disabled ? '' : undefined"
        >
            <span class="absolute left-2 flex h-3.5 w-3.5 items-center justify-center">
                <Check v-if="selected" class="h-4 w-4" />
            </span>
            <slot>{{ value }}</slot>
        </li>
    </ListboxOption>
</template>
