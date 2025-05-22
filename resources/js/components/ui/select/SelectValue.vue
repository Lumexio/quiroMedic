<script setup lang="ts">
import { inject, computed } from 'vue'

const select = inject('select', {
    value: computed(() => null),
})
</script>

<template>
    <span class="block truncate">
        <span v-if="select.value" class="text-foreground">{{ select.value }}</span>
        <slot v-else></slot>
    </span>
</template>
