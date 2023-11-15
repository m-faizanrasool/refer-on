<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const open = ref(false);
const dimmer = ref(true);

const toggle = () => {
    open.value = !open.value;
};

defineExpose({
    toggle,
});
</script>

<template>
    <div id="app">
        <!--Sidebar with Dimmer -->
        <div class="flex" :class="{ 'z-40 fixed inset-0': open }">
            <!-- Sidebar -->
            <div class="absolute top-0 right-0 z-20 flex flex-row h-screen">
                <!-- Sidebar Content -->
                <div
                    ref="content"
                    class="overflow-hidden overflow-y-auto transition-all duration-700 bg-black w-60"
                    :class="[open ? 'max-w-xl' : 'max-w-0']"
                >
                    <div class="flex justify-end pt-6 pr-6">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            class="w-6 h-6 text-white cursor-pointer"
                            @click="toggle"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18 18 6M6 6l12 12"
                            />
                        </svg>
                    </div>

                    <nav class="mx-4 text-white">
                        <ul>
                            <li class="sidebar-item">
                                <Link :href="route('task.index')"
                                    >Your Tasks</Link
                                >
                            </li>

                            <li class="sidebar-item md:hidden">
                                <Link :href="route('howitworks')"
                                    >How it works</Link
                                >
                            </li>

                            <div v-if="$page.props.auth.user.is_admin">
                                <li class="sidebar-item">
                                    <Link :href="route('user.index')"
                                        >Users (Admin)</Link
                                    >
                                </li>

                                <li class="sidebar-item">
                                    <Link
                                        :href="route('blacklisted-tasks.index')"
                                    >
                                        Blacklisted Task (Admin)
                                    </Link>
                                </li>
                            </div>
                        </ul>
                    </nav>
                </div>
            </div>

            <transition name="fade">
                <!-- Dimmer -->
                <div
                    v-if="dimmer && open"
                    @click="toggle()"
                    class="z-10 flex-1 bg-black opacity-50 active:outline-none"
                />
            </transition>
        </div>
    </div>
</template>
