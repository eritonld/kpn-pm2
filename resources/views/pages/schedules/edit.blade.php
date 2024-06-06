@extends('layouts_.vertical', ['page_title' => 'Schedule'])

@section('css')
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('schedules') }}">{{ $parentLink }}</a></li>
                        <li class="breadcrumb-item active">{{ $link }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ $link }}</h4>
            </div>
        </div>
    </div>
    <div class="d-sm-flex align-items-center justify-content-center">
        <div class="card col-md-8">
            <div class="card-header d-flex bg-white justify-content-between">
                <h4 class="modal-title" id="viewFormEmployeeLabel">Schedule</h4>
                <a href="{{ route('schedules') }}" type="button" class="btn btn-close"></a>
            </div>
                <div class="card-body" @style('overflow-y: auto;')>
                    <div class="container-fluid">
                        <form id="scheduleForm" method="post" action="{{ route('update-schedule') }}">@csrf
                            <div class="row my-2">
                                <div class="col-md-5">
                                    <div class="mb-2">
                                        <label class="form-label" for="name">Schedule Name</label>
                                        <input type="text" class="form-control bg-light" placeholder="Enter name.." id="name" name="schedule_name" value="{{ $model->schedule_name }}" readonly>
                                        <input type="hidden" class="form-control bg-light" placeholder="Enter name.." id="id_schedule" name="id_schedule" value="{{ $model->id }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-5">
                                    <div class="mb-2">
                                        <label class="form-label" for="type">Event Type</label>
                                            <input type="text" class="form-control bg-light" id="event_type" name="event_type" value="Goals" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-5">
                                    <div class="mb-2">
                                        <label class="form-label" for="type">Employee Type</label>
                                            <input type="text" class="form-control bg-light" id="employee_type" name="employee_type" value="{{ $model->employee_type }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-10">
                                    <div class="mb-2">
                                        <label class="form-label" for="type">Bisnis Unit</label>
                                        <input type="text" class="form-control bg-light" id="bisnis_unit" name="bisnis_unit" value="{{ $model->bisnis_unit }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-10">
                                    <div class="mb-2">
                                        <label class="form-label" for="type">Filter Company:</label>
                                            <input type="text" class="form-control bg-light" id="company_filter" name="company_filter" value="{{ $model->company_filter }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-10">
                                    <div class="mb-2">
                                        <label class="form-label" for="type">Filter Locations:</label>
                                            <input type="text" class="form-control bg-light" id="location_filter" name="location_filter" value="{{ $model->location_filter }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-5">
                                    <div class="mb-2">
                                        <label class="form-label" for="start">Last Join Date</label>
                                        <input type="date" name="last_join_date" class="form-control bg-light" id="start" value="{{ $model->last_join_date }}" placeholder="mm/dd/yyyy">
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-5">
                                    <div class="mb-2">
                                        <label class="form-label" for="start">Start Date</label>
                                        <input type="date" name="start_date" class="form-control bg-light" id="start" value="{{ $model->start_date }}" placeholder="mm/dd/yyyy">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-2">
                                        <label class="form-label" for="end">End Date</label>
                                        <input type="date" name="end_date" class="form-control bg-light" id="end" value="{{ $model->end_date }}" placeholder="mm/dd/yyyy">
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-5">
                                    <div class="mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox_reminder" name="checkbox_reminder" value="1" @if ($model->checkbox_reminder == 1) checked @endif>
                                            <label class="form-label" class="custom-control-label" for="checkbox_reminder">Reminder</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="reminders" @if ($model->checkbox_reminder == 0) hidden @endif>
                                <div class="row my-2">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label class="form-label" for="inputState">Reminder By</label>
                                            <select id="inputState" name="inputState" class="form-select" onchange="toggleDivs()">
                                                <option value="repeaton" @if ($model->inputState == 'repeaton') selected @endif>Repeat On</option>
                                                <option value="beforeenddate" @if ($model->inputState == 'beforeenddate') selected @endif>Before End Date</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="repeaton" style="display: @if ($model->inputState == 'beforeenddate') none @endif">
                                    <div class="row">
                                        <div class="col-12 col-md-auto">
                                            <div class="btn-group-vertical mb-2 d-flex d-md-none" role="group" aria-label="Vertical button group">
                                                @php $repeat_days = $repeat_days = explode(',', $model->repeat_days); @endphp
                                                <button type="button" name="repeat_days[]" value="Mon" class="btn btn-outline-primary btn-sm day-button {{ in_array('Mon', $repeat_days) ? 'active' : '' }}">Monday</button>
                                                <button type="button" name="repeat_days[]" value="Tue" class="btn btn-outline-primary btn-sm day-button {{ in_array('Tue', $repeat_days) ? 'active' : '' }}">Tuesday</button>
                                                <button type="button" name="repeat_days[]" value="Wed" class="btn btn-outline-primary btn-sm day-button {{ in_array('Wed', $repeat_days) ? 'active' : '' }}">Wednesday</button>
                                                <button type="button" name="repeat_days[]" value="Thu" class="btn btn-outline-primary btn-sm day-button {{ in_array('Thu', $repeat_days) ? 'active' : '' }}">Thursday</button>
                                                <button type="button" name="repeat_days[]" value="Fri" class="btn btn-outline-primary btn-sm day-button {{ in_array('Fri', $repeat_days) ? 'active' : '' }}">Friday</button>
                                                <button type="button" name="repeat_days[]" value="Sat" class="btn btn-outline-primary btn-sm day-button {{ in_array('Sat', $repeat_days) ? 'active' : '' }}">Saturday</button>
                                                <button type="button" name="repeat_days[]" value="Sun" class="btn btn-outline-primary btn-sm day-button {{ in_array('Sun', $repeat_days) ? 'active' : '' }}">Sunday</button>
                                            </div>
                                            <div class="btn-group mb-2 d-none d-md-flex" role="group" aria-label="Vertical button group">
                                                <button type="button" name="repeat_days[]" value="Mon" class="btn btn-outline-primary btn-sm day-button {{ in_array('Mon', $repeat_days) ? 'active' : '' }}">Mon</button>
                                                <button type="button" name="repeat_days[]" value="Tue" class="btn btn-outline-primary btn-sm day-button {{ in_array('Tue', $repeat_days) ? 'active' : '' }}">Tue</button>
                                                <button type="button" name="repeat_days[]" value="Wed" class="btn btn-outline-primary btn-sm day-button {{ in_array('Wed', $repeat_days) ? 'active' : '' }}">Wed</button>
                                                <button type="button" name="repeat_days[]" value="Thu" class="btn btn-outline-primary btn-sm day-button {{ in_array('Thu', $repeat_days) ? 'active' : '' }}">Thu</button>
                                                <button type="button" name="repeat_days[]" value="Fri" class="btn btn-outline-primary btn-sm day-button {{ in_array('Fri', $repeat_days) ? 'active' : '' }}">Fri</button>
                                                <button type="button" name="repeat_days[]" value="Sat" class="btn btn-outline-primary btn-sm day-button {{ in_array('Sat', $repeat_days) ? 'active' : '' }}">Sat</button>
                                                <button type="button" name="repeat_days[]" value="Sun" class="btn btn-outline-primary btn-sm day-button {{ in_array('Sun', $repeat_days) ? 'active' : '' }}">Sun</button>
                                            </div>
                                        </div>
                                        <div class="col-md-auto text-end">
                                            <button type="button" class="btn btn-outline-primary btn-sm mb-2" id="select-all">Select All</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="beforeenddate" style="display: @if ($model->inputState == 'repeaton' || $model->checkbox_reminder == 0) none @endif">
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="before_end_date" oninput="validateInput(this)" value="{{ $model->before_end_date }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-4">
                                    <div class="col-md-8">
                                        <div class="mb-2">
                                            <label class="form-label" for="messages">Messages</label>
                                            <textarea name="messages" id="messages" rows="5" class="form-control bg-light" placeholder="Enter message..">{{ $model->messages }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md d-md-flex justify-content-end text-center">
                                    <input type="hidden" name="repeat_days_selected" id="repeatDaysSelected">
                                    <a href="{{ route('schedules') }}" type="button" class="btn btn-danger rounded-pill shadow px-4 me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary rounded-pill shadow px-4">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Tambahkan script JavaScript untuk mengumpulkan nilai repeat_days[] -->
@push('scripts')
<script>
    document.getElementById('scheduleForm').addEventListener('submit', function() {
        var repeatDaysButtons = document.getElementsByName('repeat_days[]');
        var repeatDaysSelected = [];
        repeatDaysButtons.forEach(function(button) {
            if (button.classList.contains('active')) {
                repeatDaysSelected.push(button.value);
            }
        });
        document.getElementById('repeatDaysSelected').value = repeatDaysSelected.join(',');
    });
    function toggleDivs() {
        var selectBox = document.getElementById("inputState");
        var repeatOnDiv = document.getElementById("repeaton");
        var beforeEndDateDiv = document.getElementById("beforeenddate");
        
        if (selectBox.value === "repeaton") {
            repeatOnDiv.style.display = "block";
            beforeEndDateDiv.style.display = "none";
        } else {
            repeatOnDiv.style.display = "none";
            beforeEndDateDiv.style.display = "block";
        }
    }

    function validateInput(input) {
        //input.value = input.value.replace(/[^0-9,]/g, '');
        input.value = input.value.replace(/[^0-9]/g, '');
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush