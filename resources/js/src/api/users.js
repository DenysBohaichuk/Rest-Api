import axios from 'axios';

const API_BASE_URL = '/api/v1/users';

export const fetchUsers = async (page = 1) => {
    const response = await axios.get(`${API_BASE_URL}?page=${page}`);
    return response.data;
};

export const createUser = async (formData, token) => {
    const response = await axios.post(API_BASE_URL, formData, {
        headers: {
            method: "POST",
            "Authorization": `Bearer ${token}`,
            'Content-Type': 'multipart/form-data',
        },
    });
    return response;
};

