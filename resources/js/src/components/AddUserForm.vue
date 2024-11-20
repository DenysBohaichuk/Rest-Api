<script setup>
import InputField from "@/src/components/Base/Fields/InputField.vue";
import EmailField from "@/src/components/Base/Fields/EmailField.vue";

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    formErrors: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['submit-form', 'file-change']);
</script>

<template>
    <section>
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Додати Користувача</h2>
        <form @submit.prevent="$emit('submit-form')" class="bg-white p-8 rounded-lg shadow-md space-y-6">

            <div v-if="formErrors.length" class="bg-red-100 border border-red-400 text-red-800 p-4 rounded">
                <ul>
                    <li v-for="(err, idx) in formErrors" :key="idx">{{ err }}</li>
                </ul>
            </div>

            <InputField
                id="name"
                label="Ім'я"
                type="text"
                v-model="form.name"
                :required="true"
                :error="formErrors.name"
            />
            <EmailField
                id="email"
                label="Email"
                type="email"
                v-model="form.email"
                :required="true"
                :error="formErrors.email"
            />
            <div>
                <label for="profile_image" class="block text-sm font-medium text-gray-700">Аватар</label>
                <input
                    @change="$emit('file-change', $event)"
                    type="file"
                    id="profile_image"
                    accept="image/*"
                    required
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>
            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                Додати
            </button>
        </form>
    </section>
</template>
