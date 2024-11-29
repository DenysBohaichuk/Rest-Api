import axios from 'axios';

const API_BASE_URL = '/api/v1/positions';

export const fetchPositions = async () => {
    const response = await axios.get(`${API_BASE_URL}`);
    return response.data;
};


