async function checkPatientIn(id) {
	// console.log(id);
	try {
		const res = await fetch('/rec/addtowaitlist.php', {
			method: 'POST',
			body: JSON.stringify({ patientid: id }),
		});
		const data = await res.json();
		if (data.ok) {
			alert(`Patient ${id} has been checked in`);
		} else {
			alert(data.message);
		}
	} catch (error) {
		console.error(error);
	}
}

$(() => {
	function format(d) {
		// `d` is the original data object for the row
		return `<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">
				<tr>
					<td><b>Phone:</b></td>
					<td>${d.phone_number}</td>
					<td><b>Religion:</b></td>
					<td>${d.religion}</td>
					<td><b>Date of Birth:</b></td>
					<td>${d.birthdate}</td>
				</tr>
				<tr>
					<td><b>Occupation:</b></td>
					<td>${d.occupation}</td>
					<td><b>State:</b></td>
					<td>${d.state_of_origin}</td>
					<td><b>Tribe:</b></td>
					<td>${d.tribe}</td>
				</tr>
				<tr>
					<td>
						<button class='btn btn-primary' onclick="checkPatientIn('${d.hospital_number}')">Check In</button>					
						<a class='btn btn-danger' href='/rec/patientedit.php?id=${d.hospital_number}'>Edit Patient</a>
					</td>
				</tr>
			</table>`;
	}

	const table = $('#patients-table').DataTable({
		ajax: { url: '/rec/getpatients.php', dataSrc: '' },
		columns: [
			{
				className: 'details-control',
				orderable: false,
				data: null,
				defaultContent: '',
			},
			{ data: 'hospital_number' },
			{ data: 'name' },
			{ data: 'category' },
			{ data: 'gender' },
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
