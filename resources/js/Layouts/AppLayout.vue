<script setup>
import Navbar from "@/Layouts/Partials/Navbar.vue";
import Footer from "@/Layouts/Partials/Footer.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Navbar />

    <div class="relative rf-container main-height">
        <slot />
    </div>

    <Footer />
</template>
