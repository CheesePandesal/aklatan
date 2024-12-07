window.onload = () => displayUsers();

let idToEdit = null;
const closeBtn = document.getElementById("close-btn");
const userForm = document.getElementById("userForm");
const usernameInput = document.getElementById("usernameInput");
const passwordInput = document.getElementById("passwordInput");
const emailInput = document.getElementById("emailInput");
const selectedUserRole = document.getElementById("selectedUserRole");
// Use the hideModal function
// Use the hideModal function and add custom code
closeBtn.addEventListener("click", () => {
  resetForm();
});
async function getUsers(id, searchQuery) {
  //check id if defined
  if (id) {
    endpoint = `../../api/user.php?userId=${id}`;
  } else if (searchQuery) {
    endpoint = `../../api/user.php?searchQuery=${searchQuery}`;
  } else {
    endpoint = "../../api/user.php";
  }
  //return fetch
  return fetch(endpoint).then((response) => {
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    return response.json();
  });
}
async function displayUsers(searchQuery) {
  const users = await getUsers(null, searchQuery);
  const userDisplay = document.getElementById("user-record");
  userDisplay.innerHTML = "";

  users.forEach((user) => {
    const userHTML = `
      <tr class="user-row" id="${user.user_id}">
        <td class="user-id">${user.user_id}</td>
        <td class="username">${user.username}</td>
        <td class="user-email">${user.user_email}</td>
        <td class="user-role" style="color: ${
          user.user_role === "Librarian" ? "#4aa86f" : "#f65867"
        };">${user.user_role}</td>
        <td class="user-actions">
          <button class="edit-btn" onclick="editUser(${user.user_id})">
            Edit
          </button>
          <button class="delete-btn" onclick="deleteUser(${user.user_id})">
            Delete
          </button>
        </td>
      </tr>
    `;
    userDisplay.innerHTML += userHTML;
  });
}
/////////////////////////////////////////////////////// PUT ////////////////////////////////////////////////
//function to set edit Books, with id
async function editUser(userId) {
  //assign id to global id variable
  idToEdit = userId;
  //get record using id

  const user = await getUsers(userId, null);
  modalContainer.style.display = "flex";

  // const book = await getUsers(bookId);
  //populate the form using response/record
  document.getElementById("modal-title").innerHTML = "Update User";
  usernameInput.value = user[0].username;
  passwordInput.value = user[0].user_password;
  emailInput.value = user[0].user_email;
  selectedUserRole.value = user[0].user_role;
  //let's change the button to update, to make it dynamic
  document.getElementById("btn").innerHTML = `
    <input type="button" onclick="resetForm()" class="cancel-btn" value="Cancel" style="width: 50%;">
    <input type="submit" value="Update Book" style="width: 50%;">
`;
}

function resetForm() {
  idToEdit = null;
  modalContainer.style.display = "none";
  userForm.reset();
  document.getElementById("modal-title").innerHTML = "Add New User";
  document.getElementById("btn").innerHTML = `
    <input type="submit" value="Add User">
`;
}
userForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const username = usernameInput.value.trim();
  const password = passwordInput.value.trim();
  const email = emailInput.value.trim();
  const userRole = selectedUserRole.value;

  if (!username || !password || !email || !userRole) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please fill out all fields!",
      confirmButtonColor: "#f65867",
    });
    return;
  }

  //prepare data to submit
  const dataToSubmit = {
    username: username,
    user_password: password,
    user_email: email,
    user_role: userRole,
  };
  console.log(dataToSubmit);
  //send prepared data to the server using this functions
  //if idToEdit was assigned, update record, if null, add record
  if (idToEdit) {
    updateUser(dataToSubmit);
  } else {
    insertData(dataToSubmit);
  }
});

