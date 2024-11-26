import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import api from "../../services/api";
import EditableCell from "../EditableCell";

const AdminDashboard = () => {
    const navigate = useNavigate();
    const [jobs, setJobs] = useState([]);
    const [drivers, setDrivers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [editingId, setEditingId] = useState(null);
    const [editingData, setEditingData] = useState({});
    const [editingFields, setEditingFields] = useState({});

    const [newJob, setNewJob] = useState({
        pickup_address: "",
        delivery_address: "",
        recipient_name: "",
        recipient_phone: "",
        driver_id: "",
    });

    const handleEdit = (job) => {
        setEditingId(job.id);
        // Make sure we capture all editable fields
        setEditingData({
            pickup_address: job.pickup_address,
            delivery_address: job.delivery_address,
            recipient_name: job.recipient_name,
            recipient_phone: job.recipient_phone,
            status: job.status,
            driver_id: job.driver_id,
        });
    };

    const handleSaveEdit = async (jobId, field, value) => {
        try {
            setLoading(true);
            const updatedData = {
                ...jobs.find((job) => job.id === jobId),
                [field]: value,
            };

            const response = await api.put(`/api/jobs/${jobId}`, updatedData);

            setJobs(
                jobs.map((job) =>
                    job.id === jobId ? { ...job, [field]: value } : job
                )
            );
        } catch (err) {
            setError(err.response?.data?.message || "Failed to update job");
        } finally {
            setLoading(false);
        }
    };

    const StatusCell = ({ value, job }) => {
        const [isEditing, setIsEditing] = useState(false);
        const [localStatus, setLocalStatus] = useState(value);

        const statusClasses = {
            completed: "bg-green-100 text-green-800",
            failed: "bg-red-100 text-red-800",
            in_progress: "bg-blue-100 text-blue-800",
            assigned: "bg-gray-100 text-gray-800",
        };

        const handleStatusChange = async (newStatus) => {
            try {
                const response = await api.put(`/api/jobs/${job.id}`, {
                    ...job,
                    status: newStatus,
                });

                setLocalStatus(newStatus);
                // Update the jobs state in the parent component
                setJobs((prevJobs) =>
                    prevJobs.map((j) =>
                        j.id === job.id ? { ...j, status: newStatus } : j
                    )
                );
            } catch (err) {
                setError(
                    err.response?.data?.message || "Failed to update status"
                );
            } finally {
                setIsEditing(false);
            }
        };

        return (
            <div className="group flex items-center justify-between">
                {isEditing ? (
                    <select
                        className="p-1 border rounded w-full"
                        value={localStatus}
                        onChange={(e) => handleStatusChange(e.target.value)}
                        onBlur={() => setIsEditing(false)}
                        autoFocus
                    >
                        <option value="assigned">Assigned</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                ) : (
                    <>
                        <span
                            className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClasses[value]}`}
                        >
                            {value.replace("_", " ")}
                        </span>
                        <button
                            onClick={() => setIsEditing(true)}
                            className="invisible group-hover:visible text-blue-600 hover:text-blue-800 ml-2"
                        >
                            Edit
                        </button>
                    </>
                )}
            </div>
        );
    };

    // Check authentication on component mount
    useEffect(() => {
        const user = JSON.parse(localStorage.getItem("user"));
        if (!user || user.role !== "admin") {
            navigate("/login");
            return;
        }
        fetchData();
    }, [navigate]);

    const fetchData = async () => {
        try {
            setLoading(true);
            setError(null);

            const [jobsResponse, driversResponse] = await Promise.all([
                api.get("/api/jobs"),
                api.get("/api/drivers"),
            ]);

            setJobs(jobsResponse.data.jobs || []);
            setDrivers(driversResponse.data.drivers || []);
        } catch (err) {
            console.error("Error fetching data:", err);
            setError(
                err.response?.data?.message || "Failed to load dashboard data"
            );
            if (err.response?.status === 401) {
                navigate("/login");
            }
        } finally {
            setLoading(false);
        }
    };

    const handleCreateJob = async (e) => {
        e.preventDefault();
        try {
            setLoading(true);
            setError(null);

            const response = await api.post("/api/jobs", newJob);

            setJobs((prevJobs) => [...prevJobs, response.data.job]);
            setNewJob({
                pickup_address: "",
                delivery_address: "",
                recipient_name: "",
                recipient_phone: "",
                driver_id: "",
            });
        } catch (err) {
            setError(err.response?.data?.message || "Failed to create job");
        } finally {
            setLoading(false);
        }
    };

    const handleDeleteJob = async (jobId) => {
        if (!window.confirm("Are you sure you want to delete this job?")) {
            return;
        }

        try {
            setLoading(true);
            setError(null);

            await api.delete(`/api/jobs/${jobId}`);
            setJobs((prevJobs) => prevJobs.filter((job) => job.id !== jobId));
        } catch (err) {
            setError(err.response?.data?.message || "Failed to delete job");
        } finally {
            setLoading(false);
        }
    };

    const handleAssignDriver = async (jobId, driverId) => {
        try {
            setLoading(true);
            setError(null);

            await api.post(`/api/jobs/${jobId}/assign`, {
                driver_id: driverId,
            });

            // Update the local state to reflect the change
            setJobs((prevJobs) =>
                prevJobs.map((job) =>
                    job.id === jobId ? { ...job, driver_id: driverId } : job
                )
            );
        } catch (err) {
            setError(err.response?.data?.message || "Failed to assign driver");
        } finally {
            setLoading(false);
        }
    };

    if (loading && !jobs.length) {
        return (
            <div className="flex items-center justify-center min-h-screen">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            </div>
        );
    }

    return (
        <div className="p-6">
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-bold">Admin Dashboard</h1>
                <button
                    onClick={fetchData}
                    className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                    Refresh Data
                </button>
            </div>

            {error && (
                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {error}
                </div>
            )}

            {/* Create New Job Form */}
            <div className="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h2 className="text-xl font-semibold mb-4">Create New Job</h2>
                <form onSubmit={handleCreateJob} className="space-y-4">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input
                            type="text"
                            placeholder="Pickup Address"
                            className="p-2 border rounded"
                            value={newJob.pickup_address}
                            onChange={(e) =>
                                setNewJob({
                                    ...newJob,
                                    pickup_address: e.target.value,
                                })
                            }
                            required
                        />
                        <input
                            type="text"
                            placeholder="Delivery Address"
                            className="p-2 border rounded"
                            value={newJob.delivery_address}
                            onChange={(e) =>
                                setNewJob({
                                    ...newJob,
                                    delivery_address: e.target.value,
                                })
                            }
                            required
                        />
                        <input
                            type="text"
                            placeholder="Recipient Name"
                            className="p-2 border rounded"
                            value={newJob.recipient_name}
                            onChange={(e) =>
                                setNewJob({
                                    ...newJob,
                                    recipient_name: e.target.value,
                                })
                            }
                            required
                        />
                        <input
                            type="text"
                            placeholder="Recipient Phone"
                            className="p-2 border rounded"
                            value={newJob.recipient_phone}
                            onChange={(e) =>
                                setNewJob({
                                    ...newJob,
                                    recipient_phone: e.target.value,
                                })
                            }
                            required
                        />
                        <select
                            className="p-2 border rounded"
                            value={newJob.driver_id}
                            onChange={(e) =>
                                setNewJob({
                                    ...newJob,
                                    driver_id: e.target.value,
                                })
                            }
                            required
                        >
                            <option value="">Select Driver</option>
                            {drivers.map((driver) => (
                                <option key={driver.id} value={driver.id}>
                                    {driver.name}
                                </option>
                            ))}
                        </select>
                    </div>
                    <button
                        type="submit"
                        disabled={loading}
                        className={`w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 ${
                            loading ? "opacity-50 cursor-not-allowed" : ""
                        }`}
                    >
                        {loading ? "Creating..." : "Create Job"}
                    </button>
                </form>
            </div>

            {/* Jobs List */}
            <div className="bg-white p-6 rounded-lg shadow-sm">
                <h2 className="text-xl font-semibold mb-4">Jobs List</h2>
                <div className="overflow-x-auto">
                    <table className="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pickup
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Delivery
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Recipient
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Driver
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody className="bg-white divide-y divide-gray-200">
                            {jobs.map((job) => (
                                <tr key={job.id}>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <EditableCell
                                            value={job.pickup_address}
                                            field="pickup_address"
                                            job={job}
                                        />
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <EditableCell
                                            value={job.delivery_address}
                                            field="delivery_address"
                                            job={job}
                                        />
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <EditableCell
                                            value={job.recipient_name}
                                            field="recipient_name"
                                            job={job}
                                        />
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <EditableCell
                                            value={job.recipient_phone}
                                            field="recipient_phone"
                                            job={job}
                                        />
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <EditableCell
                                            value={job.status}
                                            field="status"
                                            job={job}
                                            type="select"
                                        />
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <select
                                            className="border rounded p-1"
                                            value={job.driver_id || ""}
                                            onChange={(e) =>
                                                handleAssignDriver(
                                                    job.id,
                                                    e.target.value
                                                )
                                            }
                                        >
                                            <option value="">
                                                Select Driver
                                            </option>
                                            {drivers.map((driver) => (
                                                <option
                                                    key={driver.id}
                                                    value={driver.id}
                                                >
                                                    {driver.name}
                                                </option>
                                            ))}
                                        </select>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        {editingId === job.id ? (
                                            <div className="space-x-2">
                                                <button
                                                    onClick={handleSaveEdit}
                                                    className="text-green-600 hover:text-green-900"
                                                >
                                                    Save
                                                </button>
                                                <button
                                                    onClick={() => {
                                                        setEditingId(null);
                                                        setEditingData({});
                                                    }}
                                                    className="text-gray-600 hover:text-gray-900"
                                                >
                                                    Cancel
                                                </button>
                                            </div>
                                        ) : (
                                            <button
                                                onClick={() =>
                                                    handleDeleteJob(job.id)
                                                }
                                                className="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
};

export default AdminDashboard;
