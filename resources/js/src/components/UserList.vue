<script setup>

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    hasMore: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(['load-more']);
</script>

<template>
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6">Список користувачів</h2>
        <div
            v-for="user in users"
            :key="user.id"
            class="p-5 mb-4 bg-white rounded-lg shadow-md flex items-center gap-4 sm:gap-6"
        >
            <img
                :src="`/storage/${user.profile_image}`"
                alt="Avatar"
                class="w-16 h-16 rounded-full border border-gray-300"
            />
            <div class="text-center sm:text-left">
                <p class="text-lg font-bold text-gray-900">{{ user.name }}</p>
                <p class="text-sm text-gray-600">{{ user.email }}</p>
                <p class="text-sm text-gray-400">{{ user.phone }}</p>
                <p class="text-xs text-gray-300">UUID: {{ user.uuid }}</p>
            </div>
        </div>

        <button
            v-if="hasMore"
            @click="$emit('load-more')"
            class="bg-blue-500 text-white px-4 py-2 rounded-md shadow"
        >
            Завантажити більше
        </button>
    </section>
</template>
