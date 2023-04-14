<script setup>
import Navbar from "@/Layouts/Partials/Navbar.vue";
import Footer from "@/Layouts/Partials/Footer.vue";
import Sidebar from "@/Layouts/Partials/Sidebar.vue";
import { ref } from "vue";

const sidebarRef = ref(null);

const openSidebar = () => {
    sidebarRef.value.toggle();
};
</script>

<template>
    <Navbar @button-clicked="openSidebar" />

    <div class="relative rf-container main-height">
        <slot />
    </div>

    <Footer />

    <Sidebar ref="sidebarRef" />
</template>
