<div class="row">
    <div class="col-md-12">
      <div class="card shadow mb-4">
        <div class="card-header">
            <div class="row bg-primary-subtle rounded p-2">
              <div class="col-md-auto text-center">
                  <button class="btn btn-outline-primary rounded-pill btn-sm px-2 my-1 me-2 filter-btn" data-id="all">All Task</button>
                  <button class="btn btn-outline-primary rounded-pill btn-sm px-2 my-1 me-2 filter-btn" data-id="draft">Draft</button>
                  <button class="btn btn-outline-primary rounded-pill btn-sm px-2 my-1 me-2 filter-btn" data-id="waiting for revision">Waiting For Revision</button>
                  <button class="btn btn-outline-primary rounded-pill btn-sm px-2 my-1 me-2 filter-btn" data-id="waiting for approval">Waiting For Approval</button>
                  <button class="btn btn-outline-primary rounded-pill btn-sm px-2 my-1 me-2 filter-btn" data-id="approved">Approved</button>
              </div>
            </div>
          </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="adminReportTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>Employees</th>
                            <th>KPI</th>
                            <th>Goal Status</th>
                            <th>Approval Status</th>
                            <th>Initiated On</th>
                            <th>Initiated By</th>
                            <th>Last Updated On</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($data as $row)
                        <tr>
                          <td>{{ $row->employee->fullname }}<br>{{ $row->employee_id }}</td>
                          <td class="text-center">
                            <a href="javascript:void(0)" class="btn btn-light btn-sm rounded-pill font-weight-medium" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $row->goal->id }}"><i class="ri-search-line"></i></a>
                          </td>
                          <td class="text-center">
                            <span class="badge {{ $row->goal->form_status == 'Approved' ? 'bg-success' : ($row->goal->form_status == 'Draft' ? 'badge-outline-secondary' : 'bg-secondary')}} rounded-pill px-1">{{ $row->goal->form_status == 'Draft' ? 'Not Submitted' : $row->goal->form_status }}</span></td>
                          <td class="text-center">
                            <a href="javascript:void(0)" data-bs-id="{{ $row->employee_id }}" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ $row->goal->form_status=='Draft' ? 'Draft' : ($row->approvalLayer ? 'Manager L'.$row->approvalLayer.' : '.$row->name : $row->name) }}" class="badge {{ $row->status === 'Approved' ? 'bg-success' : ( $row->status=='Sendback' || $row->goal->form_status=='Draft' ? 'bg-secondary' : 'bg-warning' ) }} rounded-pil px-1">{{ $row->status == 'Pending' ? ($row->goal->form_status=='Draft' ? 'Draft' : 'Waiting For Approval') : ( $row->status=='Sendback'? 'Waiting For Revision' : $row->status) }}</a>
                          </td>
                          <td class="text-center">{{ $row->formatted_created_at }}</td>
                          <td>{{ $row->initiated->name }}<br>{{ $row->initiated->employee_id }}</td>
                          <td class="text-center">{{ $row->formatted_updated_at }}</td>
                          <div class="modal fade" id="modalDetail{{ $row->goal->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl mt-2" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="viewFormEmployeeLabel">Goal's</h4>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                          </button>
                                      <div class="input-group-md">
                                          <input type="text" id="employee_name" class="form-control" placeholder="Search employee.." hidden>
                                      </div>
                                </div>
                                <div class="modal-body bg-secondary-subtle">
                                  <div class="container-fluid py-3">
                                      <form action="" method="post">
                                        <div class="row">
                                            <div class="col">
                                                <div class="d-sm-flex align-items-center mb-3">
                                                      <h4 class="me-2">{{ $row->employee->fullname }}</h4><span class="text-muted h4">{{ $row->employee->employee_id }}</span>
                                                </div>
                                            </div>
                                        </div>
                                          <!-- Content Row -->
                                          <div class="container-card">
                                            @php
                                                $formData = json_decode($row->goal['form_data'], true);
                                            @endphp
                                            @if ($formData)
                                            @foreach ($formData as $index => $data)
                                                <div class="card mb-4 shadow">
                                                    <div class="card-header pb-0 border-0 bg-white">
                                                        <h4>KPI {{ $index + 1 }}</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="kpi">KPI</label>
                                                                    <textarea class="form-control bg-gray-100" disabled>{{ $data['kpi'] }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="target">Target</label>
                                                                    <input type="text" value="{{ $data['target'] }}" class="form-control bg-gray-100" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="uom">UoM</label>
                                                                    <input type="text" value="{{ $data['uom'] }}" class="form-control bg-gray-100" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="weightage">Weightage</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control bg-gray-100" value="{{ $data['weightage'] }}" disabled>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">%</span>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Tambahkan kode untuk menampilkan error weightage jika ada -->
                                                                    @if ($errors->has("weightage"))
                                                                        <span class="text-danger">{{ $errors->first("weightage") }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="type">Type</label>
                                                                    <input type="text" value="{{ $data['type'] }}" class="form-control bg-gray-100" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @else
                                                <p>No form data available.</p>
                                            @endif                
                                </div>
                                      </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
</div>