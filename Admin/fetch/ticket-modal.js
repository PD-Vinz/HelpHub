$(document).ready(function() {
    // Handle click event for loading details
    $('#ticketTable').on('click', '.load-details', function() {
        var ticketId = $(this).data('ticket_id');
        var status = $(this).data('status');
        var $button = $(this);

        // Show loading indicator
        $button.html('Loading...');

        // Make AJAX request to get ticket details
        $.ajax({
            url: 'get-ticket-details.php',
            method: 'GET',
            data: { ticket_id: ticketId, status: status },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Create or update modal with fetched details
                    var modalId = 'ticketModal' + response.ticket_id;
                    var $modal = $('#' + modalId);
                    
                    if ($modal.length === 0) {
                        // Create new modal if it doesn't exist
                        $('body').append(
                            '<div class="modal fade" id="' + modalId + '" tabindex="-1" role="dialog">' +
                                '<div class="modal-dialog2" role="document">' +
                                    '<div class="modal-content">' +
                                        '<div class="modal-header">' +
                                            '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                            '<h4 class="modal-title">' + response.status + ' Ticket</h4>' +
                                        '</div>' +
                                        '<div class="modal-body">' + response.html + '</div>' +
                                        '<div class="modal-footer">' +
                                            getFooterButtons(response.status, response.ticket_id) +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>'
                        );
                        $modal = $('#' + modalId);
                    } else {
                        // Update existing modal content
                        $modal.find('.modal-title').text(response.status + ' Ticket');
                        $modal.find('.modal-body').html(response.html);
                        $modal.find('.modal-footer').html(getFooterButtons(response.status, response.ticket_id));
                    }

                    // Show the modal
                    $modal.modal('show');
                } else {
                    alert('Error: ' + response.message);
                }

                // Reset button text
                $button.html('View Details');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                console.log('Response Text:', jqXHR.responseText);
                alert('Error loading ticket details. Please check the console for more information.');
                $button.html('View Details');
            }
        });
    });

    function getFooterButtons(status, ticketId) {
        var buttons = '<a href="#" data-dismiss="modal" class="btn">Back</a>';
        
        switch (status) {
            case 'Pending':
                buttons += '<button class="btn btn-primary action-btn" data-action="Open" data-ticket-id="' + ticketId + '">Open Ticket</button>';
                break;
            case 'Processing':
                buttons += '<button class="btn btn-primary action-btn" data-action="Transfer" data-ticket-id="' + ticketId + '">Transfer</button>';
                buttons += '<button class="btn btn-primary action-btn" data-action="Return" data-ticket-id="' + ticketId + '">Return</button>';
                buttons += '<button class="btn btn-primary action-btn" data-action="Close" data-ticket-id="' + ticketId + '">Close</button>';
                break;
            case 'Returned':
            case 'Resolved':
                // Add buttons for these statuses if needed
                break;
        }
        
        return buttons;
    }

    // Event delegation for dynamically created action buttons
    $(document).on('click', '.action-btn', function() {
        var action = $(this).data('action');
        var ticketId = $(this).data('ticket-id');
        createActionModal(action, ticketId);
    });

    function createActionModal(action, ticketId) {
        var modalId = 'myModal' + action + ticketId;
        var modalTitle = action + ' Ticket';
        var modalContent = `
            <div class="modal fade" id="${modalId}">
                <div class="modal-dialog3">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">${modalTitle}</h4>
                        </div>
                        <div class="modal-body">
                            Confirm ${action.toLowerCase()}ing ticket
                        </div>
                        <div class="modal-footer">
                            <a href="#" data-dismiss="modal" class="btn">Cancel</a>
                            <a href="../Admin/${action.toLowerCase()}-form.php?id=${ticketId}&user=${ticket_user}" class="btn btn-primary">Confirm</a>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if it exists
        $('#' + modalId).remove();

        // Append the new modal to the body
        $('body').append(modalContent);

        // Show the modal
        $('#' + modalId).modal('show');
    }

    // Make sure ticket_user is defined globally or pass it as a parameter
    var ticket_user = '<?php echo $ticket_user; ?>';
});