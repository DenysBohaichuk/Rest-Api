<template>
  <div class="relative rounded-md shadow-sm">
    <label :for="id" class="absolute -top-2 left-2 inline-block bg-white px-1 text-xs font-medium"
           :class="{ 'text-red-400': error }">{{ label }}</label>
    <input
        :type="type"
        :id="id"
        v-bind="$attrs"
        :value="modelValue"
        class="block w-full rounded-md border-0 py-1.5 pl-3 sm:text-sm sm:leading-6 focus:outline-none focus:ring-azure"
        :class="{
        'text-red-900 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500': error,
        'text-grey ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset ': !error
      }"
        @input="updateValue"
        :placeholder="label"
        :required="required" />
  </div>
  <span v-if="error" class="block pt-1 text-red-400">{{ error }}</span>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({
  label: String,
  id: String,
  type: String,
  required: Boolean,
  modelValue: String,
  error: String,
});

const emit = defineEmits(['update:modelValue']);

const updateValue = (event) => {
  emit('update:modelValue', event.target.value);
};
</script>
