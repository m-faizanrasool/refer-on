<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <nav class="flex items-center justify-between p-6">
        <div class="flex items-center gap-10">
            <div class="text-lg font-bold">
                {{ $page.props.country.data?.name ?? "Unknown" }}
            </div>

            <Link class="text-2xl font-bold color-primary" href="/"
                >REFERON
            </Link>
        </div>

        <div class="flex items-center gap-6">
            <div>How it works</div>

            <Link
                :href="route('login')"
                class="btn"
                v-if="!$page.props.auth.user"
            >
                Login/Register
            </Link>

            <div v-if="$page.props.auth.user">My Account</div>

            <div v-if="$page.props.auth.user" class="text-[20px]">
                Hello,
                <span class="ml-1 font-bold">{{
                    $page.props.auth.user.name
                }}</span>
            </div>
        </div>
    </nav>
</template>
