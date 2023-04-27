<script setup>
import { onMounted, ref } from "vue";

defineProps(["modelValue"]);

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="pl-4 border-gray-300 shadow-xl rounded-3xl focus:ring-0"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
