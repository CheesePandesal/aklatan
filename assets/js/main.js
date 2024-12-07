const toggleButton = document.getElementById("toggle-btn");
const sidebar = document.getElementById("sidebar");
const inputs = document.querySelectorAll(".input-field");
const toggle_btn = document.querySelectorAll(".toggle");
const main = document.querySelector("main");
const bullets = document.querySelectorAll(".bullets span");
const images = document.querySelectorAll(".image");
const modalContainer = document.getElementById("modal-container");
const modalContent = document.getElementById("modal-content");
const addBtn = document.getElementById("add-btn");
// or via CommonJS

// sample data for the table

// // pagination variables
// const rowsPerPage = 10;
// const currentPage = 1;
// // function to render the table data
// function renderTableData() {

//   const tableBody = document.getElementById('book-record');
//   tableBody.innerHTML = '';

//   const startIndex = (currentPage - 1) * rowsPerPage;
//   const endIndex = startIndex + rowsPerPage;

//   for (let i = startIndex; i < endIndex; i++) {
//     const row = tableData[i];
//     const tableRow = document.createElement('tr');
//     console.log(tableRow)
//     tableRow.innerHTML = `
//       <td>${row.title}</td>
//       <td>${row.year}</td>
//       <td>${row.action}</td>
//     `;
//     tableBody.appendChild(tableRow);
//   }
// }
// // function to render the pagination links
// function renderPaginationLinks() {
//   const pagination = document.getElementById('pagination');
//   pagination.innerHTML = '';

//   const totalPages = Math.ceil(tableData.length / rowsPerPage);

//   for (let i = 1; i <= totalPages; i++) {
//     const link = document.createElement('a');
//     link.href = '#';
//     link.textContent = i;
//     link.addEventListener('click', () => {
//       currentPage = i;
//       renderTableData();
//       renderPaginationLinks();
//     });
//     pagination.appendChild(link);
//   }
// }

// // render the table data and pagination links on page load
// renderTableData();
// renderPaginationLinks();
function toggleSidebar() {
  sidebar.classList.toggle("close");
  toggleButton.classList.toggle("rotate");

  closeAllSubMenus();
}

function toggleSubMenu(button) {
  if (!button.nextElementSibling.classList.contains("show")) {
    closeAllSubMenus();
  }

  button.nextElementSibling.classList.toggle("show");
  button.classList.toggle("rotate");

  if (sidebar.classList.contains("close")) {
    sidebar.classList.toggle("close");
    toggleButton.classList.toggle("rotate");
  }
}

function closeAllSubMenus() {
  Array.from(sidebar.getElementsByClassName("show")).forEach((ul) => {
    ul.classList.remove("show");
    ul.previousElementSibling.classList.remove("rotate");
  });
}

// Get the modal container and content elements

// Add event listener to the add book button
addBtn.addEventListener("click", () => {
  // Show the modal container
  modalContainer.style.display = "flex";
});

// Function to hide the modal container


// Add event listener to the modal container (for clicking outside the modal)
modalContainer.addEventListener("click", (e) => {
  // If the click is outside the modal content, hide the modal container
  if (e.target === modalContainer) {
    modalContainer.style.display = "none";
  }
});

// Add event listener to the modal container (for clicking outside the modal)
modalContainer.addEventListener("click", (e) => {
  // If the click is outside the modal content, hide the modal container
  if (e.target === modalContainer) {
    modalContainer.style.display = "none";
  }
});

inputs.forEach((inp) => {
  inp.addEventListener("focus", () => {
    inp.classList.add("active");
  });
  inp.addEventListener("blur", () => {
    if (inp.value != "") return;
    inp.classList.remove("active");
  });
});

toggle_btn.forEach((btn) => {
  btn.addEventListener("click", () => {
    main.classList.toggle("sign-up-mode");
  });
});
