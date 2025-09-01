<?php
require_once __DIR__ . '/../../backend/models/Attendance.php';
require_once __DIR__ . '/../../backend/models/Department.php';
require_once __DIR__ . '/../../backend/models/FieldWorker.php';
require_once __DIR__ . '/../../backend/lib/tcpdf/tcpdf.php';


$start = $_GET['start_date'] ?? date('Y-m-d');
$end = $_GET['end_date'] ?? date('Y-m-d');
$department = $_GET['department'] ?? 'all';
$group = $_GET['group'] ?? 'all';

// If department is an ID, convert to department name for Attendance::getAll
if ($department !== 'all' && is_numeric($department)) {
	$deptObj = Department::getById($department);
	$department = $deptObj ? $deptObj['name'] : 'all';
}


// Fetch department name for heading
$deptName = 'All';
if ($department !== 'all') {
	$deptName = $department;
}


// Fetch attendance data with department filter (by name), no search, no date
$records = Attendance::getAll('', $department, null);
$filtered = [];
foreach ($records as $row) {
	$date = $row['date'];
	if ($date < $start || $date > $end) continue;
	// If group filter is set, check group_name (if available)
	if ($group !== 'all') {
		// Try to get group_name from field_worker_profiles
		$userId = $row['user_id'] ?? null;
		$groupName = null;
		if ($userId) {
			$db = Database::getConnection();
			$stmt = $db->prepare("SELECT group_name FROM field_worker_profiles WHERE user_id = ?");
			$stmt->execute([$userId]);
			$groupName = $stmt->fetchColumn();
		}
		if ($groupName !== $group) continue;
	}
	$filtered[] = $row;
}

// PDF generation
$pdf = new \TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$heading = "Attendance Report\nDate Range: $start to $end\nDepartment: $deptName\nGroup: " . ($group === 'all' ? 'All' : $group);
$pdf->Write(0, $heading, '', 0, 'L', true, 0, false, false, 0);

$tbl = '<table border="1" cellpadding="4"><thead><tr><th>Full Name</th><th>Date</th><th>Status</th><th>Total Hours</th></tr></thead><tbody>';
foreach ($filtered as $row) {
	$tbl .= '<tr>';
	$tbl .= '<td>' . htmlspecialchars($row['full_name']) . '</td>';
	$tbl .= '<td>' . htmlspecialchars($row['date']) . '</td>';
	$tbl .= '<td>' . htmlspecialchars($row['status']) . '</td>';
	$tbl .= '<td>' . htmlspecialchars($row['total_hours'] ?? '-') . '</td>';
	$tbl .= '</tr>';
}
$tbl .= '</tbody></table>';
$pdf->writeHTML($tbl, true, false, false, false, '');

$filename = 'attendance_report';
$filename .= '_'.str_replace('-', '', $start).'-'.str_replace('-', '', $end);
if ($department !== 'all') {
	$filename .= '_'.preg_replace('/[^a-zA-Z0-9]/', '', strtolower($department));
}
if ($group !== 'all') {
	$filename .= '_group'.strtoupper($group);
}
$filename .= '.pdf';
$pdf->Output($filename, 'D');
exit;

?>
