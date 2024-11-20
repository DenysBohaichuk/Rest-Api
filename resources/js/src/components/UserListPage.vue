<script setup>
import {ref} from 'vue';
import UserList from "@/src/components/UserList.vue";
import AddUserForm from "@/src/components/AddUserForm.vue";
import {createUser, fetchUsers} from "@/src/api/users.js";
import {handleApiError} from "@/src/api/utils.js";
import * as yup from 'yup';


const users = ref([]);
const page = ref(1);
const hasMore = ref(true);
const form = ref({
    name: '',
    email: '',
    profile_image: null,
});
const formErrors = ref([]);

const schema = yup.object().shape({
    name: yup.string().required('Ім\'я є обов\'язковим.'),
    email: yup.string().email('Некоректний формат email.').required('Email є обов\'язковим.'),
    profile_image: yup
        .mixed()
        .required('Аватар є обов\'язковим.')
        .test('fileSize', 'Розмір файлу має бути не більше 2MB.', (value) => {
            return value && value.size <= 2 * 1024 * 1024; // 2MB
        })
        .test('fileType', 'Непідтримуваний формат файлу (лише JPG/PNG/WEBP).', (value) => {
            return value && ['image/jpeg', 'image/png', 'image/webp'].includes(value.type);
        }),
});

const resetForm = () => {
    form.value.name = '';
    form.value.email = '';
    form.value.profile_image = null;
    document.getElementById('profile_image').value = '';
};

const loadUsers = async () => {
    try {
        const response = await fetchUsers(page.value);
        console.log(response)
        if (response.status) {
            users.value.push(...response.data.data);
            hasMore.value = response.data.current_page < response.data.last_page;
        } else {
            handleApiError(response.error);
        }
    } catch (err) {
        handleApiError(err.response?.data?.error || { code: 500, message: 'Виникла несподівана помилка.' });
    }
};

const loadMore = async () => {
    page.value++;
    await loadUsers();
};

const addUser = async () => {
    formErrors.value = [];
    try {
        console.log("Додавання користувача...", form.value);

        await schema.validate(form.value, { abortEarly: false });

        const formData = new FormData();
        formData.append('name', form.value.name);
        formData.append('email', form.value.email);
        formData.append('profile_image', form.value.profile_image);

        for (let [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
        }

        const response = await createUser(formData);

        console.log("Результат API:", response);

        if (response.status) {
            users.value.unshift(response.data);
            resetForm();
            alert('Користувач успішно доданий!');
        } else {
            handleApiError(response.error);
        }
    } catch (validationError) {
        console.error("Помилка валідації:", validationError);

        if(validationError?.message){
            formErrors.value = [validationError.message];
        }
        else if (validationError.response?.data?.error?.message) {
            formErrors.value = [validationError.response.data.error.message];
        } else if (validationError.response?.data?.message) {
            formErrors.value = [validationError.response.data.message];
        } else {
            formErrors.value = ["Сталася невідома помилка."];
        }
    }
};

const onFileChange = (event) => {
    const file = event.target.files[0];
    form.value.profile_image = file ? file : null;
};

loadUsers();
</script>

<template>
    <div class="max-w-4xl mx-auto p-8 bg-gray-100 min-h-screen">
        <header class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Керування Користувачами</h1>
        </header>

        <UserList :users="users" :hasMore="hasMore" @load-more="loadMore" />

        <AddUserForm
            :form="form"
            :formErrors="formErrors"
            @submit-form="addUser"
            @file-change="onFileChange"
        />
    </div>
</template>
