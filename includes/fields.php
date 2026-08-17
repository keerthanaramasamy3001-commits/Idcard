<?php
/**
 * Field definitions per module — drives form rendering, table columns, and ID card data.
 * type: text | number | date | select | tel | email | textarea | file
 */

function get_fields($moduleKey) {
    $fields = [
        'school' => [
            ['name' => 'name', 'label' => 'Student Name', 'type' => 'text', 'required' => true],
            ['name' => 'admission_number', 'label' => 'Admission Number', 'type' => 'text', 'required' => true],
            ['name' => 'class', 'label' => 'Class', 'type' => 'text'],
            ['name' => 'section', 'label' => 'Section', 'type' => 'text'],
            ['name' => 'roll_number', 'label' => 'Roll Number', 'type' => 'text'],
            ['name' => 'dob', 'label' => 'Date of Birth', 'type' => 'date'],
            ['name' => 'gender', 'label' => 'Gender', 'type' => 'select', 'options' => ['Male', 'Female', 'Other']],
            ['name' => 'blood_group', 'label' => 'Blood Group', 'type' => 'select', 'options' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']],
            ['name' => 'parent_name', 'label' => 'Parent Name', 'type' => 'text'],
            ['name' => 'phone', 'label' => 'Phone Number', 'type' => 'tel'],
            ['name' => 'address', 'label' => 'Address', 'type' => 'textarea'],
            ['name' => 'photo', 'label' => 'Photo', 'type' => 'file'],
            ['name' => 'principal_name', 'label' => 'Principal Name', 'type' => 'text'],
            ['name' => 'school_name', 'label' => 'School Name', 'type' => 'text'],
            ['name' => 'issue_date', 'label' => 'Issue Year', 'type' => 'number', 'placeholder' => 'e.g. 2026'],
            ['name' => 'expiry_date', 'label' => 'Expiry Year', 'type' => 'number', 'placeholder' => 'e.g. 2027'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['Active', 'Inactive', 'Expired']],
        ],
        'college' => [
            ['name' => 'name', 'label' => 'Student Name', 'type' => 'text', 'required' => true],
            ['name' => 'register_number', 'label' => 'Register Number', 'type' => 'text', 'required' => true],
            ['name' => 'department', 'label' => 'Department', 'type' => 'text'],
            ['name' => 'year', 'label' => 'Year', 'type' => 'text'],
            ['name' => 'semester', 'label' => 'Semester', 'type' => 'text'],
            ['name' => 'blood_group', 'label' => 'Blood Group', 'type' => 'select', 'options' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']],
            ['name' => 'dob', 'label' => 'Date of Birth', 'type' => 'date'],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
            ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel'],
            ['name' => 'address', 'label' => 'Address', 'type' => 'textarea'],
            ['name' => 'photo', 'label' => 'Photo', 'type' => 'file'],
            ['name' => 'college_name', 'label' => 'College Name', 'type' => 'text'],
            ['name' => 'principal', 'label' => 'Principal', 'type' => 'text'],
            ['name' => 'issue_date', 'label' => 'Issue Year', 'type' => 'number', 'placeholder' => 'e.g. 2026'],
            ['name' => 'expiry_date', 'label' => 'Expiry Year', 'type' => 'number', 'placeholder' => 'e.g. 2027'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['Active', 'Inactive', 'Expired']],
        ],
        'office' => [
            ['name' => 'name', 'label' => 'Employee Name', 'type' => 'text', 'required' => true],
            ['name' => 'employee_id', 'label' => 'Employee ID', 'type' => 'text', 'required' => true],
            ['name' => 'department', 'label' => 'Department', 'type' => 'text'],
            ['name' => 'designation', 'label' => 'Designation', 'type' => 'text'],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
            ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel'],
            ['name' => 'joining_date', 'label' => 'Joining Date', 'type' => 'date'],
            ['name' => 'blood_group', 'label' => 'Blood Group', 'type' => 'select', 'options' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']],
            ['name' => 'address', 'label' => 'Address', 'type' => 'textarea'],
            ['name' => 'photo', 'label' => 'Photo', 'type' => 'file'],
            ['name' => 'manager_name', 'label' => 'Manager Name', 'type' => 'text'],
            ['name' => 'office_name', 'label' => 'Office Name', 'type' => 'text'],
            ['name' => 'issue_date', 'label' => 'Issue Year', 'type' => 'number', 'placeholder' => 'e.g. 2026'],
            ['name' => 'expiry_date', 'label' => 'Expiry Year', 'type' => 'number', 'placeholder' => 'e.g. 2027'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['Active', 'Inactive', 'Expired']],
        ],
        'hospital' => [
            ['name' => 'name', 'label' => 'Patient Name', 'type' => 'text', 'required' => true],
            ['name' => 'patient_id_number', 'label' => 'Patient ID', 'type' => 'text', 'required' => true],
            ['name' => 'doctor_name', 'label' => 'Doctor Name', 'type' => 'text'],
            ['name' => 'blood_group', 'label' => 'Blood Group', 'type' => 'select', 'options' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']],
            ['name' => 'dob', 'label' => 'Date of Birth', 'type' => 'date'],
            ['name' => 'gender', 'label' => 'Gender', 'type' => 'select', 'options' => ['Male', 'Female', 'Other']],
            ['name' => 'emergency_contact', 'label' => 'Emergency Contact', 'type' => 'tel'],
            ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel'],
            ['name' => 'address', 'label' => 'Address', 'type' => 'textarea'],
            ['name' => 'photo', 'label' => 'Photo', 'type' => 'file'],
            ['name' => 'hospital_name', 'label' => 'Hospital Name', 'type' => 'text'],
            ['name' => 'issue_date', 'label' => 'Issue Year', 'type' => 'number', 'placeholder' => 'e.g. 2026'],
            ['name' => 'expiry_date', 'label' => 'Expiry Year', 'type' => 'number', 'placeholder' => 'e.g. 2027'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['Active', 'Inactive', 'Expired']],
        ],
    ];
    return $fields[$moduleKey] ?? [];
}

/** Which fields show as columns in the table view (subset, keep it readable) */
function get_table_columns($moduleKey) {
    $cols = [
        'school'   => ['photo', 'id', 'name', 'class', 'section', 'roll_number', 'status'],
        'college'  => ['photo', 'id', 'name', 'department', 'year', 'status'],
        'office'   => ['photo', 'id', 'name', 'department', 'designation', 'status'],
        'hospital' => ['photo', 'id', 'name', 'doctor_name', 'blood_group', 'status'],
    ];
    return $cols[$moduleKey] ?? ['id', 'name', 'status'];
}
