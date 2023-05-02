<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <footer
        class="flex flex-col-reverse items-center justify-between p-6 md:flex-row"
    >
        <div>
            <h6>© REFERON. All Rights Reserved</h6>
        </div>

        <div
            class="flex flex-col gap-3 mb-2 text-center sm:flex-row md:gap-[70px] md:mb-0"
        >
            <Link :href="route('aboutus')">About us</Link>
            <Link :href="route('termofuse')">Terms and conditions</Link>
            <Link :href="route('privacypolicy')">Privacy policy</Link>
        </div>
    </footer>
</template>
