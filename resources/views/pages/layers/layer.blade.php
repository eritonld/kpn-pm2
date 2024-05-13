<x-app-layout>
    @section('title', 'Layer')
    <x-slot name="content">
    <!-- Begin Page Content -->
    <div class="container-fluid">       
        
        <!-- Content Row -->
        <div class="row">
          <div class="col-md-12">

            <div class="card shadow mb-4">
              <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-hover" id="taskTable" width="100%" cellspacing="0">
                          <thead class="thead-light">
                              <tr class="text-center">
                                <th>#</th>
                                <th>NIK</th>
                                <th>Name</th>
                                <th>PT</th>
                                <th>BU</th>
                                <th>Superior</th>                                 
                                <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
                            @php 
                                $no=1;
                            @endphp
                            @foreach($approvalLayers as $approvalLayer)
                                <tr>
                                    <td>
                                        {{ $no++ }}
                                    </td>
                                    <td>{{ $approvalLayer->employee_id }}</td>
                                    <td>{{ $approvalLayer->fullname }}</td>
                                    <td>{{ $approvalLayer->contribution_level_code }}</td>
                                    <td>{{ $approvalLayer->group_company }}</td>
                                    <td>
                                        @php
                                            $layersArray = explode('|', $approvalLayer->layers);
                                            $approverNamesArray = explode('|', $approvalLayer->approver_names);
                                        @endphp
                                        @foreach($layersArray as $index => $layer)
                                            {{ "L".$layer }} : {{ $approverNamesArray[$index] }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-circle btn-outline-primary open-edit-modal"
                                        data-employee-id="{{ $approvalLayer->employee_id }}"
                                        data-fullname="{{ $approvalLayer->fullname }}"
                                        data-app="{{ $approvalLayer->approver_ids }}"
                                        data-layer="{{ $approvalLayer->layers }}"
                                        data-app-name="{{ $approvalLayer->approver_names }}"
                                        title="Edit"><i class="fas fa-edit"></i></button>
                                    </td>
                              </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
              </div>
            </div>
          </div>
      </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for editing employee details -->
                <form id="editForm" action="{{ route('update-layer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="employee_id" id="employee_id">
                    <div class="form-group">
                        <label for="employee_id">Employee ID:</label>
                        <input type="text" class="form-control" id="employeeId" name="employee_id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="fullname">Full Name:</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" readonly>
                    </div>
                    <hr>
                    <div class="input-group margin" id="viewlayer">
                        
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

</x-slot>
</x-app-layout>
<script>
    $(document).ready(function() {
        $('.open-edit-modal').on('click', function() {
            var employeeId = $(this).data('employee-id');
            $('#employeeId').text(employeeId);
            $('#editModal').modal('show');
        });
    });
</script>
<script>
    // Periksa apakah ada pesan sukses
    var successMessage = "{{ session('success') }}";

    // Jika ada pesan sukses, tampilkan sebagai alert
    if (successMessage) {
        alert(successMessage);
    }
</script>
<script>
        function handleDelete(element) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                var scheduleId = element.getAttribute('data-id');

                fetch('/schedule/' + scheduleId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Terjadi kesalahan saat menghapus data.');
                    }
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data.');
                });
            }
        }
    
</script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#taskTable').DataTable();

    // Apply filter when location dropdown value changes
    $('#locationFilter').on('change', function() {
        applyLocationFilter(table);
    });

    // Apply filter when table is redrawn (e.g., when navigating to next page)
    table.on('draw.dt', function() {
        applyLocationFilter(table);
    });
});

function applyLocationFilter(table) {
    var locationId = $('#locationFilter').val().toUpperCase();

    // Filter table based on location
    table.column(10).search(locationId).draw(); // Adjust index based on your table structure
}
</script>
<script>
    $(document).ready(function() {
        $('.open-edit-modal').on('click', function() {
            var employeeId = $(this).data('employee-id');
            var fullname = $(this).data('fullname');
            var app = $(this).data('app');
            var layer = $(this).data('layer');
            var appname = $(this).data('app-name');

            // populateModal(employeeId, fullname, app, layer, appname);
            populateModal(employeeId, fullname, app, layer, appname, {!! json_encode($employees) !!});
        });
    });

    function populateModal(employeeId, fullName, app, layer, appName, employees) {
        $('#employee_id').val(employeeId);
        $('#employeeId').val(employeeId);
        $('#fullname').val(fullName);

        var apps = app.split('|');
        var layers = layer.split('|');
        var appNames = appName.split('|');

        $('#viewlayer').empty();

        for (var i = 0; i < apps.length; i++) {
            var selectOptions = '';
            for (var j = 0; j < employees.length; j++) {
                var selected = (employees[j].employee_id == apps[i]) ? 'selected' : '';
                selectOptions += '<option value="' + employees[j].employee_id + '" ' + selected + '>' + employees[j].fullname + '</option>';
            }

            $('#viewlayer').append('<div class="input-group margin"><div class="input-group-btn"><button type="button" class="btn btn-info">Layer ' + layers[i] + '</button></div><select name="nik_app[]" class="form-control">' + selectOptions + '</select></div>');
        }

        $('#editModal').modal('show');
    }

    $(document).ready(function() {
    // AJAX request to update layer
 
    $('#editForm').submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        var nik_app = [];
        $('select[name="nik_app[]"]').each(function() {
            nik_app.push($(this).val());
        });

        $.ajax({
            type: 'POST',
            url: '{{ route("update-layer") }}',
            data: {
                employee_id: $('#employee_id').val(),
                nik_app: nik_app
            },
            success: function(response) {
                // Handle success response
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                alert('Terjadi kesalahan saat memperbarui data.');
            }
        });
    });
});
</script>