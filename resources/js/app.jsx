import './bootstrap';
import '../css/app.css';
import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import MainLayout from './components/layouts/MainLayout';
import Home from './pages/Home';
import AdminDashboard from './components/admin/Dashboard';
import DriverDashboard from './components/driver/Dashboard';
import Login from './pages/Login';
import Register from './pages/Register';

const App = () => {
    return (
        <Routes>
            <Route path="/" element={<MainLayout />}>
                <Route index element={<Home />} />
                <Route path="admin" element={<AdminDashboard />} />
                <Route path="driver" element={<DriverDashboard />} />
                <Route path="login" element={<Login />} />
                <Route path="register" element={<Register />} />
            </Route>
        </Routes>
    );
};

ReactDOM.createRoot(document.getElementById('app')).render(
    <BrowserRouter>
        <App />
    </BrowserRouter>
);