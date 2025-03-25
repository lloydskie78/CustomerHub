import React, { useEffect, useState } from 'react';
import {
    Container,
    Paper,
    TextField,
    Button,
    Box,
    Typography,
    Grid
} from '@mui/material';
import { Customer, customerService } from '../services/customerService';
import { useNavigate, useParams } from 'react-router-dom';

export const CustomerForm: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const navigate = useNavigate();
    const [formData, setFormData] = useState<Customer>({
        first_name: '',
        last_name: '',
        email: '',
        contact_number: ''
    });
    const [errors, setErrors] = useState<Partial<Customer>>({});

    useEffect(() => {
        if (id && id !== 'new') {
            loadCustomer(parseInt(id));
        }
    }, [id]);

    const loadCustomer = async (customerId: number) => {
        try {
            const data = await customerService.getById(customerId);
            setFormData(data);
        } catch (error) {
            console.error('Failed to load customer:', error);
            navigate('/customers');
        }
    };

    const validateForm = (): boolean => {
        const newErrors: Partial<Customer> = {};
        
        if (!formData.first_name) {
            newErrors.first_name = 'First name is required';
        }
        if (!formData.last_name) {
            newErrors.last_name = 'Last name is required';
        }
        if (!formData.email) {
            newErrors.email = 'Email is required';
        } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
            newErrors.email = 'Invalid email format';
        }
        if (!formData.contact_number) {
            newErrors.contact_number = 'Contact number is required';
        }

        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }

        try {
            if (id && id !== 'new') {
                await customerService.update(parseInt(id), formData);
            } else {
                await customerService.create(formData);
            }
            navigate('/customers');
        } catch (error: any) {
            if (error.response?.data?.errors) {
                setErrors(error.response.data.errors);
            } else {
                console.error('Failed to save customer:', error);
            }
        }
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
        // Clear error when user starts typing
        if (errors[name as keyof Customer]) {
            setErrors(prev => ({
                ...prev,
                [name]: undefined
            }));
        }
    };

    return (
        <Container maxWidth="md" sx={{ mt: 4 }}>
            <Paper sx={{ p: 3 }}>
                <Typography variant="h4" gutterBottom>
                    {id && id !== 'new' ? 'Edit Customer' : 'New Customer'}
                </Typography>
                
                <form onSubmit={handleSubmit}>
                    <Grid container spacing={3}>
                        <Grid item xs={12} sm={6}>
                            <TextField
                                fullWidth
                                label="First Name"
                                name="first_name"
                                value={formData.first_name}
                                onChange={handleChange}
                                error={!!errors.first_name}
                                helperText={errors.first_name}
                            />
                        </Grid>
                        <Grid item xs={12} sm={6}>
                            <TextField
                                fullWidth
                                label="Last Name"
                                name="last_name"
                                value={formData.last_name}
                                onChange={handleChange}
                                error={!!errors.last_name}
                                helperText={errors.last_name}
                            />
                        </Grid>
                        <Grid item xs={12}>
                            <TextField
                                fullWidth
                                label="Email"
                                name="email"
                                type="email"
                                value={formData.email}
                                onChange={handleChange}
                                error={!!errors.email}
                                helperText={errors.email}
                            />
                        </Grid>
                        <Grid item xs={12}>
                            <TextField
                                fullWidth
                                label="Contact Number"
                                name="contact_number"
                                value={formData.contact_number}
                                onChange={handleChange}
                                error={!!errors.contact_number}
                                helperText={errors.contact_number}
                            />
                        </Grid>
                    </Grid>

                    <Box sx={{ mt: 3, display: 'flex', gap: 2 }}>
                        <Button
                            variant="contained"
                            color="primary"
                            type="submit"
                        >
                            Save
                        </Button>
                        <Button
                            variant="outlined"
                            onClick={() => navigate('/customers')}
                        >
                            Cancel
                        </Button>
                    </Box>
                </form>
            </Paper>
        </Container>
    );
}; 