<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Home" />

    <GuestLayout>
        <div class="w-4/6 mx-auto">
            <div class="mt-10">
                <h1 class="font-bold text-center page-heading">REFERON</h1>

                <TextInput
                    type="text"
                    class="block w-full mt-1"
                    placeholder="Search for brands or stores with tasks"
                />
            </div>
        </div>
    </GuestLayout>
</template>
