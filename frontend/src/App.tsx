import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { CustomerList } from './components/CustomerList';
import { CustomerForm } from './components/CustomerForm';
import { Container, CssBaseline, ThemeProvider, createTheme } from '@mui/material';

const theme = createTheme();

function App() {
  return (
    <ThemeProvider theme={theme}>
      <CssBaseline />
      <Router>
        <Routes>
          <Route path="/customers" element={<CustomerList />} />
          <Route path="/customers/:id" element={<CustomerForm />} />
          <Route path="/" element={<Navigate to="/customers" replace />} />
        </Routes>
      </Router>
    </ThemeProvider>
  );
}

export default App;
