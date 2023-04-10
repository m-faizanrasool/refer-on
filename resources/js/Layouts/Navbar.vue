<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <nav class="flex items-center justify-between p-6">
        <div class="flex items-center gap-10">
            <div class="text-lg font-bold">
                {{ $page.props.country.data?.countryName ?? "Unknown" }}
            </div>

            <h2 class="text-2xl font-extrabold color-primary">REFERON</h2>
        </div>

        <div class="flex items-center gap-6">
            <div>How it works</div>

            <Link :href="route('login')" class="btn">Login/Register </Link>
        </div>
    </nav>
</template>
