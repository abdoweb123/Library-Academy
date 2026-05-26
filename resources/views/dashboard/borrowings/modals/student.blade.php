<div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog" style="min-width: 40vw;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ trns('add_student') }}</h5>
            </div>

            <div class="modal-body">
                <input type="text" id="student_name" class="form-control mb-2" placeholder="{{ trns('name') }}">
                <input type="email" id="student_email" class="form-control mb-2" placeholder="{{ trns('email') }}">
                <input type="text" id="student_phone" class="form-control mb-2" placeholder="{{ trns('phone') }}">
                <input type="password" id="student_password" class="form-control" placeholder="{{ trns('password') }}">
                <select name="course_id" id="student_course_id" class="form-control course-select">
                    @if(old('course_id'))
                        <option value="{{ old('course_id') }}" selected>{{ old('course_id') }}</option>
                    @endif
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveStudent()">
                    {{ trns('save') }}
                </button>
            </div>

        </div>
    </div>
</div>
