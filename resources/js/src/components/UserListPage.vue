<script setup>
import {ref} from 'vue';
import UserList from "@/src/components/UserList.vue";
import AddUserForm from "@/src/components/AddUserForm.vue";
import {createUser, fetchUsers} from "@/src/api/users.js";
import {handleApiError} from "@/src/api/utils.js";
import {fetchPositions} from "@/src/api/positions.js";
import {fetchToken} from "@/src/api/token.js";
//import * as yup from 'yup';

const token = ref(null);
const positions = ref([]);
const users = ref([]);
const page = ref(1);
const hasMore = ref(true);
const form = ref({
    name: '',
    email: '',
    phone: '',
    position: '',
    photo: null,
});
const formErrors = ref([]);

// Для валідації форми
/*const schema = yup.object().shape({
    name: yup.string().required('Ім\'я є обов\'язковим.'),
    email: yup.string().email('Некоректний формат email.').required('Email є обов\'язковим.'),
    photo: yup
        .mixed()
        .required('Аватар є обов\'язковим.')
        .test('fileSize', 'Розмір файлу має бути не більше 2MB.', (value) => {
            return value && value.size <= 2 * 1024 * 1024; // 2MB
        })
        .test('fileType', 'Непідтримуваний формат файлу (лише JPG/PNG/WEBP).', (value) => {
            return value && ['image/jpeg', 'image/png', 'image/webp'].includes(value.type);
        }),
});*/

const resetForm = () => {
    form.value.name = '';
    form.value.email = '';
    form.value.photo = null;
    document.getElementById('photo').value = '';
};

const loadUsers = async () => {
    try {
        const response = await fetchUsers(page.value);

        console.log(response)

        if (response.success) {
            users.value.push(...response.users);
            hasMore.value = response.page < response.total_pages;
        } else {
            handleApiError(response);
        }
    } catch (err) {
        handleApiError(err.response || { code: 500, message: 'Виникла несподівана помилка.' });
    }
};

const loadMore = async () => {
    page.value++;
    await loadUsers();
};

const loadPositions = async () =>{
    try {
        const response = await fetchPositions();

        if (response.success) {
            positions.value.push(...response.positions);
        } else {
            handleApiError(response);
        }
    } catch (err) {
        handleApiError(err.response || { code: 500, message: 'Виникла несподівана помилка.' });
    }
}
const getToken = async () =>{
    try {
        const response = await fetchToken();

        if (response.success) {
            token.value = response.token
            alert("Токен отримано")
        } else {
            handleApiError(response);
        }
    } catch (err) {
        handleApiError(err.response || { code: 500, message: 'Виникла несподівана помилка.' });
    }
}
const addUser = async () => {
    formErrors.value = [];
    try {

        if (!token.value) {
            alert("Токен не отримано!");
            return;
        }

        console.log("Додавання користувача...", form.value);

       // await schema.validate(form.value, { abortEarly: false });

        const formData = new FormData();
        formData.append('name', form.value.name);
        formData.append('email', form.value.email);
        formData.append('phone', form.value.phone);
        formData.append('position_id', form.value.position);
        formData.append('photo', form.value.photo);

        for (let [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
        }

        const response = await createUser(formData, token.value);

        console.log("Результат API:", response.data);

        if (response.data.success) {
            const tokenFromHeader = response.headers.get('Authorization');
            token.value = tokenFromHeader.split(' ')[1];

            resetForm();
            alert('Користувач успішно доданий!');
        } else {
            handleApiError(response.data);
        }
    } catch (validationError) {
        console.error("Помилка валідації:", validationError);

        if (validationError.response?.data?.fails) {
            formErrors.value = validationError.response.data.fails;
        }
        else if(validationError?.response?.data?.message){
            formErrors.value = [validationError.response?.data?.message];
        } else if(validationError?.message){
            formErrors.value = [validationError.message];
        } else {
            formErrors.value = ["Сталася невідома помилка."];
        }
    }
};
const onFileChange = (event) => {
    const file = event.target.files[0];
    form.value.photo = file ? file : null;
};

loadUsers();
loadPositions();
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
            :positions="positions"
            @submit-form="addUser"
            @get-token="getToken"
            @file-change="onFileChange"
        />
    </div>
</template>
