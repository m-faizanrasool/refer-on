<script setup>
import Navbar from "@/Layouts/Partials/Navbar.vue";
import Footer from "@/Layouts/Partials/Footer.vue";
import Sidebar from "@/Layouts/Partials/Sidebar.vue";
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

import Toastify from "toastify-js";

const sidebarRef = ref(null);

const openSidebar = () => {
    sidebarRef.value.toggle();
};

const session = usePage().props.flash;

onMounted(() => {
    if (session.message || session.error) {
        const message = session.error ? session.error : session.message;
        const className = session.error ? "toastify-error" : "toastify-success";

        Toastify({
            text: message,
            className: className,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
        }).showToast();
    }
});
</script>

<template>
    <Navbar @button-clicked="openSidebar" />

    <div class="relative rf-container main-height">
        <slot />
    </div>

    <Footer />

    <Sidebar ref="sidebarRef" v-if="$page.props.auth.user" />
</template>
