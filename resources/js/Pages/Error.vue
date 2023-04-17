<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    availableTasks: Array,
    quereyParam: String,
});
</script>

<template>
    <Head title="Error" />

    <AppLayout></AppLayout>
</template>
