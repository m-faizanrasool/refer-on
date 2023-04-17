<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

import { Head } from "@inertiajs/inertia-vue3";

defineProps({
    availableTasks: Array,
    quereyParam: String,
});
</script>

<template>
    <Head title="Error" />

    <AppLayout> </AppLayout>
</template>
