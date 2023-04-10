<script setup>
import Navbar from "@/Layouts/Navbar.vue";
import Footer from "@/Layouts/Footer.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Navbar />

    <div class="relative rf-container main-height">
        <slot />
    </div>

    <Footer />
</template>
