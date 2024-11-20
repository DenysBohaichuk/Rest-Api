import axios from 'axios';

const API_BASE_URL = '/api/users';

export const fetchUsers = async (page = 1) => {
    const response = await axios.get(`${API_BASE_URL}?page=${page}`);
    return response.data;
};

export const createUser = async (formData) => {
    const response = await axios.post(API_BASE_URL, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
    return response.data;
};

