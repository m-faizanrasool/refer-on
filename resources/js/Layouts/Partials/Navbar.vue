<script setup>
import { Link } from "@inertiajs/vue3";

import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
</script>

<template>
    <nav class="flex items-center justify-between px-4 py-6 font-bold md:p-6">
        <div class="flex items-center gap-2.5 md:gap-8">
            <div class="flex items-center gap-2">
                <div class="border-2 shadow-xl avatar">
                    <img
                        :class="`fi-${
                            $page.props.country.data?.code.toLowerCase() ?? 'uk'
                        }`"
                    />
                </div>

                <div class="hidden text-lg md:block">
                    {{ $page.props.country.data?.name ?? "Unknown" }}
                </div>
            </div>

            <Link class="text-xl md:text-2xl text-spaced" href="/"
                >REFERON
            </Link>
        </div>

        <div class="flex items-center gap-2.5 md:gap-8">
            <div class="hidden md:block">How it works</div>

            <Link
                :href="route('login')"
                class="btn"
                v-if="!$page.props.auth.user"
            >
                Login/Register
            </Link>

            <div class="flex items-center gap-2" v-if="$page.props.auth.user">
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <button
                            class="flex items-center transition duration-150 ease-in-out gap-x-1"
                        >
                            <span class="text-lg">
                                Hello, {{ $page.props.auth.user.username }}
                            </span>

                            <svg
                                class="w-5 h-5 mt-0.5"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </template>

                    <template #content>
                        <DropdownLink :href="route('profile.show')">
                            My Account
                        </DropdownLink>

                        <DropdownLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            Log Out
                        </DropdownLink>
                    </template>
                </Dropdown>

                <div class="flex items-center">
                    <button
                        @click="$emit('button-clicked')"
                        class="rounded hover:bg-gray-100 focus:bg-gray-100"
                    >
                        <svg
                            class="w-6 h-6 transition-all duration-200 ease-in-out"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>
