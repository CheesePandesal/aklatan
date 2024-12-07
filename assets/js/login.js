const logInForm = document.getElementById("logInForm");

logInForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const username = document.querySelector("#username").value.trim();
  const user_password = document.querySelector("#password").value.trim();
  console.log(username);
  console.log(user_password);

  if (username === "" || password === "") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please enter both username and password!",
      confirmButtonColor: "#f65867",
    });
    return;
  }

  // Make a POST request to the PHP code
  fetch("../api/auth.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ username, user_password }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        // Display an error message
        Swal.fire({
          icon: "error",
          title: "Ooops...",
          text: data.error,
          confirmButtonColor: "#f65867",
        });
      } else {
        // Set the user data in the session
        sessionStorage.setItem("username", data.username);
        sessionStorage.setItem("user_role", data.user_role);
        window.location.href = "../index.php";
      }
    })
    .catch((error) => {
      console.error(error);
      // Display an error message
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Error connecting to the server!",
        confirmButtonColor: "#f65867",
      });
    });
});
