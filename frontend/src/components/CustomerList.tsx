import React, { useEffect, useState } from 'react';
import {
    Container,
    Paper,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableHead,
    TableRow,
    Button,
    TextField,
    Box,
    Typography,
    IconButton
} from '@mui/material';
import { Customer, customerService } from '../services/customerService';
import { useNavigate } from 'react-router-dom';

export const CustomerList: React.FC = () => {
    const [customers, setCustomers] = useState<Customer[]>([]);
    const [search, setSearch] = useState('');
    const navigate = useNavigate();

    const loadCustomers = async (searchTerm?: string) => {
        try {
            const data = await customerService.getAll(searchTerm);
            setCustomers(data);
        } catch (error) {
            console.error('Failed to load customers:', error);
        }
    };

    useEffect(() => {
        loadCustomers();
    }, []);

    const handleSearch = (event: React.ChangeEvent<HTMLInputElement>) => {
        setSearch(event.target.value);
        loadCustomers(event.target.value);
    };

    const handleDelete = async (id: number) => {
        if (window.confirm('Are you sure you want to delete this customer?')) {
            try {
                await customerService.delete(id);
                loadCustomers();
            } catch (error) {
                console.error('Failed to delete customer:', error);
            }
        }
    };

    return (
        <Container maxWidth="lg" sx={{ mt: 4 }}>
            <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
                <Typography variant="h4">Customers</Typography>
                <Button
                    variant="contained"
                    color="primary"
                    onClick={() => navigate('/customers/new')}
                >
                    Add Customer
                </Button>
            </Box>

            <TextField
                fullWidth
                label="Search customers"
                variant="outlined"
                value={search}
                onChange={handleSearch}
                sx={{ mb: 3 }}
            />

            <TableContainer component={Paper}>
                <Table>
                    <TableHead>
                        <TableRow>
                            <TableCell>First Name</TableCell>
                            <TableCell>Last Name</TableCell>
                            <TableCell>Email</TableCell>
                            <TableCell>Contact Number</TableCell>
                            <TableCell>Actions</TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {customers.map((customer) => (
                            <TableRow key={customer.id}>
                                <TableCell>{customer.first_name}</TableCell>
                                <TableCell>{customer.last_name}</TableCell>
                                <TableCell>{customer.email}</TableCell>
                                <TableCell>{customer.contact_number}</TableCell>
                                <TableCell>
                                    <Button
                                        color="primary"
                                        onClick={() => navigate(`/customers/${customer.id}`)}
                                    >
                                        Edit
                                    </Button>
                                    <Button
                                        color="error"
                                        onClick={() => customer.id && handleDelete(customer.id)}
                                    >
                                        Delete
                                    </Button>
                                </TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                </Table>
            </TableContainer>
        </Container>
    );
}; 