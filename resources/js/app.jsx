import './bootstrap';
import '../css/app.css';
import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import MainLayout from './components/layouts/MainLayout';
import Home from './pages/Home';
import AdminDashboard from './components/admin/Dashboard';
import DriverDashboard from './components/driver/Dashboard';

const App = () => {
    return (
        <Routes>
            <Route path="/" element={<MainLayout />}>
                <Route index element={<Home />} />
                <Route path="admin" element={<AdminDashboard />} />
                <Route path="driver" element={<DriverDashboard />} />
            </Route>
        </Routes>
    );
};

ReactDOM.createRoot(document.getElementById('app')).render(
    <BrowserRouter>
        <App />
    </BrowserRouter>
);