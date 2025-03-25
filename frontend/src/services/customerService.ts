import axios from 'axios';

const API_URL = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';

export interface Customer {
    id?: number;
    first_name: string;
    last_name: string;
    email: string;
    contact_number: string;
}

export const customerService = {
    getAll: async (search?: string) => {
        const response = await axios.get(`${API_URL}/customers${search ? `?search=${search}` : ''}`);
        return response.data;
    },

    getById: async (id: number) => {
        const response = await axios.get(`${API_URL}/customers/${id}`);
        return response.data;
    },

    create: async (customer: Customer) => {
        const response = await axios.post(`${API_URL}/customers`, customer);
        return response.data;
    },

    update: async (id: number, customer: Customer) => {
        const response = await axios.put(`${API_URL}/customers/${id}`, customer);
        return response.data;
    },

    delete: async (id: number) => {
        await axios.delete(`${API_URL}/customers/${id}`);
    }
}; 