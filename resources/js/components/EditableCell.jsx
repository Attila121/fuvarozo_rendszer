import React, { useState, useEffect } from "react";
const EditableCell = ({ value, field, job, type = "text" }) => {
    const [isEditing, setIsEditing] = useState(false);
    const inputRef = React.useRef(null);
    const [localValue, setLocalValue] = useState(value);

    // Handle clicking outside to save
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (inputRef.current && !inputRef.current.contains(event.target)) {
                handleSave();
            }
        };

        if (isEditing) {
            document.addEventListener('mousedown', handleClickOutside);
        }

        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [isEditing]);

    // Update local value when prop changes
    useEffect(() => {
        setLocalValue(value);
    }, [value]);

    const handleSave = async () => {
        if (localValue !== value) {
            try {
                const response = await api.put(`/api/jobs/${job.id}`, {
                    ...job,
                    [field]: localValue
                });
                
                // Update the jobs state in parent component
                setJobs(prevJobs => prevJobs.map(j => 
                    j.id === job.id ? {...j, [field]: localValue} : j
                ));
            } catch (err) {
                // Revert on error
                setLocalValue(value);
                setError(err.response?.data?.message || "Failed to update field");
            }
        }
        setIsEditing(false);
    };

    if (isEditing) {
        return type === "select" ? (
            <select
                ref={inputRef}
                className="w-full p-1 border rounded"
                value={localValue}
                onChange={(e) => setLocalValue(e.target.value)}
                onBlur={handleSave}
                onKeyDown={(e) => {
                    if (e.key === 'Enter') handleSave();
                    if (e.key === 'Escape') {
                        setLocalValue(value);
                        setIsEditing(false);
                    }
                }}
                autoFocus
            >
                <option value="assigned">Assigned</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
            </select>
        ) : (
            <input
                ref={inputRef}
                type="text"
                className="w-full p-1 border rounded"
                value={localValue}
                onChange={(e) => setLocalValue(e.target.value)}
                onBlur={handleSave}
                onKeyDown={(e) => {
                    if (e.key === 'Enter') handleSave();
                    if (e.key === 'Escape') {
                        setLocalValue(value);
                        setIsEditing(false);
                    }
                }}
                autoFocus
            />
        );
    }

    return type === "select" ? (
        <div 
            onClick={() => setIsEditing(true)}
            className="cursor-pointer hover:bg-gray-50 p-1 rounded"
        >
            <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                ${value === "completed" ? "bg-green-100 text-green-800" :
                  value === "failed" ? "bg-red-100 text-red-800" :
                  value === "in_progress" ? "bg-blue-100 text-blue-800" :
                  "bg-gray-100 text-gray-800"}`}
            >
                {value.replace('_', ' ')}
            </span>
        </div>
    ) : (
        <div 
            onClick={() => setIsEditing(true)}
            className="cursor-pointer hover:bg-gray-50 p-1 rounded"
        >
            {value}
        </div>
    );
};

export default EditableCell;