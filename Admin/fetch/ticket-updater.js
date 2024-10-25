function savePageLengthPreference(length) {
  localStorage.setItem('ticketTablePageLength', length);
}

function getPageLengthPreference() {
  return parseInt(localStorage.getItem('ticketTablePageLength')) || 10; // Default to 10 if not set
}

function updateTicketTable() {
  fetch('fetch/get-tickets.php')
      .then(response => response.json())
      .then(data => {
          const tableBody = document.getElementById('ticketTableBody');
          tableBody.innerHTML = ''; // Clear existing rows

          if (data.error) {
              console.error('Error from server:', data.error, data.details);
              tableBody.innerHTML = `<tr><td colspan="8">Error: ${data.error}</td></tr>`;
              return;
          }

          if (!Array.isArray(data.data) || data.data.length === 0) {
              tableBody.innerHTML = '<tr><td colspan="8">No tickets found</td></tr>';
              return;
          }

          data.data.forEach(ticket => {
              try {
                  const row = document.createElement('tr');
                  row.className = `odd gradeX ${getStatusClass(ticket.status)}`;

                  row.innerHTML = `
<td>
    ${ticket.status.toLowerCase() === "resolved" || ticket.status.toLowerCase() === "returned"
        ? '' // Show nothing if the ticket is resolved or returned
        : ticket.priority === "YES" && (ticket.status.toLowerCase() === "pending" || ticket.status.toLowerCase() === "processing")
        ? '<i class="fa fa-exclamation-circle text-red-500" aria-hidden="true"></i>' 
        : ticket.priority === "NO"
        ? '' 
        : ticket.priority ? escapeHtml(ticket.priority) : '' // Show priority only if it's defined
    }
</td>

                      <td>${(ticket.ticket_id)}</td>
                      <td>${escapeHtml(ticket.status)}</td>
                      <td>${escapeHtml(ticket.employee)}</td>
                      <td>${escapeHtml(ticket.created_date)}</td>
                      <td>${escapeHtml(ticket.full_name)}</td>
                      <td>${escapeHtml(ticket.issue)}</td>
                      <td>
                          <button class="btn btn-primary btn-xs load-details" data-ticket_id="${(ticket.ticket_id)}" data-status="${(ticket.status)}">
                              View Details
                          </button>
                      </td>
                  `;

                  tableBody.appendChild(row);
              } catch (error) {
                  console.error('Error processing ticket:', ticket, error);
              }
          });

          // Reinitialize DataTables
          if ($.fn.DataTable.isDataTable('#ticketTable')) {
              $('#ticketTable').DataTable().destroy();
          }

          $.fn.dataTable.ext.type.order['custom-priority-status'] = function(data, settings, row) {
              var priorityIcon = $(row).find('td:eq(0) i.fa-exclamation-circle').length > 0 ? 1 : 0;
              var status = $(row).find('td:eq(2)').text();
              var statusOrder = $.fn.dataTable.ext.type.order['custom-status-pre'](status);
              return priorityIcon * 10 + statusOrder;
          };

          let table = $('#ticketTable').DataTable({
              "pageLength": getPageLengthPreference(),
              "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
              "order": [[0, 'desc'], [2, 'asc']],
              "columnDefs": [{
                  "orderable": true,
                  "targets": 0,
                  "type": "custom-priority-status",
              }, {
                  "targets": 2,
                  "type": "custom-status-pre",
              }, {
                  "width": "6%",
                  "targets": [0],
                  "className": "text-center"
              }, {
                  "width": "9%",
                  "targets": [2]
              }, {
                  "width": "9%",
                  "targets": [1],
              }, {
                  "width": "10%",
                  "targets": [2],
              }, {
                  "width": "17%",
                  "targets": [3, 5],
              }, {
                  "width": "13%",
                  "targets": [4],
              }, {
                  "width": "15%",
                  "targets": [6],
              }, {
                  "width": "5%",
                  "orderable": false,
                  "targets": [7],
              }],
          });

          // Add event listener for page length change
          table.on('length.dt', function(e, settings, len) {
              savePageLengthPreference(len);
          });
      })
      .catch(error => {
          console.error('Fetch error:', error);
          const tableBody = document.getElementById('ticketTableBody');
          tableBody.innerHTML = `<tr><td colspan="8">Error fetching data: ${error.message}</td></tr>`;
      });
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
    if (typeof unsafe !== 'string') {
        console.warn('Non-string value passed to escapeHtml:', unsafe);
        return '';
    }
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

// Update the table every 30 seconds
setInterval(updateTicketTable, 10000);

// Initial update
document.addEventListener('DOMContentLoaded', updateTicketTable);