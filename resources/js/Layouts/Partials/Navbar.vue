<script setup>
import { Link } from "@inertiajs/vue3";

import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
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

            <div v-if="$page.props.auth.user">
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button
                                type="button"
                                class="inline-flex items-center px-3 py-2 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none text-[20px] font-bold"
                            >
                                {{ $page.props.auth.user.name }}

                                <svg
                                    class="ml-2 -mr-0.5 h-4 w-4"
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
                        </span>
                    </template>

                    <template #content>
                        <DropdownLink :href="route('profile.edit')">
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
            </div>
        </div>
    </nav>
</template>
