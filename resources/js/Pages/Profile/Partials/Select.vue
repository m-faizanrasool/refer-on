<script setup>
import { ref } from "vue";

const emit = defineEmits(["update:value"]);

let props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    selectedOption: {
        type: Array,
        required: true,
    },
});

const showOptions = ref(false);

const toggleOptions = () => {
    showOptions.value = !showOptions.value;
};

const selectOption = (option) => {
    if (confirm("Are you sure?")) {
        showOptions.value = false;
        emit("update:value", option.value);
    }
};
</script>

<template>
    <div class="cursor-pointer">
        <div
            class="flex items-center justify-between mb-1"
            @click="toggleOptions"
        >
            <span :class="selectedOption[0].class">{{
                selectedOption[0].label
            }}</span>

            <span class="w-5 h-5">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="#000"
                    class="transition-all duration-300"
                    v-bind:style="
                        showOptions
                            ? 'transform: rotate(-180deg)'
                            : 'transform: rotate(0deg)'
                    "
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                    />
                </svg>
            </span>
        </div>

        <div
            class="transition-all duration-300 bg-[#b3bac8] rounded-lg"
            v-show="showOptions"
        >
            <template v-for="(option, index) in options" :key="index">
                <div
                    @click="selectOption(option)"
                    class="py-2 pl-1 mx-2 border-b border-black hover:font-normal last:border-b-0"
                >
                    {{ option.value }}
                </div>
            </template>
        </div>
    </div>
</template>
