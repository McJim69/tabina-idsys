<script>
	function deleteRecord(table, idValue, trId){   
		swal({
			title: "Confirm Delete",
			text: "This record will be permanently removed.",
			icon: "warning",
			buttons: {
				cancel: {
					text: "Cancel",
					visible: true,
					className: "btn btn-secondary"
				},
				confirm: {
					text: "Delete",
					visible: true,
					className: "btn btn-danger"
				}
			},
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
				swal({
					title: "Deleting...",
					text: "Please wait while we remove the record.",
					icon: "info",
					buttons: false,
					closeOnClickOutside: false,
					closeOnEsc: false
				});

				var xhr = new XMLHttpRequest();
				xhr.onreadystatechange = function(){
					if (xhr.readyState === 4){
						if(xhr.status === 200 && xhr.responseText.trim() === "Success"){
							swal("Deleted!", "The record has been removed.", "success")
							.then(() => {
								if(trId){
									document.getElementById(trId).style.transition = "opacity 0.5s";
									document.getElementById(trId).style.opacity = 0;
									setTimeout(function(){
										document.getElementById(trId).remove();
									}, 500);
								}
							});
						} else {
							swal("Error", xhr.responseText, "error");
						}
					}
				};
				xhr.open("POST","crud_functions.php",true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.send("table="+encodeURIComponent(table)+"&id="+encodeURIComponent(idValue));
			}
		});
	}
</script>