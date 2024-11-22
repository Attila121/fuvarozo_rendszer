// Navbar.jsx 
import React from 'react';
import { Link } from 'react-router-dom';

const Navbar = () => {
  return (
    <nav className="bg-white shadow-lg">
      <div className="container mx-auto px-4">
        <div className="flex justify-between items-center h-16">
          <Link to="/" className="font-bold text-xl">
            Delivery System
          </Link>
          <div className="flex space-x-4">
            <Link to="/login" className="text-gray-700 hover:text-gray-900">
              Login
            </Link>
            <Link to="/register" className="text-gray-700 hover:text-gray-900">
              Register
            </Link>
          </div>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;