// Register.jsx 
import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

const Register = () => {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
    });

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await axios.post('/api/register', formData);
            navigate('/login');
        } catch (error) {
            console.error('Registration error:', error);
        }
    };

    return (
        <div className="flex  justify-center">
            <form onSubmit={handleSubmit} className="bg-white p-8 rounded shadow-md w-96">
                <h2 className="text-2xl mb-6 text-center">Register</h2>
                
                <div className="mb-4">
                    <input
                        type="text"
                        name="name"
                        placeholder="Name"
                        value={formData.name}
                        onChange={handleChange}
                        className="w-full p-2 border rounded"
                        required
                    />
                </div>

                <div className="mb-4">
                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        value={formData.email}
                        onChange={handleChange}
                        className="w-full p-2 border rounded"
                        required
                    />
                </div>

                <div className="mb-4">
                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        value={formData.password}
                        onChange={handleChange}
                        className="w-full p-2 border rounded"
                        required
                    />
                </div>

                <div className="mb-6">
                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm Password"
                        value={formData.password_confirmation}
                        onChange={handleChange}
                        className="w-full p-2 border rounded"
                        required
                    />
                </div>

                <button
                    type="submit"
                    className="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
                >
                    Register
                </button>
            </form>
        </div>
    );
};

export default Register;    