import "./bootstrap";
import "../css/app.css";
import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { AuthProvider } from "./context/AuthContext";
import MainLayout from "./components/layouts/MainLayout";
import Home from "./pages/Home";
import AdminDashboard from "./components/admin/AdminDashboard";
import DriverDashboard from "./components/driver/DriverDashboard";
import Login from "./pages/Login";
import Register from "./pages/Register";
import ProtectedRoute from "./components/ProtectedRoute";


const App = () => {
    return (
        <AuthProvider>
            <Routes>
                <Route path="/" element={<MainLayout />}>
                    <Route index element={<Home />} />
                    <Route
                        path="admin/dashboard"
                        element={
                            <ProtectedRoute
                                element={<AdminDashboard />}
                                allowedRoles={["admin"]}
                            />
                        }
                    />
                    <Route
                        path="driver/dashboard"
                        element={
                            <ProtectedRoute
                                element={<DriverDashboard />}
                                allowedRoles={["driver"]}
                            />
                        }
                    />
                    <Route path="login" element={<Login />} />
                    <Route path="register" element={<Register />} />
                </Route>
            </Routes>
        </AuthProvider>
    );
};

ReactDOM.createRoot(document.getElementById("app")).render(
    <React.StrictMode>
        <BrowserRouter>
            <App />
        </BrowserRouter>
    </React.StrictMode>
);
