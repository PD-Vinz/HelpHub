function updateTicketTable() {
    fetch('fetch/get-tickets.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('ticketTableBody');
            tableBody.innerHTML = ''; // Clear existing rows

            data.forEach(ticket => {
                const row = document.createElement('tr');
                row.className = `odd gradeX ${getStatusClass(ticket.status)}`;

                row.innerHTML = `
                    <td>${getPriorityIcon(ticket.created_date, ticket.status)}</td>
                    <td>${escapeHtml(ticket.ticket_id)}</td>
                    <td>${escapeHtml(ticket.status)}</td>
                    <td>${escapeHtml(ticket.employee)}</td>
                    <td>${escapeHtml(ticket.created_date)}</td>
                    <td>${escapeHtml(ticket.full_name)}</td>
                    <td>${escapeHtml(ticket.issue)}</td>
                    <td>
                        <button class="btn btn-primary btn-xs load-details" data-ticket_id="${escapeHtml(ticket.ticket_id)}" data-status="${escapeHtml(ticket.status)}">
                            View Details
                        </button>
                    </td>
                `;

                tableBody.appendChild(row);
            });

            // Reinitialize DataTables
            if ($.fn.DataTable.isDataTable('#ticketTable')) {
                $('#ticketTable').DataTable().destroy();
            }
            $('#ticketTable').DataTable({
                // Your existing DataTable options
            });
        })
        .catch(error => console.error('Error:', error));
}

function getStatusClass(status) {
    switch (status) {
        case 'Resolved': return 'success';
        case 'Pending': return 'danger';
        case 'Processing': return 'warning';
        case 'Transferred': return 'info';
        default: return '';
    }
}

function getPriorityIcon(createdDate, status) {
    const now = new Date();
    const created = new Date(createdDate);
    const hoursElapsed = (now - created) / (1000 * 60 * 60);

    if (['Pending', 'Processing'].includes(status) && hoursElapsed >= 48) {
        return '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>';
    }
    return '';
}

function escapeHtml(unsafe) {
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

// Update the table every 30 seconds
setInterval(updateTicketTable, 30000);

// Initial update
document.addEventListener('DOMContentLoaded', updateTicketTable);