$(() => {
	function format(d) {
		return `<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">
			<tr>
				<td><b>Phone Number</b></td>
				<td>${d.phone_number}</td>
				<td><b>Gender</b></td>
				<td>${d.gender}</td>
				<td><b>Date of Birth</b></td>
				<td>${d.birthdate}</td>
			</tr>
			<tr>
				<td><b>Marital Status</b></td>
				<td>${d.marital_status}</td>
				<td><b>Occupation</b></td>
				<td>${d.occupation}</td>
				<td><b>Religion</b></td>
				<td>${d.religion}</td>
			</tr>
			<tr>
				<td><a href='/ict/advanced_patient_edit.php?id=${d.hospital_number}'>Advanced Edit</a></td>
			</tr>
		</table>`;
	}

	const table = $('#patients-table').DataTable({
		ajax: {
			url: '/ict/getpatients.php',
			dataSrc: '',
		},
		columns: [
			{ data: null, defaultContent: '', orderable: false, searchable: false, className: 'details-control' },
			{ data: 'name' },
			{ data: 'hospital_number' },
			{ data: 'category' },
		],
	});

	$('#patients-table tbody').on('click', 'td.details-control', function () {
		const tr = $(this).closest('tr');
		const row = table.row(tr);

		if (row.child.isShown()) {
			// This row is already open - close it
			row.child.hide();
			tr.removeClass('shown');
		} else {
			// Open this row
			row.child(format(row.data())).show();
			tr.addClass('shown');
		}
	});
});
