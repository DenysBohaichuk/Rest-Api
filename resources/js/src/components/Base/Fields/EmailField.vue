<template>

  <div class="relative mt-2 rounded-md shadow-sm">
    <label :for="id"
           class="absolute -top-2 left-2 inline-block bg-white px-1 text-xs font-medium "
           :class="{'text-red-400' : error}"
    >{{ label }}</label>

    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
      <EnvelopeIcon
          :class="{'text-red-400' : error}"
          class="h-5 w-5"
          aria-hidden="true"
      />
    </div>
    <input
        type="email"
        :name="name"
        :id="id"
        :value="modelValue"
        @input="updateValue"
        class="block w-full rounded-md border-0 py-1.5 pl-10 sm:text-sm sm:leading-6 focus:outline-none focus:ring-azure"
        :class="error ? 'pr-10 text-red-900 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500' : 'text-grey ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset '"
        placeholder="you@example.com"
        :required="required"
    />
  </div>
  <span v-if="error"
        :class="{'text-red-400' : error}"
        class="block pt-1"
  >{{ error }}</span>

</template>

<script setup>

import { EnvelopeIcon } from "@heroicons/vue/24/outline";

import { defineProps, defineEmits } from 'vue';

defineProps({
  label: String,
  id: String,
  name: String,
  required: Boolean,
  modelValue: String,
  error: String,
});

const emit = defineEmits(['update:modelValue']);

const updateValue = (event) => {
  emit('update:modelValue', event.target.value);
};
</script>

<style scoped>

</style>