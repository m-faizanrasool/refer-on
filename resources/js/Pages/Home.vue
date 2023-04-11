<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import Carousel from "@/Components/Carousal.vue";

let logos = [
    { src: "images/logos/adidas.png", alt: "adidas" },
    { src: "images/logos/amazon.png", alt: "amazon" },
    { src: "images/logos/ebay.png", alt: "ebay" },
    { src: "images/logos/nike.png", alt: "nike" },
    { src: "images/logos/shopee.png", alt: "shopee" },
];

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Home" />

    <AppLayout>
        <div class="w-4/6 mx-auto">
            <div class="mt-10">
                <h1 class="font-bold text-center page-heading">REFERON</h1>

                <TextInput
                    type="text"
                    class="block w-full mt-1"
                    placeholder="Search for brands or stores with tasks"
                />
            </div>

            <Carousel />
        </div>
    </AppLayout>
</template>
