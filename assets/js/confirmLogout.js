function confirmLogout() {
  Swal.fire({
    title: "Logout?",
    text: "Are you sure you want to logout?",
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#f65867",
    confirmButtonText: "Yes!",
    cancelButtonColor: "#5d5e66",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "../../api/logout.php";
    }
  });
}