async function updateUser(dataToSubmit) {

  Swal.fire({
    title: "Update User?",
    text: "Are you sure you want to update this user?",
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#f65867",
    confirmButtonText: "Yes, update user!",
    cancelButtonColor: "#5d5e66",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`../../api/user.php?userId=${idToEdit}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dataToSubmit),
      })
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          if (data.success) {
            Swal.fire({
              icon: "success",
              title: "User Updated!",
              text: "User has been updated successfully!.",
              confirmButtonColor: "#34C759",
            });
            // You can also reset the form here if you want
            displayUpdatedData(data.data);
            resetForm();
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",       
              text: "Failed to update user. Please try again.",
              confirmButtonColor: "#f65867",
            });
          }
        })
        .catch((error) => console.error("Error:", error));
      }
    });
  
}
function insertData(dataToSubmit) {
  fetch("../../api/check-username.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ username: dataToSubmit.username }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.exists) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Username already exists!",
          confirmButtonColor: "#f65867",
        });
        return;
      }

      Swal.fire({
        title: "Add User?",
        text: "Are you sure you want to add this user?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#f65867",
        confirmButtonText: "Yes",
        cancelButtonColor: "#5d5e66",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          fetch("../../api/user.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(dataToSubmit),
          })
            .then((response) => response.json())
            .then((data) => {
              console.log(data);
              if (data.success) {
                Swal.fire({
                  icon: "success",
                  title: "User Added!",
                  text: "User has been successfully added to the database.",
                  confirmButtonColor: "#34C759",
                });
                // You can also reset the form here if you want
                displayNewData(data.data);
                resetForm();
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Failed to add user. Please try again.",
                  confirmButtonColor: "#f65867",
                });
              }
            })
            .catch((error) => console.error("Error:", error));
        }
      });
    })
    .catch((error) => console.error("Error:", error));
}
function displayUpdatedData(user) {
  //find tr with id selected id
  //then change its content to updated data
  const userDisplay = document.getElementById(`${idToEdit}`);
  userDisplay.innerHTML = `
      <td class="user-id">${user.user_id}</td>
      
                                <td class="username">
                                ${user.username}
                                </td>
                                <td class="user-email">
                                ${user.user_email}
                                </td>
                                
                                <td class="user-role" style="color: ${
                                  user.user_role === "Librarian"
                                    ? "#4aa86f"
                                    : "#f65867"
                                };">${user.user_role}</td>
                                <td class="user-actions">
                                    <button class="edit-btn" onclick="editUser({
                                      user.user_id
                                    })">
                                        Edit
                                    </button>
                                    <button class="delete-btn" onclick="deleteUser({
                                      user.user_id
                                    })">
                                        Delete
                                    </button>

                                </td>`;
  //clear form and set button to save not update
  resetForm();
}
function displayNewData(user) {
  const userDisplay = document.getElementById("user-record");
  let row = `
          <tr class="user-row" id="${user.user_id}">
                                <td class="user-id">${user.user_id}</td>
                                <td class="username">
                                ${user.username}
                                </td>
                                <td class="user-email">
                                ${user.user_email}
                                </td>
                                <td class="user-status" style="color: ${
                                  user.user_role === "Librarian"
                                    ? "#4aa86f"
                                    : "#f65867"
                                };">${user.user_role}</td>

                                <td class="user-actions">
                                    <button class="edit-btn" onclick="editUser({
                                      user.user_id
                                    })">
                                        Edit
                                    </button>
                                    <button class="delete-btn" onclick="deleteUser({
                                      user.user_id
                                    })">
                                        Delete
                                    </button>

                                </td>
                            </tr>
      `;
  userDisplay.innerHTML += row;
}

/////////////////////////////////////////////////////// DELETE ////////////////////////////////////////////////

//function to delete Books, with id or nah
async function deleteUser(userId) {
  // check first if the selected user is admin or not, if the user is admin, it will not be deleted and an error message will show
  const user = await getUsers(userId, null);

  if (user[0].user_role === "Admin") {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "You cannot delete an admin user.",
      confirmButtonColor: "#f65867",
    });
    return;
  }
  //confirmation message
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#f65867",
    cancelButtonColor: "#5d5e66",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      //check id, it should not be null/empty/0
      if (!userId || userId == "" || userId == 0) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Please select a user to delete",
          confirmButtonColor: "#f65867",
        });
        return;
      }
      //then delete
      fetch(`../../api/user.php?userId=${userId}`, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json();
        })
        .then((data) => {
          console.log(data);
          if (data.success) {
            Swal.fire({
              icon: "success",
              title: "User Deleted!",
              text: "User has been successfully deleted to the database.",
              confirmButtonColor: "#34C759",
            });
          }

          document.getElementById(`${userId}`).remove();
        })
        .catch((error) => console.error("Error:", error));
    }
  });
}

let searchTimeout = null;
document.getElementById("search-input").addEventListener("input", () => {
  const searchQuery = document.getElementById("search-input").value;
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    displayUsers(searchQuery);
  }, 500);
});
