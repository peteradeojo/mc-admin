function activateStaff(username) {
	// TODO: activate staff async
	console.log(username);
}

function deactivateStaff(username) {
	// TODO: deactivate staff async
	console.log(username);
}
$(() => {
	function format(d) {
		// `d` is the original data object for the row
		return `<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">
			<tr>
				<td>Username:</td>
				<td>${d.username}</td>
				<td>Phone Number:</td>
				<td>${d.phone_number}</td>
			</tr>
			<tr>
				<td>
				${
					d.active === '0'
						? `<button class='btn' onclick="activateStaff('${d.username}')">Activate</a>`
						: `<button class='btn btn-danger' onclick="deactivateStaff('${d.username}')">Deactivate</a>`
				}
				</td>
				<td>
					<a href="/ict/editstaff.php?user=${d.username}" class="btn btn-primary">Edit</a>
				</td>
			</tr>
			</table>`;
	}

	const table = $('#staff-table').DataTable({
		ajax: { url: '/ict/getstaff.php', dataSrc: '' },
		columns: [
			{
				className: 'details-control',
				orderable: false,
				data: null,
				defaultContent: '',
			},
			{ data: 'name' },
			{ data: 'designation' },
			{ data: 'department' },
		],
	});

	$('#staff-table tbody').on('click', 'td.details-control', function () {
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
