<script setup>
defineProps(["value"]);
</script>

<template>
    <label class="block ml-2 text-base font-medium color-primary">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
