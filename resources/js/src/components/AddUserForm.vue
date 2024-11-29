

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
                :required="false"
                :error="formErrors.name"
            />
            <EmailField
                id="email"
                label="Email"
                type="email"
                v-model="form.email"
                :required="false"
                :error="formErrors.email"
            />
            <InputField
                id="phone"
                label="Телефон"
                type="text"
                v-model="form.phone"
                :required="false"
                :error="formErrors.phone"
            />

            <div class="relative">
                <button
                    @click.prevent="isOpen = !isOpen"
                    class="w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600"
                >
                    {{ selectedPosition ? selectedPosition.name : 'Вибір посади' }}
                </button>

                <ul
                    v-show="isOpen"
                    class="mt-2 max-h-48 overflow-y-auto rounded-md border border-gray-300 bg-white shadow-lg absolute w-full z-10"
                >
                    <li
                        v-for="position in positions"
                        :key="position.id"
                        @click.prevent="selectPosition(position)"
                        class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white"
                    >
                        {{ position.name }}
                    </li>
                </ul>
            </div>

            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700">Аватар</label>
                <input
                    @change="$emit('file-change', $event)"
                    type="file"
                    id="photo"
                    accept="image/*"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>
            <div class="flex gap-5">
                <button type="submit" @click.prevent="$emit('get-token')" class="w-full bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                    Отримати токен
                </button>
                <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    Додати
                </button>
            </div>
        </form>
    </section>
</template>


<script setup>
import InputField from "@/src/components/Base/Fields/InputField.vue";
import EmailField from "@/src/components/Base/Fields/EmailField.vue";
import {ref} from "vue";

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    formErrors: {
        type: Array,
        required: true,
    },
    positions: {
        type: Array,
        required: true,
    },
});
const emit = defineEmits(['submit-form', 'file-change', 'get-token']);

const isOpen = ref(false);
const selectedPosition = ref(null);

const selectPosition = (position) => {
    selectedPosition.value = position;
    isOpen.value = false;
    props.form.position = position.id;
};
</script>
