// DriverDashboard.jsx
import React, { useState, useEffect } from "react";
import api from "../../services/api"; // Update import
import { useNavigate } from "react-router-dom";

const DriverDashboard = () => {
    const navigate = useNavigate();
    const [jobs, setJobs] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [updateLoading, setUpdateLoading] = useState(false);

    useEffect(() => {
        fetchJobs();
    }, []);

    const fetchJobs = async () => {
        try {
            setLoading(true);
            const token = localStorage.getItem("token");
            const response = await api.get("/api/jobs", {
                // Update axios to api
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            setJobs(response.data.jobs || []);
            setError(null);
        } catch (error) {
            setError(error.response?.data?.message || "Failed to fetch jobs");
            if (error.response?.status === 401) {
                navigate("/login");
            }
        } finally {
            setLoading(false);
        }
    };

    const handleStatusUpdate = async (jobId, newStatus) => {
        try {
            setUpdateLoading(true);
            const token = localStorage.getItem("token");
            await api.put(
                `/api/jobs/${jobId}`, // Update axios to api
                { status: newStatus },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );
            fetchJobs(); // Refresh jobs after update
            setError(null);
        } catch (error) {
            setError(
                error.response?.data?.message || "Failed to update status"
            );
        } finally {
            setUpdateLoading(false);
        }
    };

    // Helper function to get appropriate status badge color
    const getStatusBadgeColor = (status) => {
        const colors = {
            assigned: "bg-yellow-100 text-yellow-800",
            in_progress: "bg-blue-100 text-blue-800",
            completed: "bg-green-100 text-green-800",
            failed: "bg-red-100 text-red-800",
        };
        return colors[status] || "bg-gray-100 text-gray-800";
    };

    // Helper function to format status text
    const formatStatus = (status) => {
        return status
            .split("_")
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(" ");
    };

    if (loading) {
        return (
            <div className="flex items-center justify-center min-h-screen">
                <div className="text-center">
                    <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
                    <p className="mt-4 text-gray-600">Loading jobs...</p>
                </div>
            </div>
        );
    }

    return (
        <div className="p-6 max-w-7xl mx-auto">
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-bold text-gray-900">
                    My Deliveries
                </h1>
                <button
                    onClick={fetchJobs}
                    className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                >
                    Refresh
                </button>
            </div>

            {error && (
                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {error}
                </div>
            )}

            {jobs.length === 0 ? (
                <div className="text-center py-8 bg-white rounded-lg shadow">
                    <p className="text-gray-500">No deliveries assigned yet</p>
                </div>
            ) : (
                <div className="grid gap-6">
                    {jobs.map((job) => (
                        <div
                            key={job.id}
                            className="bg-white rounded-lg shadow p-6"
                        >
                            <div className="flex justify-between items-start mb-4">
                                <div>
                                    <h3 className="font-semibold text-lg">
                                        Delivery #{job.id}
                                    </h3>
                                    <span
                                        className={`inline-block px-3 py-1 rounded-full text-sm ${getStatusBadgeColor(
                                            job.status
                                        )} mt-2`}
                                    >
                                        {formatStatus(job.status)}
                                    </span>
                                </div>
                                <div className="text-right">
                                    <p className="text-sm text-gray-600">
                                        Recipient:
                                    </p>
                                    <p className="font-medium">
                                        {job.recipient_name}
                                    </p>
                                    <p className="text-sm text-gray-600">
                                        {job.recipient_phone}
                                    </p>
                                </div>
                            </div>

                            <div className="grid md:grid-cols-2 gap-4 mb-6">
                                <div className="bg-gray-50 p-4 rounded">
                                    <p className="text-sm text-gray-600">
                                        Pickup Location:
                                    </p>
                                    <p className="font-medium">
                                        {job.pickup_address}
                                    </p>
                                </div>
                                <div className="bg-gray-50 p-4 rounded">
                                    <p className="text-sm text-gray-600">
                                        Delivery Location:
                                    </p>
                                    <p className="font-medium">
                                        {job.delivery_address}
                                    </p>
                                </div>
                            </div>

                            <div className="flex flex-wrap gap-3">
                                <button
                                    onClick={() =>
                                        handleStatusUpdate(
                                            job.id,
                                            "in_progress"
                                        )
                                    }
                                    disabled={
                                        job.status === "in_progress" ||
                                        job.status === "completed" ||
                                        updateLoading
                                    }
                                    className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 
                           disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
                                >
                                    Start Delivery
                                </button>
                                <button
                                    onClick={() =>
                                        handleStatusUpdate(job.id, "completed")
                                    }
                                    disabled={
                                        job.status === "completed" ||
                                        job.status === "failed" ||
                                        updateLoading
                                    }
                                    className="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 
                           disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
                                >
                                    Mark Completed
                                </button>
                                <button
                                    onClick={() =>
                                        handleStatusUpdate(job.id, "failed")
                                    }
                                    disabled={
                                        job.status === "completed" ||
                                        job.status === "failed" ||
                                        updateLoading
                                    }
                                    className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 
                           disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
                                >
                                    Mark Failed
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default DriverDashboard;
