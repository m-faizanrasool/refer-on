<script setup>
import { ref } from "vue";

const open = ref(false);
const dimmer = ref(true);
const right = ref(false);

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
            <div
                class="absolute top-0 z-20 flex h-screen"
                :class="[
                    right ? 'right-0 flex-row' : 'left-0 flex-row-reverse',
                ]"
            >
                <!-- Sidebar Content -->
                <div
                    ref="content"
                    class="flex items-center justify-center overflow-hidden overflow-y-auto transition-all duration-700 bg-black w-60"
                    :class="[open ? 'max-w-xl' : 'max-w-0']"
                >
                    <div class="w-48 text-xl font-bold text-center">
                        Sidebar
                    </div>
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
