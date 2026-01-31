/**
 * AJAX Functions
 * Handles asynchronous requests and form interactions
 */

/**
 * Search students with autocomplete
 */
async function autocompleteSearch(input, resultContainerId) {
    const query = input.value;
    
    if (query.length < 2) {
        document.getElementById(resultContainerId).innerHTML = '';
        return;
    }
    
    try {
        const response = await fetch(`/student-record-system/public/search.php?query=${encodeURIComponent(query)}`);
        const data = await response.json();
        
        if (data.success && data.results.length > 0) {
            displayAutocompleteResults(data.results, resultContainerId);
        } else {
            document.getElementById(resultContainerId).innerHTML = '<div class="autocomplete-item">No results found</div>';
        }
    } catch (error) {
        console.error('Autocomplete error:', error);
    }
}

/**
 * Display autocomplete results
 */
function displayAutocompleteResults(results, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    
    results.slice(0, 10).forEach(result => {
        const item = document.createElement('div');
        item.className = 'autocomplete-item';
        item.innerHTML = `
            <strong>${result.roll_number}</strong> - ${result.full_name}
            <br><small>${result.email}</small>
        `;
        item.onclick = () => selectFromAutocomplete(result);
        container.appendChild(item);
    });
}

/**
 * Handle autocomplete selection
 */
function selectFromAutocomplete(student) {
    document.getElementById('student_id').value = student.id;
    document.getElementById('searchInput').value = student.full_name;
    document.getElementById('searchResults').innerHTML = '';
}

/**
 * Fetch student details
 */
async function fetchStudentDetails(studentId) {
    try {
        const response = await fetch(`/student-record-system/public/search.php?action=get_details&id=${studentId}`);
        const data = await response.json();
        
        if (data.success) {
            return data;
        } else {
            console.error('Error fetching student details:', data.message);
            return null;
        }
    } catch (error) {
        console.error('Fetch error:', error);
        return null;
    }
}

/**
 * Load grades for a student via AJAX
 */
async function loadStudentGrades(studentId, containerId) {
    try {
        const details = await fetchStudentDetails(studentId);
        if (!details) return;
        
        const container = document.getElementById(containerId);
        if (!details.grades || details.grades.length === 0) {
            container.innerHTML = '<p>No grades found</p>';
            return;
        }
        
        let html = '<table class="data-table"><thead><tr><th>Subject</th><th>Marks</th><th>Percentage</th><th>Grade</th></tr></thead><tbody>';
        
        details.grades.forEach(grade => {
            html += `
                <tr>
                    <td>${grade.subject}</td>
                    <td>${grade.marks_obtained}/${grade.total_marks}</td>
                    <td>${grade.percentage}%</td>
                    <td><span class="grade-badge">${grade.grade}</span></td>
                </tr>
            `;
        });
        
        html += '</tbody></table>';
        container.innerHTML = html;
    } catch (error) {
        console.error('Error loading grades:', error);
    }
}

/**
 * Load attendance for a student via AJAX
 */
async function loadStudentAttendance(studentId, containerId) {
    try {
        const details = await fetchStudentDetails(studentId);
        if (!details) return;
        
        const container = document.getElementById(containerId);
        const summary = details.attendance_summary || {};
        
        let html = `
            <div class="attendance-summary">
                <p><strong>Attendance Percentage:</strong> ${summary.attendance_percentage || 0}%</p>
                <p><strong>Present:</strong> ${summary.present_days || 0} days</p>
                <p><strong>Absent:</strong> ${summary.absent_days || 0} days</p>
                <p><strong>Late:</strong> ${summary.late_days || 0} days</p>
            </div>
        `;
        
        container.innerHTML = html;
    } catch (error) {
        console.error('Error loading attendance:', error);
    }
}

/**
 * Submit form with AJAX (no page reload)
 */
async function submitFormAjax(formId, successCallback) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    try {
        const formData = new FormData(form);
        const response = await fetch(form.action || window.location.href, {
            method: 'POST',
            body: formData,
        });
        
        const data = await response.json();
        
        if (data.success && successCallback) {
            successCallback(data);
        } else {
            console.error('Form submission error:', data.message);
        }
    } catch (error) {
        console.error('Fetch error:', error);
    }
}

/**
 * Validate email exists (check duplicate)
 */
async function validateEmailExists(email) {
    try {
        const response = await fetch(`/student-record-system/public/search.php?check_email=${encodeURIComponent(email)}`);
        const data = await response.json();
        return data.success && data.exists;
    } catch (error) {
        console.error('Validation error:', error);
        return false;
    }
}

/**
 * Show loading indicator
 */
function showLoading(elementId) {
    const el = document.getElementById(elementId);
    if (el) {
        el.innerHTML = '<div class="loading">Loading...</div>';
    }
}

/**
 * Show error message
 */
function showError(elementId, message) {
    const el = document.getElementById(elementId);
    if (el) {
        el.innerHTML = `<div class="alert alert-danger">${message}</div>`;
    }
}

/**
 * Show success message
 */
function showSuccess(elementId, message) {
    const el = document.getElementById(elementId);
    if (el) {
        el.innerHTML = `<div class="alert alert-success">${message}</div>`;
    }
}
